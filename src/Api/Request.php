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

use jsamhall\ShipEngine\Exception;

class Request
{
    /**
     * The cURL resource
     *
     * @var resource
     */
    protected $curlHandle;

    /**
     * Request constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $curlHandle = curl_init($params[CURLOPT_URL]);
        curl_setopt_array($curlHandle, $params);

        $this->curlHandle = $curlHandle;
    }

    /**
     * Perform the request
     *
     * @return Response
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function send()
    {
        $response = curl_exec($this->curlHandle);
        if ($response === false) {
            $message = curl_error($this->curlHandle) ?? null;
            throw Exception::apiRequestFailed($message);
        }

        $data = json_decode($response, true);

        if (isset($data['errors']) && !empty($data['errors'])) {
            throw Exception::apiErrorResponse($data);
        }

        $statusCode = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        return new Response($data ?? [], $statusCode);
    }
}
