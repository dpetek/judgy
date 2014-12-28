<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;
use Core\Document\Base;

/**
 * @ODM\Document(db="judge",collection="Users",slaveOkay=false, repositoryClass="Judge\Repository\User")
 */
class User extends Base implements IResponse
{
    /**
     * @ODM\String(name="username")
     */
    protected $username;

    /**
     * @ODM\String(name="name")
     */
    protected $name;

    /**
     * @ODM\String(name="ph")
     */
    protected $passwordHash;

    /**
     * @ODM\String(name="email")
     */
    protected $email;

    /**
     * @ODM\Float(name="score")
     */
    protected $score;

    /**
     * @ODM\Increment(name="miscSolved")
     */
    protected $miscSolved;

    /**
     * @ODM\Boolean(name="isadmin")
     */
    protected $isAdmin;

    public static function create($username, $name, $email, $password)
    {
        $instance = new self();
        $instance->setUsername(strtolower($username));
        $instance->setName($name);
        $instance->setEmail($email);
        $instance->setPasswordHash(self::hashPassword($password));
        $instance->setScore(0.0);
        $instance->setMiscSolved(0);
        $instance->setIsAdmin(false);
        return $instance;
    }

    public function validate()
    {
        if (preg_match('/[^a-z]/', $this->getUsername())) {
            throw new \Exception('Wrong username.');
        }
    }

    public function toArray()
    {
        return array(
            'id' => (string)$this->getId(),
            'username' => $this->getUsername(),
            'name' => $this->getname(),
            'score' => $this->getScore()
        );
    }

    public static function checkUsername()
    {

    }

    public static function hashPassword($password)
    {
        $options = array(
            'cost' => 11,
        );
        return \password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function verifyPassword($password)
    {
        return \password_verify($password, $this->getPasswordHash());
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $passwordHash
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
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

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $miscSolved
     */
    public function setMiscSolved($miscSolved)
    {
        $this->miscSolved = $miscSolved;
    }

    /**
     * @return mixed
     */
    public function getMiscSolved()
    {
        return $this->miscSolved;
    }

    /**
     * @param mixed $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }
}
