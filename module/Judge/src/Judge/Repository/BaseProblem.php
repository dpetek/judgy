<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class BaseProblem extends DocumentRepository
{
    public function findNew($offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('ta', -1);
        $qb->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }

    public function findNewByType($type, $tag = null, $offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('type')->equals($type);
        if ($tag) {
            $qb->field('t')->equals($tag);
        }
        $qb->sort('ta', -1);
        $qb->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }

    public function countByType($type, $tag = null)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('type')->equals($type);
        if ($tag) {
            $qb->field('t')->equals($tag);
        }

        return $qb->count()->getQuery()->execute();
    }

    public function findInIdsAssoc($ids)
    {
        $mIds = array_map(function($id) {return new \MongoId($id); }, $ids);
        $qb = $this->createQueryBuilder()->field('_id')->in($mIds);
        return $qb->getQuery()->toArray();
    }
}
