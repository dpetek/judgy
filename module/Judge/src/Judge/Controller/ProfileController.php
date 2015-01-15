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

        $user = $userRepo->find($id);

        $algorithmSubmissions = $algorithmSubmissionRepo->findForUser($user);
        $miscSubmissions = $miscSubmissionRepo->findForUser($user);

        $view = new ViewModel();
        $view->setVariables(
            array(
                'user' => $user,
            )
        );

        $view->addChild($this->renderMiscSubmissionsList($miscSubmissions, $this->getCurrentUser()), 'miscSubmissionsList');
        $view->addChild($this->renderAlgorithmSubmissionsList($algorithmSubmissions, $this->getCurrentUser()), 'algorithmSubmissionsList');

        return $view;
    }
}
