<?php

namespace Api\Exception;

abstract class ApiException extends \Exception
{
    const DEFAULT_RESPONSE_CODE = 400;

    protected $responseCode;

    protected $additional = array();

    public function __construct($message, $responseCode = self::DEFAULT_RESPONSE_CODE, array $additional = array())
    {
        parent::__construct($message);
        $this->setResponseCode($responseCode);
        $this->setAdditional($additional);
    }

    /**
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param array $additional
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }

    /**
     * @return array
     */
    public function getAdditional()
    {
        return $this->additional;
    }
}
