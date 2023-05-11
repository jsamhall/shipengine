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

use jsamhall\ShipEngine;

class RequestFactory
{
    /**
     * The base URL for the ShipEngine API
     *
     * @var string
     */
    private $apiUrl = "https://api.shipengine.com/v1/";

    /**
     * ShipEngine API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * RequestFactory constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Build a Request to the validateAddress API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#AddressValidation_ValidateAddresses
     *
     * @param array $addresses Array of addresses to validate
     * @return Request
     */
    public function validateAddresses(array $addresses)
    {
        $url = $this->buildUrl("addresses/validate");

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode($addresses)
        ]);
    }

    /**
     * Build a Request to the listCarriers API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Carriers_List
     *
     * @return Request
     */
    public function listCarriers()
    {
        $url = $this->buildUrl("carriers");

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the getCarrier API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Carriers_Get
     *
     * @param string $carrierId The CarrierId as found in the ShipEngine Account
     * @return Request
     */
    public function getCarrier(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Carriers "ListServices" API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Carriers_ListServices
     *
     * @param string $carrierId
     * @return Request
     */
    public function listCarrierServices(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/services", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Carriers "ListPackages" API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Carriers_ListPackages
     *
     * @param string $carrierId
     * @return Request
     */
    public function listCarrierPackageTypes(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/packages", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Carriers "GetOptions" API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Carriers_GetOptions
     *
     * @param string $carrierId
     * @return Request
     */
    public function getCarrierOptions(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/options", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }


    /**
     * Build a Request to the Rating "RateShipment" API endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Rates_RateShipment
     *
     * @param ShipEngine\Rating\Shipment $shipment
     * @param ShipEngine\Rating\Options $options
     * @return Request
     */
    public function getShipmentRates(ShipEngine\Rating\Shipment $shipment, ShipEngine\Rating\Options $options)
    {
        $url = $this->buildUrl('rates');

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode([
                'shipment'     => $shipment->toArray(),
                'rate_options' => $options->toArray()
            ])
        ]);
    }

    /**
     * Build a Request to the Labels "PurchaseLabel" Endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Labels_PurchaseLabel
     *
     * @param ShipEngine\Labels\Shipment $shipment
     * @param bool $testMode
     * @return Request
     */
    public function createLabel(ShipEngine\Labels\Shipment $shipment, $testMode = false)
    {
        $url = $this->buildUrl('labels');

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode([
                'shipment'   => $shipment->toArray(),
                'test_label' => $testMode
            ])
        ]);
    }


    /**
     * Build a Request to the Labels "PurchaseLabel" Endpoint using a Custom Image uploaded via ShipEngine back-office
     *
     * @link https://shipengine-docs.readme.io/reference#Labels_PurchaseLabel
     *
     * @param ShipEngine\Labels\Shipment $shipment
     * @param string $labelImageId The Label image ID retrieved from the ShipEngine back-office
     * @param bool $testMode
     * @return Request
     */
    public function createLabelWithCustomImage(
        ShipEngine\Labels\Shipment $shipment,
        string                     $labelImageId,
                                   $testMode = false
    ) {
        $url = $this->buildUrl('labels');

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode([
                'label_image_id' => $labelImageId,
                'shipment'       => $shipment->toArray(),
                'test_label'     => $testMode
            ])
        ]);
    }

    public function createLabelFromRate(ShipEngine\Rating\Rate $rate)
    {
        $endpoint = sprintf('labels/rates/%s', $rate->getId());
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_POST => true
        ]);
    }

    public function createLabelFromRateWithCustomImage(
        ShipEngine\Rating\Rate $rate,
        string                 $labelImageId

    ) {
        $endpoint = sprintf('labels/rates/%s', $rate->getId());
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'label_image_id' => $labelImageId
            ])
        ]);
    }

    public function voidLabel(string $labelId)
    {
        $endpoint = sprintf("labels/%s/void", $labelId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_PUT => true
        ]);
    }

    /**
     * Initializes a cURL handle for a request to the ShipEngine API
     *
     * @param string $url Request URL or endpoint (e.g., /addresses/validate)
     * @param array $params Request Parameters
     * @return Request
     */
    private function initRequest(string $url, array $params = [])
    {
        $params = $params + [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    "Content-Type: application/json",
                    "api-key: " . $this->apiKey
                ]
            ];

        return new Request($params);
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