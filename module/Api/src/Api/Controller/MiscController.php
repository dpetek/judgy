<?php
namespace Api\Controller;

use Judge\Document\MiscProblem;
use Judge\Document\MiscProblemReview;
use Judge\Document\MiscUserSubmission;
use Judge\Document\Tag;
use Zend\View\Model\JsonModel;
use Api\Exception\Core\NoPermissionException;
use Api\Exception\Core\MissingResource;
use Api\Exception\Core\CustomException;

class MiscController extends BaseApiController
{

    public function create($data)
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $title = $post['title'];
        $description = $post['description'];
        $answer = $post['answer'];
        $difficulty = intval($post['difficulty']);
        $tags = $post['tags'];

        if ($difficulty < 0) {
            throw new CustomException(
                'Difficulty value must be a positive integer.',
                400
            );
        }

        $tagRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Tag'
        );

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
        if ($this->getCurrentUser()->getIsAdmin()) {
            $problem = MiscProblem::create($title, $description, $answer, $difficulty,$this->getCurrentUser(), $tagObjects);
        } else {
            $problem = MiscProblemReview::create($title, $description, $answer, $difficulty, $this->getCurrentUser(), $tagObjects);
        }
        // if admin add it to the site, if regular user add it to review queue

        if ($problem) {
            $this->getDocumentManager()->persist($problem);
        }
        $this->getDocumentManager()->flush();

        return new JsonModel(
            $problem->toArray()
        );
    }

    public function postAnswerAction()
    {
        if (!$this->getCurrentUser()) {
            throw new NoPermissionException();
        }

        $response = $this->getRequest();
        $post = $response->getPost();

        $answer = $post['answer'];
        $problemId = $this->getEvent()->getRouteMatch()->getParam('id');

        /** @var \Judge\Repository\MiscProblem $problemRepo */
        $problemRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscProblem'
        );
        /** @var \Judge\Repository\MiscUserSubmission $problemSubmissionRepo */
        $problemSubmissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscUserSubmission'
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
            $submission = MiscUserSubmission::create($problem, $currentUser);
            $problem->setAttempts($problem->getAttempts() + 1);
        }
        $correct = ($answer == $problem->getAnswer());

        if ($correct && !$submission->getSolved()) {
            $currentUser->setMiscSolved($currentUser->getMiscSolved() + 1);

            $problem->setSolved($problem->getSolved() + 1);

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
}
