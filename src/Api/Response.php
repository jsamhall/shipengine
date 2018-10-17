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
 * Class Response
 * 
 * Wrapper for a Response from the ShipEngine API
 *
 * @package jsamhall\ShipEngine\Api
 */
class Response
{
    /**
     * @var array
     */
    protected $rawData;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param int $statusCode
     */
    public function __construct(array $responseData, int $statusCode)
    {
        $this->rawData = $responseData;
        $this->statusCode = $statusCode;
    }

    /**
     * Get responseData from response
     *
     * @param null $key
     * @return array|mixed
     */
    public function getData($key = null)
    {
        return $key == null
            ? $this->rawData
            : $this->rawData[$key];
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        $successful = [200, 201, 202, 203, 204];

        return in_array($this->getStatusCode(), $successful);
    }
}