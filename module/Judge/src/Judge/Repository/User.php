<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class User extends DocumentRepository
{
    public function findTopScore($offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('score', -1);
        $qb->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }

    public function findTopMiscProblems($offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('miscSolved', -1);
        $qb->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }

    public function findTopByType($type, $offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->sort($type . '_solved', -1)->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }
}
