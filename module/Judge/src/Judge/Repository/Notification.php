<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Judge\Document\User as UserDocument;

class Notification extends DocumentRepository
{
    public function findNewForUser(UserDocument $user, $offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('uid')->equals(new \MongoId($user->getId()));
        if ($limit > 0) {
            $qb->limit($limit);
        }
        $qb->skip($offset);
        $qb->sort('_id', -1);
        return array_values($qb->getQuery()->toArray());
    }

    public function countUnreadForUser(UserDocument $user)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('uid')->equals(new \MongoId($user->getId()))->field('read')->equals(false);
        return $qb->getQuery()->count();
    }

    public function markViewedForUser(UserDocument $user)
    {
        $qb = $this->createQueryBuilder();
        $qb->update()->multiple(true);
        $qb->field('uid')->equals(new \MongoId($user->getId()))->field('read')->equals(false);
        $qb->field('read')->set(true);
        $qb->getQuery()->execute();
    }
}
