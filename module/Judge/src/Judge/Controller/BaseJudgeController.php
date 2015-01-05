<?php

namespace Judge\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Authentication\AuthenticationService;

class BaseJudgeController extends AbstractActionController
{
    public function onDispatch(MvcEvent $e)
    {
        // top navigation
        $auth = $e->getApplication()->getServiceManager()->get('authentication');
        $userId = $auth->getIdentity();
        $topNav = new ViewModel();
        $topNav->setTemplate('layout/topNav');
        $topNav->setVariables(
            array(
                'user' => $this->getCurrentUser()
            )
        );

        $this->layout()->addChild($topNav, 'topNavigation');


        // side navigation
        $sideNav = new ViewModel();
        $sideNav->setTemplate('layout/sideNav');

        $sideNav->setVariables(
            array(
                'routeName' => $e->getRouteMatch()->getMatchedRouteName(),
                'action' => $e->getRouteMatch()->getParam('action'),
                'type' => $e->getRouteMatch()->getParam('type', ''),
            )
        );

        $this->layout()->addChild($sideNav, 'sideNavigation');
        return parent::onDispatch($e);
    }

    protected function createLoginView()
    {
        $view = new ViewModel();
        $view->setTemplate('judge/index/login');
        return $view;
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        /* @var DocumentManager $documentManager */
        $documentManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        return $documentManager;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthentication()
    {
        return $this->getServiceLocator()->get('authentication');
    }

    /**
     * @return \Judge\Document\User|null
     */
    public function getCurrentUser()
    {
        $id = $this->getAuthentication()->getIdentity();
        if (!$id) {
            return null;
        }
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );

        return $repo->find(new \MongoId($id));
    }
}
