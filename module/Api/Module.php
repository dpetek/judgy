<?php

namespace Api;

use Api\Service\JsonExceptionStrategy;
use Zend\Mvc\Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //Attach custom error handling on route event
        $sharedEventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sharedEventManager->attach(
            'Zend\Mvc\Application',
            MvcEvent::EVENT_ROUTE,
            array($this, 'exceptionHandling'),
            10000
        );
    }

    public function exceptionHandling(MvcEvent $e)
    {
        $strategy = new JsonExceptionStrategy();
        $strategy->attach($e->getApplication()->getEventManager());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array();
    }
}
