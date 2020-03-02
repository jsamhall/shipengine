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
     * Builds a request to a delete a carrier. Requires a CarrierType to identify which carrier to delete.
     *
     * @link https://docs.shipengine.com/reference#carrier-accounts
     *
     * @param ShipEngine\Carriers\CarrierCode $carrierCode
     * @param string $carrierId
     * @return Request
     */
    public function deleteCarrier(ShipEngine\Carriers\CarrierCode $carrierCode, string $carrierId)
    {
        $endpoint = sprintf('connections/carriers/%1$s/%2$s', $carrierCode, $carrierId);
        $url = $this->buildUrl($endpoint);

        return $this->initRequest($url, [
            CURLOPT_CUSTOMREQUEST => 'DELETE'
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
     * @param ShipEngine\Rating\Options  $options
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
     * Build a Request to the Rating "RateShipment" API endpoint. Return a rate.
     *
     * @param ShipEngine\Rating\RateId $rateId
     * @return Request
     */
    public function getShipmentRate(ShipEngine\Rating\RateId $rateId)
    {
        $url = $this->buildUrl('rates/' . $rateId);

        return $this->initRequest($url, [
            CURLOPT_HTTPGET => true
        ]);
    }

    /**
     * Build a Request to the Labels "PurchaseLabel" Endpoint
     *
     * @link https://shipengine-docs.readme.io/reference#Labels_PurchaseLabel
     *
     * @param ShipEngine\Labels\Shipment $shipment
     * @param bool                       $testMode
     * @return Request
     */
    public function createLabel(
        ShipEngine\Labels\Shipment $shipment,
        $testMode = false
    ) {
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
     * Build a Request to the "Labels via Rate" Endpoint
     *
     * @link https://docs.shipengine.com/docs/use-a-rate-to-print-a-label
     *
     * @param ShipEngine\Labels\RateLabel $rateLabel
     * @param bool $testMode
     * @return Request
     */
    public function createLabelFromRate(ShipEngine\Labels\RateLabel $rateLabel, $testMode = false)
    {
        $url = $this->buildUrl('labels/rates/' . (string) $rateLabel->getRateId());

        return $this->initRequest($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => json_encode([
                'validate_address'    => $rateLabel->getAddressValidation(),
                'label_layout'        => $rateLabel->getLabelLayout(),
                'label_format'        => $rateLabel->getLabelFormat(),
                'label_download_type' => $rateLabel->getLabelDownloadFormat(),
                'test_label'          => $testMode,
            ]),
        ]);
    }

    /**
     * Build a Request to "Void Label" Endpoint.
     *
     * @link https://docs.shipengine.com/docs/void-a-label
     *
     * @param ShipEngine\Labels\LabelId $labelId
     * @return Request
     */
    public function voidLabel(ShipEngine\Labels\LabelId $labelId)
    {
        $url = $this->buildUrl('labels/' . (string) $labelId . '/void');

        return $this->initRequest($url, [
            CURLOPT_PUT => true
        ]);
    }

    /**
     * Connects a "stamps.com" account via the "Carriers" Endpoint.
     *
     * @link https://docs.shipengine.com/docs/connect-stampscom
     *
     * @param ShipEngine\Carriers\USPS\StampsDotCom $stampsDotCom
     * @return Request
     */
    public function connectStampsDotCom(ShipEngine\Carriers\USPS\StampsDotCom $stampsDotCom)
    {
        $url = $this->buildUrl('connections/carriers/stamps_com');

        return $this->initRequest($url, [
            CURLOPT_POST        => true,
            CURLOPT_POSTFIELDS  => json_encode($stampsDotCom->toArray()),
        ]);
    }

    /**
     * Connects a "ups.com" account via the "Carriers" Endpoint.
     *
     * @link https://docs.shipengine.com/docs/connect-ups
     *
     * @param ShipEngine\Carriers\UPS\UPS $UPS
     * @return Request
     */
    public function connectUps(ShipEngine\Carriers\UPS\UPS $UPS)
    {
        $url = $this->buildUrl('connections/carriers/ups');

        return $this->initRequest($url, [
            CURLOPT_POST        => true,
            CURLOPT_POSTFIELDS  => json_encode($UPS->toArray())
        ]);
    }

    /**
     * @param ShipEngine\Carriers\CarrierId $carrierId
     * @param ShipEngine\Carriers\UPS\Settings $settings
     * @return Request
     */
    public function adjustUps(ShipEngine\Carriers\CarrierId $carrierId, ShipEngine\Carriers\UPS\Settings $settings)
    {
        $url = $this->buildUrl('connections/carriers/ups/' . $carrierId . '/settings');

        return $this->initRequest($url, [
            CURLOPT_PUT         => true,
            CURLOPT_POSTFIELDS  => json_encode($settings->toArray())
        ]);
    }

    /**
     * Connects a "fedex.com" account via the "Carriers" Endpoint.
     *
     * @link https://docs.shipengine.com/docs/connect-fedex
     *
     * @param ShipEngine\Carriers\FedEx\FedEx $fedEx
     * @return Request
     */
    public function connectFedEx(ShipEngine\Carriers\FedEx\FedEx $fedEx)
    {
        $url = $this->buildUrl('connections/carriers/fedex');

        return $this->initRequest($url, [
            CURLOPT_POST        => true,
            CURLOPT_POSTFIELDS  => json_encode($fedEx->toArray()),
        ]);
    }

    /**
     * Initializes a cURL handle for a request to the ShipEngine API
     *
     * @param string $url    Request URL or endpoint (e.g., /addresses/validate)
     * @param array  $params Request Parameters
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
