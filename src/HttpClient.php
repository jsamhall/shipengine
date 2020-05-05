<?php
declare(strict_types = 1);

namespace jsamhall\ShipEngine;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Class HttpClient
 * @package jsamhall\ShipEngine
 */
class HttpClient extends Client
{
    /** @var int */
    protected $lastStatus;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    # region Functions

    public function postJson(string $route, array $payload, string $key = null)
    {
        return $this->tryCatchRequest('POST', $route, $payload, $key);
    }

    public function getJson(string $route, array $params = [], string $key = null)
    {
        return $this->tryCatchRequest('GET', $route, $params, $key);
    }

    public function putJson(string $route, array $payload = [], string $key = null)
    {
        return $this->tryCatchRequest('PUT', $route, $payload, $key);
    }

    public function deleteJson(string $route, array $params = []): bool
    {
        $this->tryCatchRequest('DELETE', $route, $params);
        return $this->isSuccessful();
    }

    public function isSuccessful(): bool
    {
        $successful = [200, 201, 202, 203, 204];

        return in_array($this->lastStatus, $successful);
    }

    private function getResponseData(array $data, $key = null)
    {
        return $key === null ? $data : $data[$key];
    }

    # endregion

    # Private Functions

    private function tryCatchRequest(string $method, string $route, array $payload = [], string $key = null)
    {
        try {
            switch ($method) {
                case 'POST':
                    $response = $this->post($route, ['json' => $payload]);
                    break;

                case 'GET':
                    $response = $this->get($route, ['params' => $payload]);
                    break;

                case 'PUT':
                    $response = $this->put($route, ['json' => $payload]);
                    break;
            }
        } catch (BadResponseException $exception) {
            if ($exception->hasResponse()) {
                $response = (string) $exception->getResponse()->getBody();
            }
            throw Exception::apiRequestFailed($response ?? null);
        }

        // If we don't have a response. Run a generic Guzzle call using above parameters.
        if (empty($response)) {
            $response = $this->request($method, $route, $payload);
        }

        $this->lastStatus = $response->getStatusCode();

        // This is for backward compatibility with old SDK. We are going to grab the response.
        // Decode it and attempt to look for errors.
        $data = json_decode((string) $response->getBody(), true);
        if (isset($data['errors']) && !empty($data['errors'])) {
            throw Exception::apiErrorResponse($data);
        }

        return $this->getResponseData($data, $key);
    }

    # endregion
}
