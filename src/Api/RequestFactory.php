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

use jsamhall\ShipEngine;

class RequestFactory
{
    /**
     * The base URL for the ShipEngine API
     */
    private const API_URL = "https://api.shipengine.com/v1/";

    public function __construct(protected readonly string $apiKey)
    {
    }

    /**
     * Build a Request to the validateAddress API endpoint
     *
     * @param array $addresses Array of addresses to validate
     */
    public function validateAddresses(array $addresses): Request
    {
        $url = $this->buildUrl("addresses/validate");

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode($addresses)
        ]);
    }

    /**
     * Build a Request to the listCarriers API endpoint
     */
    public function listCarriers(): Request
    {
        $url = $this->buildUrl("carriers");

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the getCarrier API endpoint
     *
     * @param string $carrierId The CarrierId as found in the ShipEngine Account
     */
    public function getCarrier(string $carrierId): Request
    {
        $endpoint = sprintf("carriers/%s", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Carriers "ListServices" API endpoint
     */
    public function listCarrierServices(string $carrierId): Request
    {
        $endpoint = sprintf("carriers/%s/services", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Carriers "ListPackages" API endpoint
     */
    public function listCarrierPackageTypes(string $carrierId): Request
    {
        $endpoint = sprintf("carriers/%s/packages", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Carriers "GetOptions" API endpoint
     */
    public function getCarrierOptions(string $carrierId): Request
    {
        $endpoint = sprintf("carriers/%s/options", $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Rating "RateShipment" API endpoint
     */
    public function getShipmentRates(ShipEngine\Rating\Shipment $shipment, ShipEngine\Rating\Options $options): Request
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
     */
    public function createLabel(ShipEngine\Labels\Shipment $shipment, bool $testMode = false): Request
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
     * @param string $labelImageId The Label image ID retrieved from the ShipEngine back-office
     */
    public function createLabelWithCustomImage(
        ShipEngine\Labels\Shipment $shipment,
        string $labelImageId,
        bool $testMode = false
    ): Request {
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

    public function createLabelFromRate(ShipEngine\Rating\Rate $rate): Request
    {
        $endpoint = sprintf('labels/rates/%s', $rate->getId());
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_POST => true
        ]);
    }

    public function createLabelFromRateWithCustomImage(ShipEngine\Rating\Rate $rate, string $labelImageId): Request
    {
        $endpoint = sprintf('labels/rates/%s', $rate->getId());
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode([
                'label_image_id' => $labelImageId
            ])
        ]);
    }

    public function voidLabel(string $labelId): Request
    {
        $endpoint = sprintf("labels/%s/void", $labelId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_CUSTOMREQUEST => 'PUT'
        ]);
    }

    /**
     * Build a Request to the Tracking "GetTrackingInformation" endpoint
     *
     * @param string $carrierCode   e.g. "usps", "fedex", "ups"
     */
    public function getTrackingInformation(string $carrierCode, string $trackingNumber): Request
    {
        $query = http_build_query([
            'carrier_code'    => $carrierCode,
            'tracking_number' => $trackingNumber,
        ]);

        $url = $this->buildUrl('tracking') . '?' . $query;

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true,
        ]);
    }

    /**
     * Initializes a cURL handle for a request to the ShipEngine API
     *
     * @param string $url Request URL or endpoint (e.g., /addresses/validate)
     * @param array $params Request Parameters
     */
    private function initRequest(string $url, array $params = []): Request
    {
        $httpHeader = [
            "Content-Type: application/json",
            "api-key: " . $this->apiKey
        ];

        $params = $params + [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
            ];

        if (($params[CURLOPT_POST] ?? false) === true) {
            $contentLength = strlen($params[CURLOPT_POSTFIELDS] ?? '');
            $httpHeader[] = sprintf('Content-Length: %d', $contentLength);
        }

        $params[CURLOPT_HTTPHEADER] = $httpHeader;

        return new Request($params);
    }

    /**
     * Builds a URL to the provided ShipEngine API endpoint
     */
    private function buildUrl(string $endpoint): string
    {
        return self::API_URL . $endpoint;
    }
}
