<?php
/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Api;

/**
 * Class ErrorDto
 * 
 * Wrapper for an error returned by the ShipEngine API
 *
 * @package ShipEngine\Api
 */
class ErrorDto
{
    protected $errorCode;
    protected $errorMessage;

    public function __construct(array $error)
    {
        $this->errorCode = $error['error_code'];
        $this->errorMessage = $error['message'];
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function __toString()
    {
        /** @var string $message */
        $message = sprintf("[Error Code: %s] %s", $this->errorCode ?: "N/A", $this->errorMessage);
        return $message;
    }
}