<?php
namespace Judge\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends BaseJudgeController
{
    public function indexAction()
    {
        /** @var \Judge\Repository\AlgorithmUserSubmission $algorithmSubmissionRepo */
        $algorithmSubmissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\AlgorithmUserSubmission'
        );
        /** @var \Judge\Repository\MiscUserSubmission $miscSubmissionRepo */
        $miscSubmissionRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\MiscUserSubmission'
        );

        /** @var \Judge\Repository\BaseProblem $problemsRepo */
        $problemsRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );

        /** @var \Judge\Repository\User $problemsRepo */
        $userRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );
        $algorithmSubmissions = $algorithmSubmissionRepo->findNew(0, 5);
        $miscSubmissions = $miscSubmissionRepo->findNew(0, 5);

        $pIds = array();
        $uIds = array();
        foreach ($algorithmSubmissions as $algSubmission) {
            $pIds[] = $algSubmission->getProblemId();
            $uIds[] = $algSubmission->getUserId();
        }
        foreach ($miscSubmissions as $miscSubmission) {
            $pIds[] = $miscSubmission->getProblemId();
            $uIds[] = $miscSubmission->getUserId();
        }

        $problems = $problemsRepo->findInIdsAssoc($pIds);
        $users = $userRepo->findInIdsAssoc($uIds);

        $view = new ViewModel();

        $view->setVariables(
            array(
                'algorithmSubmissions' => $algorithmSubmissions,
                'miscSubmissions' => $miscSubmissions,
                'problemsLookup' => $problems,
                'usersLookup' => $users
            )
        );

        return $view;
    }

    public function loginAction()
    {
        if ($this->getCurrentUser()) {
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }

    public function registerAction()
    {
        if ($this->getCurrentUser()) {
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }
}
