<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;
use Zend\Http\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Exception\DomainException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Authentication\AuthenticationService;


abstract class BaseApiController extends AbstractRestfulController
{
    public function onDispatch(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        if (! $routeMatch) {
            throw new DomainException('Missing route matches; unsure how to retrieve action');
        }

        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $event->getRequest();
        $method = $this->extractMethodFromRequest(
            $this,
            $request,
            $routeMatch
        );
        if ($method) {
            $return = $this->$method();
            $event->setResult($return);
            return $return;
        }

        return parent::onDispatch($event); // TODO: Change the autogenerated stub
    }



    protected function extractMethodFromRequest(
        BaseApiController $controller,
        Request $request,
        RouteMatch $routeMatch
    ) {
        // Was an "action" requested?
        $action  = $routeMatch->getParam('action', false);
        if ($action) {
            // Handle arbitrary methods, ending in Action
            $method = ucfirst(parent::getMethodFromAction($action));
            if ($request->getMethod() === $request::METHOD_POST && $request->getPost()->get('method') !== null &&
                defined(get_class($request) . '::METHOD_' . strtoupper($request->getPost()->get('method')))) {
                $method = strtolower($request->getPost()->get('method')) . $method;
            } else {
                $method = strtolower($request->getMethod()) . $method;
            }
            if (! method_exists($controller, $method)) {
                $method = 'notFoundAction';
            }
            return $method;
        }
        return false;
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
