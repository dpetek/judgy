<?php
namespace Api\Controller;

use FileManager\Storage\LocalFileStorage;
use Judge\Document\ActiveProblem;
use Judge\Document\BaseProblem;
use Judge\Document\UserSubmission;
use Judge\Document\AlgorithmUserSubmission;
use Judge\Document\ReviewProblem;
use Judge\Document\Tag;
use Judge\Leptir\JudgeAlgorithm;
use Zend\View\Model\JsonModel;
use Api\Exception\Core\NoPermissionException;
use Api\Exception\Core\MissingResource;
use Api\Exception\Core\CustomException;

class ProblemsController extends BaseApiController
{

    public function postSubmitAction()
    {
        $request = $this->getRequest();
        $routeMatch = $this->getEvent()->getRouteMatch();

        $post = $request->getPost();
        $title = $post['title'];
        $description = $post['description'];
        $answer = isset($post['answer']) ? $post['answer'] : '';
        $difficulty = intval($post['difficulty']);
        $tags = isset($post['tags']) ? $post['tags'] : array();

        if ($difficulty < 0) {
            throw new CustomException(
                'Difficulty value must be a positive integer.',
                400
            );
        }

        $tagRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Tag'
        );

        if (is_string($tags)) {
            $tags = json_decode($tags, true);
        }

        $tagObjects = array();
        foreach ($tags as $tag) {
            if (!isset($tag['text'])) {
                continue;
            }
            $existing = $tagRepo->findOneBy(
                array(
                    'text' => Tag::normalizeText($tag['text'])
                )
            );
            if ($existing) {
                $tagObjects[] = $existing;
            } else {
                $newTagObject = Tag::create($tag['text']);
                $this->getDocumentManager()->persist($newTagObject);
                $tagObjects[] = $newTagObject;
            }
        }

        switch ($routeMatch->getParam('type')) {
            case 'misc':
                if ($this->getCurrentUser()->getIsAdmin()) {
                    $problem = ActiveProblem::createMisc(
                        $title,
                        $description,
                        $answer,
                        $difficulty,
                        $this->getCurrentUser(),
                        $tagObjects
                    );
                } else {
                    $problem = ReviewProblem::createMisc(
                        $title,
                        $description,
                        $answer,
                        $difficulty,
                        $this->getCurrentUser(),
                        $tagObjects
                    );
                }
                break;
            case 'algorithm':
                if ($this->getCurrentUser()->getIsAdmin()) {
                    $problem = ActiveProblem::createAlgorithm(
                        $title,
                        $description,
                        $difficulty,
                        $this->getCurrentUser(),
                        $tagObjects
                    );
                } else {
                    $problem = ReviewProblem::createAlgorithm(
                        $title,
                        $description,
                        $difficulty,
                        $this->getCurrentUser(),
                        $tagObjects
                    );
                }
                break;
        }

        if ($problem) {
            // to generate id
            $this->getDocumentManager()->persist($problem);
        }

        $this->handleDataFile($problem);

        $this->getDocumentManager()->flush();

        return new JsonModel(
            $problem->toArray()
        );
    }

    protected function handleDataFile(ActiveProblem $problem)
    {
        if ($problem->getType() != BaseProblem::TYPE_ALGORITHM) {
            return ;
        }

        if (!isset($_FILES['file'])) {
            throw new MissingResource(
                array(
                    'message' => 'File not defined.'
                )
            );
        }

        $tempName = tempnam(sys_get_temp_dir(), 'judgy');
        $zip = new \ZipArchive();
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $tempName)) {
            throw new CustomException('File upload failed.');
        }

        $resource = $zip->open($tempName);

        $zip->extractTo('/var/www/judge_data/' . $problem->getId());
        $zip->close();
    }

    public function postAnswerAction()
    {
        if (!$this->getCurrentUser()) {
            throw new NoPermissionException();
        }

        $type = strtolower($this->getEvent()->getRouteMatch()->getParam('type'));
        $method = 'answer' . ucfirst($type);

        return $this->$method();
    }

    protected function answerMisc()
    {
        $response = $this->getRequest();
        $post = $response->getPost();

        $answer = $post['answer'];
        $problemId = $this->getEvent()->getRouteMatch()->getParam('id');

        /** @var \Judge\Repository\ActiveProblem $problemRepo */
        $problemRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );
        /** @var \Judge\Repository\UserSubmission $problemSubmissionRepo */
        $problemSubmissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\UserSubmission'
        );

        /** @var \Judge\Document\MiscProblem $problem */
        $problem = $problemRepo->find(new \MongoId($problemId));

        if (!$problem) {
            throw new MissingResource(
                array(
                    'resource' => 'Misc problem.'
                )
            );
        }
        $currentUser = $this->getCurrentUser();
        /** @var \Judge\Document\MiscUserSubmission $submission */
        $submission = $problemSubmissionRepo->findForUserAndProblem($problem, $currentUser);

        if (!$submission) {
            $submission = UserSubmission::create($problem, $currentUser);
            $problem->setAttempts($problem->getAttempts() + 1);
        }
        $correct = ($answer == $problem->getAnswer());

        if ($correct && !$submission->getSolved()) {
            $problem->setSolved($problem->getSolved() + 1);
            $currentUser->setMiscSolved($currentUser->getMiscSolved() + 1);
            $submission->setAttempts($submission->getAttempts() + 1);
            $submission->setSolved(true);
            $submission->setDateSolved(new \DateTime());
        } elseif (!$correct) {
            if (!$submission->getSolved()) {
                $submission->setAttempts($submission->getAttempts() + 1);
            } else {
                // problem already solved, but this submission is wrong
                $submission->setSolved(false);
                return new JsonModel($submission->toArray());
            }
        }

        $this->getDocumentManager()->persist($currentUser);
        $this->getDocumentManager()->persist($submission);
        $this->getDocumentManager()->persist($problem);
        $this->getDocumentManager()->flush();

        return new JsonModel($submission->toArray());
    }

    protected function answerAlgorithm()
    {
        $request = $this->getRequest();
        $post = $request->getPost();

        if (!isset($post['language'])) {
            throw new CustomException(
                'Language not selected'
            );
        }

        $userSubmissionDir = '/var/www/judge_data/submissions/' . $this->getCurrentUser()->getId();
        $userSubmissionProblemDir = $userSubmissionDir . '/' . $this->getEvent()->getRouteMatch()->getParam('id');

        if (!file_exists($userSubmissionDir)) {
            umask(0777);
            mkdir($userSubmissionDir, 0777, true);
        }
        if (!file_exists($userSubmissionProblemDir)) {
            umask(0777);
            mkdir($userSubmissionProblemDir, 0777, true);
        }
        $problemId = $this->getEvent()->getRouteMatch()->getParam('id');

        $directory = '/var/www/judge_data/submissions/' . $this->getCurrentUser()->getId() . '/' . $problemId . '/';
        if (!file_exists($directory)) {
            umask(0777);
            mkdir($directory, 0777, true);
        }

        $tempName = $directory . 'solution.' . $post['language'];

        if (!isset($_FILES['file'])) {
            throw new CustomException(
                'Solution file not included.'
            );
        }

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $tempName)) {
            throw new CustomException('File upload failed.');
        }

        chmod($tempName, 0777);

        /** @var \Judge\Repository\ActiveProblem $problemRepo */
        $problemRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );
        $problem = $problemRepo->find(new \MongoId($problemId));
        $submission = AlgorithmUserSubmission::create(
            $this->getCurrentUser(),
            $problem,
            $post['language']
        );

        $this->getDocumentManager()->persist($submission);
        $this->getDocumentManager()->flush();

        $task = new JudgeAlgorithm(
            array(
                'asId' => (string)$submission->getId()
            )
        );

        /** @var \Leptir\Broker\Broker  $broker */
        $broker = $this->getServiceLocator()->get('leptir_broker');
        $broker->pushTask($task, null, 1);

        return new JsonModel(
            array(
                'error' => false,
                'taskId' => $task->getTaskId()
            )
        );
    }
}
