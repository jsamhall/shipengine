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

use jsamhall\ShipEngine\Exception;

class Request
{
    /**
     * The cURL handle
     */
    protected \CurlHandle $curlHandle;

    public function __construct(array $params)
    {
        $curlHandle = curl_init();
        curl_setopt_array($curlHandle, $params);

        $this->curlHandle = $curlHandle;
    }

    /**
     * Perform the request
     *
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function send(): Response
    {
        $response = curl_exec($this->curlHandle);
        if ($response === false) {
            throw Exception::apiRequestFailed(curl_error($this->curlHandle));
        }

        $data = json_decode($response, true);

        if (!empty($data['errors'])) {
            throw Exception::apiErrorResponse($data);
        }

        return new Response($data);
    }
}
