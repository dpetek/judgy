<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;
use Judge\Document\User;

class AlgorithmsController extends BaseJudgeController
{
    public function problemsAction()
    {
        return new ViewModel();
    }

    public function scoreboardAction()
    {
        $view = new ViewModel();
        /** @var \Judge\Repository\User $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );

        $view->setVariables(
            array(
                'users' => $repo->findTopScore()
            )
        );
        return $view;
    }

    public function submitAction()
    {
        return new ViewModel();
    }
}
