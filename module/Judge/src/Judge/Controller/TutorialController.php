<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;

class TutorialController extends BaseJudgeController
{
    public function submitAction()
    {
        if (!$this->getCurrentUser() || !$this->getCurrentUser()->getIsAdmin()) {
            return $this->redirect()->toRoute('judge/default', array('action' => 'index'));
        }

        return new ViewModel();
    }

    public function listAction()
    {
        $view = new ViewModel();

        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Tutorial'
        );
        $tutorials = $repo->findAll();
        $view->setVariables(
            array(
                'tutorials' => $tutorials
            )
        );
        return $view;
    }

    public function viewAction()
    {
        if (!$this->getCurrentUser()) {
            return $this->createLoginView();
        }

        $routeMatch = $this->getEvent()->getRouteMatch();
        $id = $routeMatch->getParam('id');

        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Tutorial'
        );
        /** @var \Judge\Document\Tutorial $tutorial */
        $tutorial = $repo->find(new \MongoId($id));

        $view = new ViewModel();

        $view->setVariables(
            array(
                'tutorial' => $tutorial
            )
        );

        return $view;
    }

}
