<?php

namespace Api\Exception\Core;

use Api\Exception\ApiException;
use Zend\Http\Response;

class MissingResource extends ApiException
{
    public function __construct(array $meta = array())
    {
        parent::__construct(
            "Resource doesn't exist.",
            Response::STATUS_CODE_404,
            $meta
        );
    }
}
