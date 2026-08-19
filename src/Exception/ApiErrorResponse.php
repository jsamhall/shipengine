<?php

declare(strict_types=1);

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

class ApiErrorResponse extends Exception
{
    /**
     * The internal id of the Request
     */
    protected string $requestId;

    /**
     * @var Error[]
     */
    protected array $errors = [];

    /**
     * @param array $errorResponse The ShipEngine API error response
     */
    public function __construct(array $errorResponse)
    {
        $this->requestId = $errorResponse['request_id'];
        $this->setErrors($errorResponse['errors']);

        $message = sprintf(
            "ShipEngine Request ID %s response contained %d error(s).",
            $this->requestId,
            count($this->errors)
        );
        parent::__construct($message);
    }

    private function setErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->errors[] = new Error($error);
        }
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
