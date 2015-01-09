<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;

class ProfileController extends BaseJudgeController
{
    public function profileAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');

        /** @var \Judge\Repository\User $userRepo */
        $userRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );
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

        $user = $userRepo->find($id);

        $algorithmSubmissions = $algorithmSubmissionRepo->findForUser($user);
        $miscSubmissions = $miscSubmissionRepo->findForUser($user);

        $pIds = array();
        foreach ($algorithmSubmissions as $algSubmission) {
            $pIds[] = $algSubmission->getProblemId();
        }
        foreach ($miscSubmissions as $miscSubmission) {
            $pIds[] = $miscSubmission->getProblemId();
        }

        $problems = $problemsRepo->findInIdsAssoc($pIds);

        $view = new ViewModel();

        $view->setVariables(
            array(
                'user' => $user,
                'algorithmSubmissions' => $algorithmSubmissions,
                'miscSubmissions' => $miscSubmissions,
                'problemsLookup' => $problems
            )
        );

        return $view;
    }
}
