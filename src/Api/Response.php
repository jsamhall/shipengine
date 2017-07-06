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


class Response
{
    /**
     * @var array
     */
    protected $response;

    /**
     * Response constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Get data from response
     *
     * @param null $key
     * @return array|mixed
     */
    public function getData($key = null)
    {
        return $key == null
            ? $this->response
            : $this->response[$key];
    }
}