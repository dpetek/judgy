<?php

namespace Judge\Document;

use Core\Document\Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="judge",collection="MiscSubmissions",slaveOkay=false, repositoryClass="Judge\Repository\MiscUserSubmission")
 */
class MiscUserSubmission extends Base
{
    /**
     * @ODM\Date(name="tc")
     */
    protected $timeCreated;

    /**
     * @ODM\Boolean(name="solved")
     */
    protected $solved;

    /**
     * @ODM\String(name="answer")
     */
    protected $answer;

    /**
     * @ODM\ObjectId(name="pid")
     */
    protected $problemId;

    /**
     * @ODM\ObjectId(name="uid")
     */
    protected $userId;

    /**
     * @ODM\Float(name="score")
     */
    protected $score;

    public function validate()
    {
        return true;
    }

    public static function create(User $user, ActiveProblem $problem, $answer, $solved)
    {
        $instance = new self();
        $instance->setAnswer($answer);
        $instance->setSolved($solved);
        $instance->setUserId(new \MongoId($user->getId()));
        $instance->setProblemId(new \MongoId($problem->getId()));
        $instance->setTimeCreated(new \DateTime());
        return $instance;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
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
     * @param mixed $timeCreated
     */
    public function setTimeCreated($timeCreated)
    {
        $this->timeCreated = $timeCreated;
    }

    /**
     * @return mixed
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * @param mixed $problemId
     */
    public function setProblemId($problemId)
    {
        $this->problemId = $problemId;
    }

    /**
     * @return mixed
     */
    public function getProblemId()
    {
        return $this->problemId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
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
}
