<?php

namespace Api\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\View\Http\ExceptionStrategy;
use Api\Exception\ApiException;
use Zend\View\Model\JsonModel;

class JsonExceptionStrategy extends ExceptionStrategy
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            array($this, 'prepareExceptionViewModel'),
            1000
        );
    }

    public function prepareExceptionViewModel(MvcEvent $e)
    {
        // Do nothing if no error in the event
        $error = $e->getError();
        if (empty($error)) {
            return;
        }
        switch($error) {
            case Application::ERROR_EXCEPTION:
                $response = $e->getResponse();
                if (!($response instanceof \Zend\Http\PhpEnvironment\Response)) {
                    break;
                }
                $exception = $e->getParam('exception');

                if ($exception instanceof ApiException) {
                    $e->setViewModel(
                        new JsonModel(
                            array(
                                'error' => true,
                                'message' => $exception->getMessage(),
                                'meta' => $exception->getAdditional()
                            )
                        )
                    );
                    $e->stopPropagation(true);
                    $response->setStatusCode($exception->getResponseCode());
                }
                break;
        }
    }
}
