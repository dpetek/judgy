<?php
namespace Api\Controller;

use FileManager\Storage\LocalFileStorage;
use Judge\Document\ActiveProblem;
use Judge\Document\BaseProblem;
use Judge\Document\UserSubmission;
use Judge\Document\AlgorithmUserSubmission;
use Judge\Document\MiscUserSubmission;
use Judge\Document\ReviewProblem;
use Judge\Document\Tag;
use Judge\Leptir\JudgeAlgorithm;
use Judge\Document\Notification;
use Zend\View\Model\JsonModel;
use Api\Exception\Core\NoPermissionException;
use Api\Exception\Core\MissingResource;
use Api\Exception\Core\CustomException;
use Api\Exception\Core\CooldownException;

class ProblemsController extends BaseApiController
{

    public function postSubmitAction()
    {
        if (!$this->getCurrentUser()) {
            throw new NoPermissionException();
        }

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
        $tagsList = array();
        foreach ($tags as $tag) {
            if (!isset($tag['text'])) {
                continue;
            }
            $nn = Tag::normalizeText($tag['text']);
            if (!in_array($nn, $tagsList)) {
                $tagsList[] = $nn;
            }
            $existing = $tagRepo->findOneBy(
                array(
                    'text' => $nn
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
                        $tagsList
                    );
                } else {
                    $problem = ReviewProblem::createMisc(
                        $title,
                        $description,
                        $answer,
                        $difficulty,
                        $this->getCurrentUser(),
                        $tagsList
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
                        $tagsList
                    );
                } else {
                    $problem = ReviewProblem::createAlgorithm(
                        $title,
                        $description,
                        $difficulty,
                        $this->getCurrentUser(),
                        $tagsList
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

    protected function handleDataFile(BaseProblem $problem)
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
        /** @var \Judge\Document\UserSubmission $submission */
        $submission = $problemSubmissionRepo->findForUserAndProblem($problem, $currentUser);

        if (!$submission) {
            $submission = UserSubmission::create($problem, $currentUser);
            $problem->setAttempts($problem->getAttempts() + 1);
        } else {
            $lastSubmissionDate = $submission->getDateLastSubmission();

            if ($lastSubmissionDate instanceof \DateTime) {
                $config = $this->getServiceLocator()->get('config');
                if (isset($config['judgy']['misc']['cooldown_time'])) {
                    $cooldown = intval($config['judgy']['misc']['cooldown_time']);
                    $now = new \DateTime();
                    if (!$this->getCurrentUser()->getIsAdmin() && $now->getTimestamp() - $lastSubmissionDate->getTimestamp() <= $cooldown * 60) {
                        throw new CooldownException(
                            $cooldown * 60 - ($now->getTimestamp() - $lastSubmissionDate->getTimestamp())
                        );
                    }
                }
            }
        }
        $submission->setDateLastSubmission(new \DateTime());

        $correct = ($answer == $problem->getAnswer());

        $miscSubmission = MiscUserSubmission::create($this->getCurrentUser(), $problem, $answer, $correct);
        $this->getDocumentManager()->persist($miscSubmission);

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

        $notification = Notification::create(
            $this->getCurrentUser(),
            $correct ? "Correct answer for question '" . $problem->getTitle() . "'" :
                "Wrong answer for question '" . $problem->getTitle() . "'",
            $correct ? "notification-success" : "notification-wrong",
            $this->url()->fromRoute('problems-view/default', array('action' => 'problem', 'type' => 'misc', 'id' => (string)$problem->getId())),
            ''
        );

        $this->getDocumentManager()->persist($notification);
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
            chmod($userSubmissionDir, 0777);
        }
        if (!file_exists($userSubmissionProblemDir)) {
            umask(0777);
            mkdir($userSubmissionProblemDir, 0777, true);
            chmod($userSubmissionProblemDir, 0777);
        }
        $problemId = $this->getEvent()->getRouteMatch()->getParam('id');

        $directory = '/var/www/judge_data/submissions/' . $this->getCurrentUser()->getId() . '/' . $problemId . '/';
        if (!file_exists($directory)) {
            umask(0777);
            mkdir($directory, 0777, true);
            chmod($directory, 0777);
        }

        $ext = $post['language'];

        switch($post['language']) {
            case 'py2':
                $ext = 'py';
                break;
        }

        $tempName = $directory . 'solution.' . $ext;

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
        /** @var \Judge\Repository\UserSubmission $problemSubmissionRepo */
        $problemSubmissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\UserSubmission'
        );

        $problem = $problemRepo->find(new \MongoId($problemId));

        /** @var \Judge\Document\UserSubmission $userSubmission */
        $userSubmission = $problemSubmissionRepo->findForUserAndProblem($problem, $this->getCurrentUser());
        $userSubmission->setDateLastSubmission(new \DateTime());
        $this->getDocumentManager()->persist($userSubmission);

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
