<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class Tag extends DocumentRepository
{
    public function findByPrefix($prefix, $offset = 0, $limit = 10)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('text')->equals(new \MongoRegex('/^' . $prefix . '/'));
        $qb->limit($limit)->skip($offset);

        return array_values($qb->getQuery()->toArray());
    }
}