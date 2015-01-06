<?php

namespace Judge\Document;

use Core\Document\Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;

/**
 * @ODM\Document(db="judge",collection="AlgorithmSubmissions",slaveOkay=false, repositoryClass="Judge\Repository\AlgorithmUserSubmission")
 */
class AlgorithmUserSubmission extends Base implements IResponse
{
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';
    const STATUS_BUILD_FAIL = 'build_fail';

    /**
     * @ODM\String(name="processedMessage")
     */
    protected $message;

    /**
     * @ODM\Float(name="score")
     */
    protected $score;

    /**
     * @ODM\Date(name="tc")
     */
    protected $timeCreated;

    /**
     * @ODM\ObjectId(name="pid")
     */
    protected $problemId;

    /**
     * @ODM\ObjectId(name="uid")
     */
    protected $userId;

    /**
     * @ODM\Int(name="totalCases")
     */
    protected $totalCases;

    /**
     * @ODM\Int(name="solvedCases")
     */
    protected $solvedCases;

    /**
     * @ODM\String(name="status")
     */
    protected $status;

    /**
     * @ODM\String(name="lang")
     */
    protected $language;

    public static function create(User $user, ActiveProblem $problem, $language)
    {
        $instance = new self();
        $instance->setUserId(new \MongoId($user->getId()));
        $instance->setProblemId(new \MongoId($problem->getId()));
        $instance->setTimeCreated(new \DateTime());
        $instance->setStatus(self::STATUS_PENDING);
        $instance->setLanguage($language);
        return $instance;
    }

    public function validate()
    {
        return true;
    }

    public function toArray()
    {
        return array(

        );
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
     * @param mixed $solvedCases
     */
    public function setSolvedCases($solvedCases)
    {
        $this->solvedCases = $solvedCases;
    }

    /**
     * @return mixed
     */
    public function getSolvedCases()
    {
        return $this->solvedCases;
    }

    /**
     * @param mixed $totalCases
     */
    public function setTotalCases($totalCases)
    {
        $this->totalCases = $totalCases;
    }

    /**
     * @return mixed
     */
    public function getTotalCases()
    {
        return $this->totalCases;
    }

    /**
     * @param mixed $processedMessage
     */
    public function setMessage($processedMessage)
    {
        $this->message = $processedMessage;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getStringFromStatus()
    {
        switch ($this->getStatus()) {
            case self::STATUS_FAIL:
                return 'Failed.';
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_SUCCESS:
                return 'Success';
            case self::STATUS_BUILD_FAIL:
                return 'Build failed';
        }
        return 'Unknown';
    }

    public function getClassFromStatus()
    {
        switch ($this->getStatus()) {
            case self::STATUS_FAIL:
                return 'danger';
            case self::STATUS_PENDING:
                return 'info';
            case self::STATUS_SUCCESS:
                return 'success';
            case self::STATUS_BUILD_FAIL:
                return 'danger';
        }
        return 'primary';
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
