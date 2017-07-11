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
     * Response constructor.
     *
     * @param array $responseData
     */
    public function __construct(array $responseData)
    {
        $this->rawData = $responseData;
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
}