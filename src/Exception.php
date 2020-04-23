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

namespace jsamhall\ShipEngine;

/**
 * Generic package exception
 *
 * @package ShipEngine
 */
class Exception extends \Exception
{
    /**
     * The response from the ShipEngine API contained one or more errors
     *
     * @param array $errorResponse
     * @throws Exception\ApiErrorResponse
     * @return Exception\ApiErrorResponse
     */
    public static function apiErrorResponse(array $errorResponse)
    {
        throw new Exception\ApiErrorResponse($errorResponse);
    }
    
    /**
     * A request to the ShipEngine API failed
     *
     * @param string $message
     * @throws Exception\ApiRequestFailed
     * @return Exception\ApiRequestFailed
     */
    public static function apiRequestFailed($message = "No response was received.")
    {
        throw new Exception\ApiRequestFailed($message);
    }
}
