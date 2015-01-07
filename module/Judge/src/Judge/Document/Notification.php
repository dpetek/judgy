<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Core\Document\Base;
use Judge\Document\User as UserDocument;

/**
 * @ODM\Document(db="judge",collection="Notifications",repositoryClass="Judge\Repository\Notification")
 */
class Notification extends Base
{
    /**
     * @ODM\ObjectId(name="uid")
     */
    protected $userId;

    /**
     * @ODM\String(name="message")
     */
    protected $message;

    /**
     * @ODM\String(name="htmlMessage")
     */
    protected $cssClass;

    /**
     * @ODM\String(name="link")
     */
    protected $link;

    /**
     * @ODM\String(name="icon")
     */
    protected $icon;

    /**
     * @ODM\Boolean(name="read")
     */
    protected $read;

    /**
     * @ODM\Date(name="tc")
     */
    protected $timeCreated;

    public static function create(UserDocument $user, $message, $cssClass, $link, $icon)
    {
        $instance = new self();
        $instance->setUserId(new \MongoId($user->getId()));
        $instance->setMessage($message);
        $instance->setCssClass($cssClass);
        $instance->setLink($link);
        $instance->setIcon($icon);
        $instance->setRead(false);
        $instance->setTimeCreated(new \DateTime());
        return $instance;
    }

    public function validate()
    {
        return true;
    }

    /**
     * @param mixed $htmlMessage
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * @return mixed
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $read
     */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
     * @return mixed
     */
    public function getRead()
    {
        return $this->read;
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
}
