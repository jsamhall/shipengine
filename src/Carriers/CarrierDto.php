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
namespace jsamhall\ShipEngine\Carriers;

/**
 * Class CarrierDto
 *
 * A Data Transfer Option wrapping Carrier data as provided by listCarriers API response
 *
 * @package ShipEngine\Carriers
 */
class CarrierDto
{
    /**
     * The CarrierId used to reference this Carrier Account within ShipEngine
     * Used when multiple Accounts are established for the same Carrier
     *
     * @var string
     */
    protected $id;

    /**
     * The Code used to reference this Carrier Account within ShipEngine
     * Used when only one account has been established for the Carrier
     *
     * @var string
     */
    protected $code;

    /**
     * The customer's account number with the Carrier
     *
     * @var string
     */
    protected $accountNumber;

    /**
     * Indicates if this Carrier requires postage to be purchased prior to label creation
     *
     * @var bool
     */
    protected $requiresFundedAmount;

    /**
     * The Customer's Balance with this carrier, if applicable
     *
     * @var float
     */
    protected $accountBalance = 0.00;

    /**
     * The Nickname provided for this Carrier within ShipEngine
     *
     * @var string
     */
    protected $nickname;

    /**
     * The Display Name of this carrier within ShipEngine
     *
     * @var string
     */
    protected $friendlyName;

    /**
     * Indicates if this is the Primary Account for this Carrier
     * Used when multiple Accounts have been established for a given Carrier
     *
     * @var bool
     */
    protected $primary;

    /**
     * @var ServiceDto[]
     */
    protected $services = [];

    /**
     * @var PackageTypeDto[]
     */
    protected $packageTypes = [];

    /**
     * @var OptionDto[]
     */
    protected $options = [];

    /**
     * CarrierDto constructor.
     *
     * @param array $carrierData Carrier Data provided by ShipEngine Carrier API
     */
    public function __construct(array $carrierData)
    {
        $settableData = [
            $carrierData['carrier_id'],
            $carrierData['carrier_code'],
            $carrierData['account_number'],
            $carrierData['requires_funded_amount'],
            $carrierData['balance'],
            $carrierData['nickname'],
            $carrierData['friendly_name'],
            $carrierData['primary']
        ];

        list(
            $this->id,
            $this->code,
            $this->accountNumber,
            $this->requiresFundedAmount,
            $this->accountBalance,
            $this->nickname,
            $this->friendlyName,
            $this->primary
            ) = $settableData;

        $this->setServices($carrierData['services'])
             ->setPackages($carrierData['packages'])
             ->setOptions($carrierData['options']);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return boolean
     */
    public function doesRequireFundedAmount()
    {
        return $this->requiresFundedAmount;
    }

    /**
     * @return float
     */
    public function getAccountBalance()
    {
        return $this->accountBalance;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getFriendlyName()
    {
        return $this->friendlyName;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @return ServiceDto[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return PackageTypeDto[]
     */
    public function getPackageTypes()
    {
        return $this->packageTypes;
    }

    /**
     * @return OptionDto[]
     */
    public function getOptions()
    {
        return $this->options;
    }


    private function setServices(array $services)
    {
        foreach ($services as $service) {
            $this->services[] = new ServiceDto($service);
        }

        return $this;
    }

    private function setPackages(array $packages)
    {
        foreach ($packages as $package) {
            $this->packageTypes[] = new PackageTypeDto($package);
        }

        return $this;
    }

    private function setOptions(array $options)
    {
        foreach ($options as $option) {
            $this->options[] = new OptionDto($option);
        }

        return $this;
    }
}