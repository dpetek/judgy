<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="judge",collection="ProblemReviews",slaveOkay=false, repositoryClass="Judge\Repository\BaseProblem")
 */
class ReviewProblem extends BaseProblem
{

}
