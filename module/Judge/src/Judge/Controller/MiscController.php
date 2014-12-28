<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;

class MiscController extends BaseJudgeController
{
    public function problemsAction()
    {
        $view = new ViewModel();
        /** @var \Judge\Repository\MiscProblem $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscProblem'
        );
        /** @var \Judge\Repository\MiscUserSubmission $submissionRepo */
        $submissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscUserSubmission'
        );

        $problems = $repo->findNew();

        if ($this->getCurrentUser()) {
            /** @var \Judge\Document\MiscProblem $problem */
            foreach ($problems as $problem) {
                $submission = $submissionRepo->findForUserAndProblem($problem, $this->getCurrentUser());
                $problem->setUserSubmission($submission);
            }
        }

        $view->setVariables(
            array(
                'problems' => $problems
            )
        );
        return $view;
    }

    public function problemAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $view = new ViewModel();

        /** @var \Judge\Repository\MiscProblem $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscProblem'
        );
        /** @var \Judge\Repository\MiscUserSubmission $submissionRepo */
        $submissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscUserSubmission'
        );

        $problem = $repo->find(new \MongoId($id));

        if (!$problem) {
            return $this->redirect()->toRoute(
                'misc/default',
                array(
                    'action' => 'problems'
                )
            );
        }
        $submission = null;
        if ($this->getCurrentUser()) {
            $submission = $submissionRepo->findForUserAndProblem($problem, $this->getCurrentUser());
        }
        $view->setVariables(
            array(
                'problem' => $problem,
                'submission' => $submission,
                'loggedIn' => ($this->getCurrentUser() != null)
            )
        );

        return $view;
    }

    public function scoreboardAction()
    {
        if (!$this->getCurrentUser()) {
            return $this->createLoginView();
        }

        $view = new ViewModel();
        /** @var \Judge\Repository\User $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );

        $view->setVariables(
            array(
                'users' => $repo->findTopMiscProblems()
            )
        );
        return $view;

    }

    public function submitAction()
    {
        if (!$this->getCurrentUser()) {
            return $this->createLoginView();
        }
        return new ViewModel();
    }
}
