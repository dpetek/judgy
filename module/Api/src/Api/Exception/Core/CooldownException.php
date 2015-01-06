<?php

namespace Api\Exception\Core;

use Api\Exception\ApiException;
use Zend\Http\Response;

class CooldownException extends ApiException
{
    public function __construct($secondsLeft, array $meta = array())
    {
        parent::__construct(
            "You still can't resubmit solution for this problem. You will be able to do it in " . (string)$secondsLeft . ' sec.',
            Response::STATUS_CODE_403,
            $meta
        );
    }
}
