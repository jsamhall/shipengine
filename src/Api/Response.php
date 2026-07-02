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
 * Class Response
 *
 * Wrapper for a Response from the ShipEngine API
 *
 * @package jsamhall\ShipEngine\Api
 */
class Response
{
    public function __construct(protected array $data)
    {
    }

    /**
     * Get responseData from response
     *
     * @return array|mixed
     */
    public function getData(?string $key = null): mixed
    {
        return $key === null
            ? $this->data
            : $this->data[$key];
    }
}
