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

/**
 * Class ShipEngine
 *
 * @package ShipEngineApi
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
     * @param string          $apiKey
     * @param Address\Factory $addressFactory
     */
    public function __construct(string $apiKey, Address\Factory $addressFactory)
    {
        $this->requestFactory = new Api\RequestFactory($apiKey);
        $this->addressFactory = $addressFactory;
    }

    /**
     * Validates the given Addresses against ShipEngine's Address Validator
     *
     * @link https://docs.shipengine.com/docs/address-validation
     *
     * @param array $addresses Array of Address\AddressDto or domain Addresses to be passed through the Address\Factory;
     *
     * @return AddressVerification\VerificationResultDto[] Array of Verification Results for every address requested
     */
    public function validateAddresses(array $addresses)
    {
        $addressData = array_map(function ($address) {
            return $address instanceof Address\AddressDto
                ? $address->toArray()
                : $this->addressFactory->factory($address)->toArray();
        }, $addresses);

        $response = $this->requestFactory->validateAddresses($addressData)->send();

        return array_map(function ($data) {
            return new AddressVerification\VerificationResultDto($data);
        }, $response->getData());
    }

    /**
     * Lists all Carriers setup in the ShipEngine account
     *
     * @link https://docs.shipengine.com/docs/list-your-carriers
     *
     * @return Carriers\CarrierDto[]
     */
    public function listCarriers()
    {
        $response = $this->requestFactory->listCarriers()->send();

        return array_map(function ($carrier) {
            return new Carriers\CarrierDto($carrier);
        }, $response->getData('carriers'));
    }

    /**
     * Get a single Carrier
     *
     * @param string $carrierId
     * @return Carriers\CarrierDto
     */
    public function getCarrier(string $carrierId)
    {
        $response = $this->requestFactory->getCarrier($carrierId)->send();

        return new Carriers\CarrierDto($response->getData());
    }

    /**
     * List all Services offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\ServiceDto[]
     */
    public function listCarrierServices(string $carrierId)
    {
        $response = $this->requestFactory->listCarrierServices($carrierId)->send();

        return array_map(function ($carrier) {
            return new Carriers\ServiceDto($carrier);
        }, $response->getData('services'));
    }

    /**
     * List all Package Types offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\PackageTypeDto[]
     */
    public function listCarrierPackageTypes(string $carrierId)
    {
        $response = $this->requestFactory->listCarrierPackageTypes($carrierId)->send();

        return array_map(function ($carrier) {
            return new Carriers\PackageTypeDto($carrier);
        }, $response->getData('packages'));
    }

    /**
     * Get all Options offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\OptionDto[]
     */
    public function getCarrierOptions(string $carrierId)
    {
        $response = $this->requestFactory->getCarrierOptions($carrierId)->send();

        return array_map(function ($carrier) {
            return new Carriers\OptionDto($carrier);
        }, $response->getData('options'));
    }
}