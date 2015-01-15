<?php
namespace Api\Controller;

use Api\Exception\Core\CustomException;
use Judge\Document\User;
use Zend\Config\Writer\Json;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class UserController extends BaseApiController
{
    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        $request = $this->getRequest();
        $postParams = $request->getPost();
        $username = strtolower($postParams['username']);
        $password = $postParams['password'];
        $passwordConfirmation = $postParams['passwordConfirmation'];
        $firstName = $postParams['firstName'];
        $lastName = $postParams['lastName'];
        $email = $postParams['email'];

        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\User'
        );

        // check if username exists
        $existing = $repo->findOneBy(
            array(
                'username' => $username
            )
        );
        if ($existing) {
            return new JsonModel(
                array(
                    'error' => true,
                    'message' => 'Username is taken.'
                )
            );
        }

        // check if email exists
        $existing = $repo->findOneBy(
            array(
                'email' => $email
            )
        );
        if ($existing) {
            return new JsonModel(
                array(
                    'error' => true,
                    'message' => 'Email is taken.'
                )
            );
        }

        if ($password != $passwordConfirmation) {
            return new JsonModel(
                array(
                    'error' => true,
                    'message' => 'Password confirmation doesnt match.'
                )
            );
        }

        if (strlen($password) < 5) {
            return new JsonModel(
                array(
                    'error' => true,
                    'message' => 'Password too short. Minimum 6 characters.'
                )
            );
        }

        $username = strtolower($username);

        if (preg_match('/[^a-z0-9]/', $username)) {
            throw new CustomException("Username can contain only english characters and numbers.");
        }


        $user = User::create($username, $firstName . ' ' . $lastName, $email, $password);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        return $this->postLoginAction();
    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id)
    {
        // TODO: Implement get() method.
    }

    /**
     * Return list of resources
     *
     * @return mixed
     */
    public function getList()
    {
        // TODO: Implement getList() method.
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function postLoginAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();

        $username = $post['username'];
        $password = $post['password'];


        /** @var \Zend\Authentication\AuthenticationService $auth */
        $auth = $this->getServiceLocator()->get('authentication');

        /** @var \Core\Adapter\AuthAdapter $adapter */
        $adapter = $auth->getAdapter();
        $adapter->setUsername($username);
        $adapter->setPassword($password);

        $auth->authenticate($adapter);

        if ($auth->getIdentity()) {
            return new JsonModel(
                $this->getCurrentUser()->toArray()
            );
        }

        return new JsonModel(
            array(
                'error' => true,
                'message' => 'Username and password combination wrong.'
            )
        );
    }

    public function postLogoutAction()
    {
        /** @var \Zend\Authentication\AuthenticationService $auth */
        $auth = $this->getServiceLocator()->get('authentication');
        $currentUser = $this->getCurrentUser();
        $auth->clearIdentity();
        return new JsonModel(
            $currentUser->toArray()
        );
    }
}
