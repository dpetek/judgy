<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Judge\Document\MiscProblem as MiscProblemDocument;
use Judge\Document\User as UserDocument;

class MiscUserSubmission extends DocumentRepository
{
    public function findForUserAndProblem(MiscProblemDocument $problem, UserDocument $user)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('p')->equals(new \MongoId($problem->getId()))
            ->field('u')->equals(new \MongoId($user->getId()));
        return $qb->getQuery()->getSingleResult();
    }
}
