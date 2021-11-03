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
    protected $data;

    /**
     * Response constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
            ? $this->data
            : $this->data[$key];
    }
}