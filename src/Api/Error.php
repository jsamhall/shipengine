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
    protected string $errorCode;

    protected string $errorMessage;

    public function __construct(array $error)
    {
        $this->errorCode = $error['error_code'] ?? '';
        $this->errorMessage = $error['message'] ?? '';
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function __toString(): string
    {
        return sprintf("[Error Code: %s] %s", $this->errorCode ?: "N/A", $this->errorMessage);
    }
}
