<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;
use Core\Document\Base;

/**
 * @ODM\Document(db="judge",collection="MiscSubmissions",slaveOkay=false, repositoryClass="Judge\Repository\MiscUserSubmission")
 */
class MiscUserSubmission extends Base implements IResponse
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
     * @ODM\ObjectId(name="u")
     */
    protected $user;

    public static function create(MiscProblem $problem, User $user)
    {
        $instance = new self();
        $instance->setProblem($problem->getId());
        $instance->setUser($user->getId());
        $instance->setAttempts(0);
        $instance->setSolved(false);
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
}
