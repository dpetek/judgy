<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Core\Document\Base;

/**
 * @ODM\Document(db="judge",collection="Ratings",repositoryClass="Judge\Repository\Rating")
 */
class Rating extends Base
{
    /**
     * @ODM\Int(name="value")
     */
    protected $value;

    /**
     * @ODM\ObjectId(name="uid")
     */
    protected $userId;

    /**
     * @ODM\ObjectId(name="tid")
     */
    protected $targetId;

    public static function create(User $user, Base $target, $value)
    {
        $instance = new self();
        $instance->setValue($value);
        $instance->setTargetId(new \MongoId($target->getId()));
        $instance->setUserId(new \MongoId($user->getId()));
        return $instance;
    }

    public function validate()
    {
        return true;
    }

    public function toArray()
    {
        return array(
            'id' => (string)$this->getId(),
            'rating' => $this->getValue()
        );
    }

    /**
     * @param mixed $targetId
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    /**
     * @return mixed
     */
    public function getTargetId()
    {
        return $this->targetId;
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
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
