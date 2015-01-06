<?php
namespace Judge\Controller;

use Judge\Document\BaseProblem;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProblemsController extends BaseJudgeController
{
    public function problemsAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();
        $view = new ViewModel();
        /** @var \Judge\Repository\BaseProblem $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );

        $type = $this->getEvent()->getRouteMatch()->getParam('type');
        $problems = $repo->findNewByType($type, isset($query['tag']) ? $query['tag'] : null);

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

        $title = 'Active ' . ucfirst($type) . ' Problems';

        $view->setVariables(
            array(
                'problems' => $problems,
                'title' => $title . (isset($query['tag']) ? ' (tag: ' . $query['tag'] . ')' : ''),
                'type' => $type,
                'tag' => isset($query['tag']) ? $query['tag'] : null
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
        /** @var \Judge\Document\UserSubmission $submission */
        $submission = null;
        if ($this->getCurrentUser()) {
            /** @var \Judge\Repository\UserSubmission $submissionRepo */
            $submissionRepo = $this->getDocumentManager()->getRepository(
                'Judge\Document\UserSubmission'
            );
            $submission = $submissionRepo->findForUserAndProblem($problem, $this->getCurrentUser());
        }
        $type = strtolower($this->getEvent()->getRouteMatch()->getParam('type'));
        $view->setTemplate('judge/problems/' . $type . '_problem');

        $variables = array(
            'problem' => $problem,
            'submission' => $submission,
            'loggedIn' => ($this->getCurrentUser() != null),
            'type' => $type
        );

        $judgyConfig = $this->getServiceLocator()->get('config')['judgy'];

        $cooldownLeft = 0;
        if (($this->getCurrentUser() && !$this->getCurrentUser()->getIsAdmin()) && $submission && ($submission->getDateLastSubmission() instanceof \DateTime) &&  isset($judgyConfig[$type]['cooldown_time'])) {
            $now = new \DateTime();
            $timePassed = $now->getTimestamp() - $submission->getDateLastSubmission()->getTimestamp();

            if ($timePassed <= 60 * intval($judgyConfig[$type]['cooldown_time'])) {
                $cooldownLeft = 60 * intval($judgyConfig[$type]['cooldown_time']) - $timePassed;
            }
        }
        $variables['cooldownLeft'] = $cooldownLeft;

        if ($type == 'algorithm') {
            /** @var \Judge\Repository\AlgorithmUserSubmission $algSubmissionRepo */
            $algSubmissionRepo = $this->getDocumentManager()->getRepository(
                'Judge\Document\AlgorithmUserSubmission'
            );
            $algorithmSubmissions = null;
            if ($this->getCurrentUser()) {
                $algorithmSubmissions = $algSubmissionRepo->findForProblemAndUser($problem, $this->getCurrentUser());
            }
            $variables['algorithmSubmissions'] = $algorithmSubmissions;
        }

        $view->setVariables($variables);

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
        $viewModel = new ViewModel();
        $type = strtolower($this->getEvent()->getRouteMatch()->getParam('type'));
        $viewModel->setTemplate('judge/problems/' . $type . '_submit');
        return $viewModel;
    }
}
