<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;
use Core\Document\Base;
use Judge\Document\ActiveProblem;

/**
 * @ODM\Document(db="judge",collection="UserSubmissions",slaveOkay=false, repositoryClass="Judge\Repository\UserSubmission")
 */
class UserSubmission extends Base implements IResponse
{
    /**
     * @ODM\Boolean(name="solved")
     */
    protected $solved;

    /**
     * @ODM\Date(name="dateSolved")
     */
    protected $dateSolved;

    /**
     * @ODM\Increment(name="attempts")
     */
    protected $attempts;

    /**
     * @ODM\ObjectId(name="p")
     */
    protected $problem;

    /**
     * @ODM\String(name="type")
     */
    protected $type;

    /**
     * @ODM\ObjectId(name="u")
     */
    protected $user;

    /**
     * @ODM\Date(name="dls")
     */
    protected $dateLastSubmission;

    public static function create(ActiveProblem $problem, User $user)
    {
        $instance = new self();
        $instance->setProblem($problem->getId());
        $instance->setUser($user->getId());
        $instance->setAttempts(0);
        $instance->setSolved(false);
        $instance->setType($problem->getType());
        return $instance;
    }

    public function validate()
    {

    }

    public function toArray()
    {
        return array(
            'solved' => $this->getSolved(),
            'dateSolved' => $this->getDateSolved(),
            'attempts' => $this->getAttempts()
        );
    }

    /**
     * @param mixed $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     * @return mixed
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param mixed $dateSolved
     */
    public function setDateSolved($dateSolved)
    {
        $this->dateSolved = $dateSolved;
    }

    /**
     * @return mixed
     */
    public function getDateSolved()
    {
        return $this->dateSolved;
    }

    /**
     * @param mixed $solved
     */
    public function setSolved($solved)
    {
        $this->solved = $solved;
    }

    /**
     * @return mixed
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * @param mixed $problem
     */
    public function setProblem($problem)
    {
        $this->problem = $problem;
    }

    /**
     * @return mixed
     */
    public function getProblem()
    {
        return $this->problem;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $dateLastSubmission
     */
    public function setDateLastSubmission($dateLastSubmission)
    {
        $this->dateLastSubmission = $dateLastSubmission;
    }

    /**
     * @return \DateTIme|null
     */
    public function getDateLastSubmission()
    {
        return $this->dateLastSubmission;
    }
}
