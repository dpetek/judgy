<?php

namespace Api\Exception\Core;

use Api\Exception\ApiException;
use Zend\Http\Response;

class NoPermissionException extends  ApiException
{
    public function __construct(array $meta = array())
    {
        parent::__construct(
            "You don't have permission to do that.",
            Response::STATUS_CODE_403,
            $meta
        );
    }
}
