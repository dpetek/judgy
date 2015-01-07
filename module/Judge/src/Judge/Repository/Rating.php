<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Judge\Document\User as UserDocument;
use Core\Document\BaseRatable;

class Rating extends DocumentRepository
{
    public function findForUserAndTarget(UserDocument $user, BaseRatable $target)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('uid')->equals(new \MongoId($user->getId()))
            ->field('tid')->equals(new \MongoId($target->getId()));

        return $qb->getQuery()->getSingleResult();
    }
}
