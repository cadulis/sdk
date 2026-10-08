<?php

namespace Cadulis\Sdk\Client;

class Exception extends \Cadulis\Sdk\Exception
{

    protected ?string $_errorCode = null;

    public function setErrorCode(string $errorCode) : static
    {
        $this->_errorCode = $errorCode;

        return $this;
    }

    /**
     * @return string|null the `error_code` of the API's error body (e.g. `insufficient_credits`), null without one
     */
    public function getErrorCode() : ?string
    {
        return $this->_errorCode;
    }
}
