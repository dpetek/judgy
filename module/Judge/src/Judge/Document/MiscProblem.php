<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;
use Core\Document\Base;

/**
 * @ODM\Document(db="judge",collection="MiscProblems",slaveOkay=false, repositoryClass="Judge\Repository\MiscProblem")
 */
class MiscProblem extends BaseMiscProblem
{

}
