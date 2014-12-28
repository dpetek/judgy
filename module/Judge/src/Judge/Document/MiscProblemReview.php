<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="judge",collection="MiscProblemsReview",slaveOkay=false, repositoryClass="Judge\Repository\MiscProblem")
 */
class MiscProblemReview extends BaseMiscProblem
{

}
