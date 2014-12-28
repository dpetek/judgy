<?php

namespace Core\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
/**
 * @ODM\MappedSuperclass
 */
abstract class Base
{
    /**
     * @ODM\Id(strategy="Auto")
     */
    protected $id;

    abstract public function validate();

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
