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
 * Class Error
 * 
 * Wrapper for an error returned by the ShipEngine API
 *
 * @package ShipEngine\Api
 */
class Error
{
    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var string
     */
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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            $this->getErrorCode() => $this->getErrorMessage()
        ];
    }

    public function __toString()
    {
        /** @var string $message */
        $message = sprintf("[Error Code: %s] %s", $this->errorCode ?: "N/A", $this->errorMessage);
        return $message;
    }
}