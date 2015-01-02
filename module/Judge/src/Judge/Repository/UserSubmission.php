<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Judge\Document\ActiveProblem;
use Judge\Document\User as UserDocument;

class UserSubmission extends DocumentRepository
{
    public function findForUserAndProblem(ActiveProblem $problem, UserDocument $user)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('p')->equals(new \MongoId($problem->getId()))
            ->field('u')->equals(new \MongoId($user->getId()));
        return $qb->getQuery()->getSingleResult();
    }
}
