<?php
namespace Core\Adapter;

use Judge\Document\User;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result as AuthenticationResult;
use Doctrine\ODM\MongoDB\DocumentManager;

class AuthAdapter extends AbstractAdapter
{
    protected $username;

    protected $password;

    /**
     * @var DocumentManager $documentManager
     */
    protected $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->setDocumentManager($documentManager);
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );
        /** @var \Judge\Document\User $user */
        $user = $repo->findOneBy(
            array(
                'username' => $this->getUsername()
            )
        );

        if (!$user) {
            return new AuthenticationResult(
                AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND,
                null,
                array(
                    'User not found.'
                )
            );
        }

        if (!$user->verifyPassword($this->getPassword())) {
            return new AuthenticationResult(
                AuthenticationResult::FAILURE_CREDENTIAL_INVALID,
                $user->getId(),
                array(
                    "Username and password combination not valid."
                )
            );
        }
        return new AuthenticationResult(
            AuthenticationResult::SUCCESS,
            $user->getId()
        );
    }

    /**
     * @param mixed $documentManager
     */
    public function setDocumentManager($documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
}
