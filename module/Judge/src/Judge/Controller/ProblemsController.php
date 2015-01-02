<?php
namespace Judge\Controller;

use Judge\Document\BaseProblem;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProblemsController extends BaseJudgeController
{
    public function problemsAction()
    {
        $view = new ViewModel();
        /** @var \Judge\Repository\BaseProblem $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );

        $type = $this->getEvent()->getRouteMatch()->getParam('type');
        $problems = $repo->findNewByType($type);

        if ($this->getCurrentUser()) {
            /** @var \Judge\Repository\UserSubmission $submissionRepo */
            $submissionRepo = $this->getDocumentManager()->getRepository(
                'Judge\Document\UserSubmission'
            );

            /** @var \Judge\Document\ActiveProblem $problem */
            foreach ($problems as $problem) {
                $submission = $submissionRepo->findForUserAndProblem($problem, $this->getCurrentUser());
                $problem->setUserSubmission($submission);
            }
        }

        $title = 'Active problems';

        $view->setVariables(
            array(
                'problems' => $problems,
                'title' => $title,
                'type' => $type
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
            'Judge\Document\ActiveProblem'
        );

        $problem = $repo->find(new \MongoId($id));

        if (!$problem) {
            return $this->redirect()->toRoute(
                'problems-view/default',
                array(
                    'action' => 'problems',
                    'type' => 'misc'
                )
            );
        }
        $submission = null;
        if ($this->getCurrentUser()) {
            /** @var \Judge\Repository\UserSubmission $submissionRepo */
            $submissionRepo = $this->getDocumentManager()->getRepository(
                'Judge\Document\UserSubmission'
            );
            $submission = $submissionRepo->findForUserAndProblem($problem, $this->getCurrentUser());
        }
        $view->setVariables(
            array(
                'problem' => $problem,
                'submission' => $submission,
                'loggedIn' => ($this->getCurrentUser() != null),
                'type' => strtlower($this->getEvent()->getParam('type'))
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
        $type = strtolower($this->getEvent()->getRouteMatch()->getParam('type'));
        $view->setVariables(
            array(
                'users' => $repo->findTopByType($type),
                'title' => $type == BaseProblem::TYPE_MISC ? 'Misc questions scoreboard' : 'Algorithm scoreboard',
                'type' => $type,
                'scoreMethod' => 'get' . ucfirst($type) . 'Score',
                'solvedMethod' => 'get' . ucfirst($type) . 'Solved'
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
