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
 * Class UrlBuilder
 *
 * Builds URLs to ShipEngine's public API endpoints
 *
 * @package ShipEngine\Api
 */
class UrlBuilder
{
    /**
     * The API key to use when communicating with ShipEngine
     *
     * @var string
     */
    private $apiKey;

    /**
     * The base URL for the ShipEngine API
     *
     * @var string
     */
    private $apiUrl = "https://api.shipengine.com/v1/";

    /**
     * ShipEngineApiRouteBuilder constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    /**
     * @return string
     */
    public function validateAddress()
    {
        return $this->buildUrl("addresses/validate");
    }
    
    /**
     * @return string
     */
    public function listCarriers()
    {
        return $this->buildUrl("carriers");
    }

    /**
     * @param string $carrierId
     * @return string
     */
    public function getCarrier(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s", $carrierId);
        return $this->buildUrl($endpoint);
    }

    /**
     * @param string $carrierId
     * @return string
     */
    public function getCarrierServices(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/services", $carrierId);
        return $this->buildUrl($endpoint);
    }

    /**
     * @param string $carrierId
     * @return string
     */
    public function getCarrierPackageTypes(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/packages", $carrierId);
        return $this->buildUrl($endpoint);
    }

    /**
     * @param string $carrierId
     * @return string
     */
    public function getCarrierOptions(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/options", $carrierId);
        return $this->buildUrl($endpoint);
    }

    /**
     * Builds a URL to the provided ShipEngine API endpoint
     *
     * @param string $endpoint
     * @return string The URL
     */
    private function buildUrl(string $endpoint)
    {
        return $this->apiUrl . $endpoint;
    }
}