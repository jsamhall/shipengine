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

use jsamhall\ShipEngine\Carriers\CarrierId;
use jsamhall\ShipEngine\Carriers\CarrierType;
use jsamhall\ShipEngine\Carriers\FedEx\FedEx;
use jsamhall\ShipEngine\Carriers\UPS\Settings as UpsSettings;
use jsamhall\ShipEngine\Carriers\UPS\UPS;
use jsamhall\ShipEngine\Carriers\USPS\StampsDotCom;
use jsamhall\ShipEngine\Labels\LabelId;

/**
 * Class ShipEngine
 *
 * @package ShipEngine
 */
class ShipEngine
{
    /**
     * Request Factory
     *
     * @var Api\RequestFactory
     */
    protected $requestFactory;

    /**
     * @var Address\Factory
     */
    protected $addressFactory;

    /**
     * ShipEngine constructor.
     *
     * @param string                     $apiKey
     * @param Address\FormatterInterface $addressFormatter
     */
    public function __construct(string $apiKey, Address\FormatterInterface $addressFormatter)
    {
        $this->requestFactory = new Api\RequestFactory($apiKey);
        $this->addressFactory = new Address\Factory($addressFormatter);
    }

    /**
     * Format an address using the Factory's Formatter
     *
     * @param mixed $address Domain address as expected by the Address\Formatter implementation
     * @return Address\Address
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
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function validateAddresses(array $addresses)
    {
        $addressData = array_map(function ($address) {
            return $address instanceof Address\Address
                ? $address->toArray()
                : $this->addressFactory->factory($address)->toArray();
        }, $addresses);

        $response = $this->requestFactory->validateAddresses($addressData)->send();

        return array_map(function ($data) {
            return new AddressVerification\VerificationResult($data);
        }, $response->getData());
    }

    /**
     * Lists all Carriers setup in the ShipEngine account
     *
     * @link https://docs.shipengine.com/docs/list-your-carriers
     *
     * @return Carriers\Carrier[]
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function listCarriers()
    {
        $response = $this->requestFactory->listCarriers()->send();

        return array_map(function ($carrier) {
            return new Carriers\Carrier($carrier);
        }, $response->getData('carriers'));
    }

    /**
     * Get a single Carrier
     *
     * @param string $carrierId
     * @return Carriers\Carrier
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function getCarrier(string $carrierId)
    {
        $response = $this->requestFactory->getCarrier($carrierId)->send();

        return new Carriers\Carrier($response->getData());
    }

    /**
     * @param CarrierType $carrierType
     * @param string $carrierId
     * @return bool
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function removeCarrier(CarrierType $carrierType, string $carrierId): bool
    {
        $response = $this->requestFactory->deleteCarrier($carrierType, $carrierId)->send();

        return $response->isSuccessful();
    }

    /**
     * List all Services offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\Service[]
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function listCarrierServices(string $carrierId)
    {
        $response = $this->requestFactory->listCarrierServices($carrierId)->send();

        return array_map(function ($carrier) {
            return new Carriers\Service($carrier);
        }, $response->getData('services'));
    }

    /**
     * List all Package Types offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\PackageType[]
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function listCarrierPackageTypes(string $carrierId)
    {
        $response = $this->requestFactory->listCarrierPackageTypes($carrierId)->send();

        return array_map(function ($carrier) {
            return new Carriers\PackageType($carrier);
        }, $response->getData('packages'));
    }

    /**
     * Get all Options offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\AvailableOption[]
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function getCarrierOptions(string $carrierId)
    {
        $response = $this->requestFactory->getCarrierOptions($carrierId)->send();

        return array_map(function ($carrier) {
            return new Carriers\AvailableOption($carrier);
        }, $response->getData('options'));
    }

    /**
     * Get all Rates for the given Shipment and RateOptions
     *
     * @param Rating\Shipment $shipment
     * @param Rating\Options $rateOptions
     * @return Api\Response|Rating\RateResponse
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function getRates(Rating\Shipment $shipment, Rating\Options $rateOptions)
    {
        if (! count($rateOptions)) {
            throw new \InvalidArgumentException("\$rateOptions cannot be empty");
        }

        $response = $this->requestFactory->getShipmentRates($shipment, $rateOptions)->send();

        return new Rating\RateResponse($response->getData('rate_response'));
    }

    /**
     * Create a Shipping Label
     *
     * @param Labels\Shipment $shipment
     * @param bool $testMode
     * @return Labels\Response
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function createLabel(Labels\Shipment $shipment, $testMode = false)
    {
        $response = $this->requestFactory->createLabel($shipment, $testMode)->send();

        return new Labels\Response($response->getData());
    }

    /**
     * Create a Shipping Label via Rate
     *
     * @param Labels\RateLabel $rateLabel
     * @param bool $testMode
     * @return Labels\Response
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function createLabelFromRate(Labels\RateLabel $rateLabel, $testMode = false)
    {
        $response = $this->requestFactory->createLabelFromRate($rateLabel, $testMode)->send();

        return new Labels\Response($response->getData());
    }

    /**
     * @param LabelId $label
     * @return Labels\VoidResponse
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function voidLabel(LabelId $label)
    {
        $response = $this->requestFactory->voidLabel($label)->send();

        return new Labels\VoidResponse($response->getData());
    }

    /**
     * Connect a Stamps.com account.
     *
     * @param StampsDotCom $stampsDotCom
     * @return CarrierId
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function connectStampsDotCom(StampsDotCom $stampsDotCom)
    {
        $response = $this->requestFactory->connectStampsDotCom($stampsDotCom)->send();

        return new CarrierId($response->getData('carrier_id'));
    }

    /**
     * Connect a ups.com account.
     *
     * @param UPS $UPS
     * @return CarrierId
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function connectUps(UPS $UPS): CarrierId
    {
        $response = $this->requestFactory->connectUps($UPS)->send();

        return new CarrierId($response->getData('carrier_id'));
    }

    /**
     * @param CarrierId $carrierId
     * @param UpsSettings $settings
     * @return Api\Response
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function adjustUps(CarrierId $carrierId, UpsSettings $settings)
    {
        $response = $this->requestFactory->adjustUps($carrierId, $settings)->send();

        // todo - map response to whatever return is
        return $response;
    }

    /**
     * Connect a fedex.com account.
     *
     * @param FedEx $fedEx
     * @return CarrierId
     * @throws Exception\ApiErrorResponse
     * @throws Exception\ApiRequestFailed
     */
    public function connectFedEx(FedEx $fedEx): CarrierId
    {
        $response = $this->requestFactory->connectFedEx($fedEx)->send();

        return new CarrierId($response->getData('carrier_id'));
    }
}