<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class MiscProblem extends DocumentRepository
{
    public function findNew($offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('ta', -1);
        $qb->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }
}
