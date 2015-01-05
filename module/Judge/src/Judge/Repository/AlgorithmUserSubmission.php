<?php

namespace Judge\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Judge\Document\ActiveProblem as ActiveProblemDocument;
use Judge\Document\User as UserDocument;

class AlgorithmUserSubmission extends DocumentRepository
{
    public function findForProblemAndUser(ActiveProblemDocument $problem, UserDocument $user, $offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('pid')->equals(new \MongoId($problem->getId()));
        $qb->field('uid')->equals(new \MongoId($user->getId()));
        $qb->sort('tc', -1)->limit($limit)->skip($offset);
        return array_values($qb->getQuery()->toArray());
    }
}