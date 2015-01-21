<?php
namespace Judge\Document;

use Core\Document\Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="judge",collection="Tutorials",slaveOkay=false, repositoryClass="Judge\Repository\Tutorial")
 */
class Tutorial extends Base
{
    /**
     * @ODM\String(name="content")
     */
    protected $content;
    /**
     * @ODM\String(name="title")
     */
    protected $title;
    /**
     * @ODM\String(name="type")
     */
    protected $type;

    /**
     * @ODM\ReferenceOne(name="user", targetDocument="Judge\Document\User")
     */
    protected $user;

    public static function create(\Judge\Document\User $user, $title, $content, $type)
    {
        $instance = new self();
        $instance->setUser($user);
        $instance->setContent($content);
        $instance->setTitle($title);
        $instance->setType($type);
        return $instance;
    }

    public function validate()
    {
        return true;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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
     * @param \Judge\Document\User $user
     */
    public function setUser(\Judge\Document\User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Judge\Document\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
