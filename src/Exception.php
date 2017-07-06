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
 * @package ShipEngineApi
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
     * The data returned by the FormatterInterface was invalid
     *
     * @param array $missingKeys
     * @throws Exception\InvalidAddressData
     * @return Exception\InvalidAddressData
     */
    public static function invalidAddressData(array $missingKeys)
    {
        throw new Exception\InvalidAddressData($missingKeys);
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