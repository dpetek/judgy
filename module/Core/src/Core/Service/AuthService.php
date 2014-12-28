<?php
namespace Core\Service;

use Core\Adapter\AuthAdapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Authentication\Storage\Session as SessionStorage;

class AuthService implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var DocumentManager $dm */
        $dm = $serviceLocator->get('doctrine.documentmanager.odm_default');
        return new AuthenticationService(
            new SessionStorage(),
            new AuthAdapter($dm)
        );
    }

}
