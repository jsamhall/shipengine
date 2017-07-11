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
namespace jsamhall\ShipEngine\Exception;

use jsamhall\ShipEngine\Api\Error;
use jsamhall\ShipEngine\Exception;

class ApiErrorResponse
    extends Exception
{
    /**
     * The internal id of the Request
     *
     * @var string
     */
    protected $requestId;

    /**
     * @var Error[]
     */
    protected $errors = [];

    /**
     * ShipEngineApiException constructor.
     *
     * @param array $errorResponse The ShipEngine API error response
     */
    public function __construct(array $errorResponse)
    {
        $this->requestId = $errorResponse['request_id'];
        $this->setErrors($errorResponse['errors']);


        $message = sprintf("ShipEngine Request ID %s response contained %d error(s).", $this->requestId, count($this->errors));
        return parent::__construct($message);
    }

    private function setErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->errors[] = new Error($error);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}