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

namespace jsamhall\ShipEngine;

/**
 * Generic package exception
 *
 * @package ShipEngine
 */
class Exception extends \Exception
{
    /**
     * The response from the ShipEngine API contained one or more errors.
     */
    public static function apiErrorResponse(array $errorResponse): Exception\ApiErrorResponse
    {
        return new Exception\ApiErrorResponse($errorResponse);
    }

    /**
     * A request to the ShipEngine API failed.
     */
    public static function apiRequestFailed(string $message = "No response was received."): Exception\ApiRequestFailed
    {
        return new Exception\ApiRequestFailed($message);
    }
}
