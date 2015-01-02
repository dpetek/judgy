<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="judge",collection="Problems",slaveOkay=false, repositoryClass="Judge\Repository\BaseProblem")
 */
class ActiveProblem extends BaseProblem
{

}
