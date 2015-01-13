<?php

namespace DoctrineMongoODMModule\Hydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class JudgeDocumentUserSubmissionHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="boolean") */
        if (isset($data['solved'])) {
            $value = $data['solved'];
            $return = (bool) $value;
            $this->class->reflFields['solved']->setValue($document, $return);
            $hydratedData['solved'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['dateSolved'])) {
            $value = $data['dateSolved'];
            if ($value instanceof \MongoDate) { $return = new \DateTime(); $return->setTimestamp($value->sec); } elseif (is_numeric($value)) { $return = new \DateTime(); $return->setTimestamp($value); } elseif ($value instanceof \DateTime) { $return = $value; } else { $return = new \DateTime($value); }
            $this->class->reflFields['dateSolved']->setValue($document, clone $return);
            $hydratedData['dateSolved'] = $return;
        }

        /** @Field(type="increment") */
        if (isset($data['attempts'])) {
            $value = $data['attempts'];
            $return = is_float($value) ? (float) $value : (int) $value;
            $this->class->reflFields['attempts']->setValue($document, $return);
            $hydratedData['attempts'] = $return;
        }

        /** @Field(type="object_id") */
        if (isset($data['p'])) {
            $value = $data['p'];
            $return = (string) $value;
            $this->class->reflFields['problem']->setValue($document, $return);
            $hydratedData['problem'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['type'])) {
            $value = $data['type'];
            $return = (string) $value;
            $this->class->reflFields['type']->setValue($document, $return);
            $hydratedData['type'] = $return;
        }

        /** @Field(type="object_id") */
        if (isset($data['u'])) {
            $value = $data['u'];
            $return = (string) $value;
            $this->class->reflFields['user']->setValue($document, $return);
            $hydratedData['user'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['dls'])) {
            $value = $data['dls'];
            if ($value instanceof \MongoDate) { $return = new \DateTime(); $return->setTimestamp($value->sec); } elseif (is_numeric($value)) { $return = new \DateTime(); $return->setTimestamp($value); } elseif ($value instanceof \DateTime) { $return = $value; } else { $return = new \DateTime($value); }
            $this->class->reflFields['dateLastSubmission']->setValue($document, clone $return);
            $hydratedData['dateLastSubmission'] = $return;
        }

        /** @Field(type="float") */
        if (isset($data['score'])) {
            $value = $data['score'];
            $return = (float) $value;
            $this->class->reflFields['score']->setValue($document, $return);
            $hydratedData['score'] = $return;
        }

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }
        return $hydratedData;
    }
}