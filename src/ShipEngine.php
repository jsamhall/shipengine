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
namespace jsamhall\ShipEngine;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use jsamhall\ShipEngine\Carriers\CarrierId;
use jsamhall\ShipEngine\Carriers\CarrierCode;
use jsamhall\ShipEngine\Carriers\FedEx\FedEx;
use jsamhall\ShipEngine\Carriers\UPS\Settings as UpsSettings;
use jsamhall\ShipEngine\Carriers\UPS\UPS;
use jsamhall\ShipEngine\Carriers\USPS\StampsDotCom;
use jsamhall\ShipEngine\Labels\LabelId;
use jsamhall\ShipEngine\Rating\Rate;
use jsamhall\ShipEngine\Rating\RateId;
use jsamhall\ShipEngine\Address\Factory;
use jsamhall\ShipEngine\Address\Address;
use jsamhall\ShipEngine\Address\FormatterInterface;

/**
 * Class ShipEngine
 *
 * @package ShipEngine
 */
class ShipEngine
{
    /**
     * @var Factory
     */
    protected $addressFactory;

    /**
     * @var Client
     */
    protected $client;

    /**
     * ShipEngine constructor.
     *
     * @param string                     $apiKey
     * @param FormatterInterface $addressFormatter
     * @param array                      $options
     */
    public function __construct(string $apiKey, FormatterInterface $addressFormatter, array $options = [])
    {
        $defaultOptions = [
            'base_uri' => 'https://api.shipengine.com/v1/',
            'headers' => [
                'Content-Type' => 'application/json',
                'API-Key'      => $apiKey,
            ],
        ];

        $options = $options + $defaultOptions;
        $this->addressFactory = new Factory($addressFormatter);
        $this->client = new HttpClient($options);
    }

    /**
     * Format an address using the Factory's Formatter
     *
     * @param mixed $address Domain address as expected by the Address\Formatter implementation
     * @return Address
     */
    public function formatAddress($address)
    {
        return $this->addressFactory->factory($address);
    }

    /**
     * Validates the given Addresses against ShipEngine's Address Validator
     *
     * @link https://docs.shipengine.com/docs/address-validation
     *
     * @param array $addresses Array of Address\Address or domain Addresses which are passed through the Formatter
     *
     * @return AddressVerification\VerificationResult[] Array of Verification Results for every address requested
     * @throws RequestException
     * @throws ClientException
     */
    public function validateAddresses(array $addresses)
    {
        $addressData = $this->addressFactory->getAddressData($addresses);
        $response = $this->client->postJson('addresses/validate', $addressData);

        return array_map(function ($data) {
            return new AddressVerification\VerificationResult($data);
        }, $response);
    }

    /**
     * @param Address $address
     * @return AddressVerification\VerificationResult
     * @throws RequestException
     * @throws ClientException
     */
    public function validateAddress(Address $address)
    {
        $addressData = $address->toArray();
        $response = $this->client->postJson('addresses/validate', $addressData, 0);

        return new AddressVerification\VerificationResult($response);
    }

    /**
     * Lists all Carriers setup in the ShipEngine account
     *
     * @link https://docs.shipengine.com/docs/list-your-carriers
     *
     * @return Carriers\Carrier[]
     * @throws RequestException
     * @throws ClientException
     */
    public function listCarriers()
    {
        $response = $this->client->getJson('carriers', [], 'carriers');

        return array_map(function ($carrier) {
            return new Carriers\Carrier($carrier);
        }, $response);
    }

    /**
     * Get a single Carrier
     *
     * @param string $carrierId
     * @return Carriers\Carrier
     * @throws RequestException
     * @throws ClientException
     */
    public function getCarrier(string $carrierId)
    {
        $endpoint = sprintf('carriers/%s', $carrierId);
        $response = $this->client->getJson($endpoint);

        return new Carriers\Carrier($response);
    }

    /**
     * @param CarrierCode $carrierCode
     * @param string $carrierId
     * @return bool
     * @throws RequestException
     * @throws ClientException
     */
    public function removeCarrier(CarrierCode $carrierCode, string $carrierId): bool
    {
        $endpoint = sprintf('connections/carriers/%1$s/%2$s', $carrierCode, $carrierId);
        return $this->client->deleteJson($endpoint);
    }

    /**
     * List all Services offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\Service[]
     * @throws RequestException
     * @throws ClientException
     */
    public function listCarrierServices(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/services", $carrierId);
        $response = $this->client->getJson($endpoint, [], 'services');

        return array_map(function ($carrier) {
            return new Carriers\Service($carrier);
        }, $response);
    }

    /**
     * List all Package Types offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\PackageType[]
     * @throws RequestException
     * @throws ClientException
     */
    public function listCarrierPackageTypes(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/packages", $carrierId);
        $response = $this->client->getJson($endpoint, [], 'packages');

        return array_map(function ($carrier) {
            return new Carriers\PackageType($carrier);
        }, $response);
    }

    /**
     * Get all Options offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\AvailableOption[]
     * @throws RequestException
     * @throws ClientException
     */
    public function getCarrierOptions(string $carrierId)
    {
        $endpoint = sprintf('carriers/%s/options', $carrierId);
        $response = $this->client->getJson($endpoint, [], 'options');

        return array_map(function ($carrier) {
            return new Carriers\AvailableOption($carrier);
        }, $response);
    }

    /**
     * Get all Rates for the given Shipment and RateOptions
     *
     * @param Rating\Shipment $shipment
     * @param Rating\Options $rateOptions
     * @return Api\Response|Rating\RateResponse
     * @throws RequestException
     * @throws ClientException
     */
    public function getRates(Rating\Shipment $shipment, Rating\Options $rateOptions)
    {
        if (! count($rateOptions)) {
            throw new \InvalidArgumentException("\$rateOptions cannot be empty");
        }

        $response = $this->client->postJson('rates', [
            'shipment'     => $shipment->toArray(),
            'rate_options' => $rateOptions->toArray(),
        ], 'rate_response');

        return new Rating\RateResponse($response);
    }

    /**
     * Gets a specific Rate from known rateId.
     *
     * @param RateId $rateId
     * @return Rate
     * @throws RequestException
     * @throws ClientException
     */
    public function getRate(RateId $rateId): Rate
    {
        $endpoint = 'rates/' . $rateId;
        $response = $this->client->getJson($endpoint);

        return new Rating\Rate($response);
    }

    /**
     * Create a Shipping Label
     *
     * @param Labels\Shipment $shipment
     * @param bool $testMode
     * @return Labels\Response
     * @throws RequestException
     * @throws ClientException
     */
    public function createLabel(Labels\Shipment $shipment, $testMode = false)
    {
        $response = $this->client->postJson('labels', [
            'shipment'   => $shipment->toArray(),
            'test_label' => $testMode
        ]);

        return new Labels\Response($response);
    }

    /**
     * Create a Shipping Label via Rate
     *
     * @param Labels\RateLabel $rateLabel
     * @param bool $testMode
     * @return Labels\Response
     * @throws RequestException
     * @throws ClientException
     */
    public function createLabelFromRate(Labels\RateLabel $rateLabel, $testMode = false)
    {
        $url = 'labels/rates/' . (string) $rateLabel->getRateId();
        $response = $this->client->postJson($url, [
            'validate_address'    => $rateLabel->getAddressValidation(),
            'label_layout'        => $rateLabel->getLabelLayout(),
            'label_format'        => $rateLabel->getLabelFormat(),
            'label_download_type' => $rateLabel->getLabelDownloadFormat(),
            'test_label'          => $testMode,
        ]);

        return new Labels\Response($response);
    }

    /**
     * @param LabelId $label
     * @return Labels\VoidResponse
     * @throws RequestException
     * @throws ClientException
     */
    public function voidLabel(LabelId $label)
    {
        $url = 'labels/' . (string) $label . '/void';
        $response = $this->client->putJson($url);

        return new Labels\VoidResponse($response);
    }

    /**
     * Connect a Stamps.com account.
     *
     * @param StampsDotCom $stampsDotCom
     * @return CarrierId
     * @throws RequestException
     * @throws ClientException
     */
    public function connectStampsDotCom(StampsDotCom $stampsDotCom)
    {
        $response = $this->client->postJson('connections/carriers/stamps_com', $stampsDotCom->toArray(), 'carrier_id');

        return new CarrierId($response);
    }

    /**
     * Connect a ups.com account.
     *
     * @param UPS $UPS
     * @return CarrierId
     * @throws RequestException
     * @throws ClientException
     */
    public function connectUps(UPS $UPS): CarrierId
    {
        $response = $this->client->postJson('connections/carriers/ups', $UPS->toArray(), 'carrier_id');

        return new CarrierId($response);
    }

    /**
     * @param CarrierId $carrierId
     * @param UpsSettings $settings
     * @return array
     * @throws RequestException
     * @throws ClientException
     */
    public function adjustUps(CarrierId $carrierId, UpsSettings $settings)
    {
        $response = $this->client->putJson('connections/carriers/ups/' . $carrierId . '/settings', $settings->toArray());

        // 4/29/2020 TODO: fix response for UPS adjustments
        return $response;
    }

    /**
     * Connect a fedex.com account.
     *
     * @param FedEx $fedEx
     * @return CarrierId
     * @throws RequestException
     * @throws ClientException
     */
    public function connectFedEx(FedEx $fedEx): CarrierId
    {
        $response = $this->client->postJson('connections/carriers/fedex', $fedEx->toArray(), 'carrier_id');

        return new CarrierId($response);
    }
}
