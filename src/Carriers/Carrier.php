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
namespace jsamhall\ShipEngine\Carriers;

/**
 * Class Carrier
 *
 * A Data Transfer Object wrapping Carrier data as provided by listCarriers API response
 *
 * @package ShipEngine\Carriers
 */
class Carrier
{
    /**
     * The CarrierId used to reference this Carrier Account within ShipEngine
     * Used when multiple Accounts are established for the same Carrier
     */
    protected CarrierId $id;

    /**
     * The Code used to reference this Carrier Account within ShipEngine
     * Used when only one account has been established for the Carrier
     */
    protected CarrierCode $code;

    /**
     * The customer's account number with the Carrier
     */
    protected ?string $accountNumber;

    /**
     * Indicates if this Carrier requires postage to be purchased prior to label creation
     */
    protected bool $requiresFundedAmount;

    /**
     * The Customer's Balance with this carrier, if applicable
     */
    protected float $accountBalance = 0.00;

    /**
     * The Nickname provided for this Carrier within ShipEngine
     */
    protected ?string $nickname;

    /**
     * The Display Name of this carrier within ShipEngine
     */
    protected ?string $friendlyName;

    /**
     * Indicates if this is the Primary Account for this Carrier
     * Used when multiple Accounts have been established for a given Carrier
     */
    protected bool $primary;

    /**
     * @var Service[]
     */
    protected array $services = [];

    /**
     * @var PackageType[]
     */
    protected array $packageTypes = [];

    /**
     * @var AvailableOption[]
     */
    protected array $options = [];

    /**
     * @param array $carrierData Carrier Data provided by ShipEngine Carrier API
     */
    public function __construct(array $carrierData)
    {
        $this->id = new CarrierId($carrierData['carrier_id']);
        $this->code = new CarrierCode($carrierData['carrier_code']);
        $this->accountNumber = $carrierData['account_number'] ?? null;
        $this->requiresFundedAmount = (bool) $carrierData['requires_funded_amount'];
        $this->accountBalance = (float) $carrierData['balance'];
        $this->nickname = $carrierData['nickname'] ?? null;
        $this->friendlyName = $carrierData['friendly_name'] ?? null;
        $this->primary = (bool) $carrierData['primary'];

        $this->setServices($carrierData['services'])
             ->setPackages($carrierData['packages'])
             ->setOptions($carrierData['options']);
    }

    public function getId(): CarrierId
    {
        return $this->id;
    }

    public function getCode(): CarrierCode
    {
        return $this->code;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function doesRequireFundedAmount(): bool
    {
        return $this->requiresFundedAmount;
    }

    public function getAccountBalance(): float
    {
        return $this->accountBalance;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getFriendlyName(): ?string
    {
        return $this->friendlyName;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    /**
     * @return Service[]
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @return PackageType[]
     */
    public function getPackageTypes(): array
    {
        return $this->packageTypes;
    }

    /**
     * @return AvailableOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    private function setServices(array $services): self
    {
        foreach ($services as $service) {
            $this->services[] = new Service($service);
        }

        return $this;
    }

    private function setPackages(array $packages): self
    {
        foreach ($packages as $package) {
            $this->packageTypes[] = new PackageType($package);
        }

        return $this;
    }

    private function setOptions(array $options): self
    {
        foreach ($options as $option) {
            $this->options[] = new AvailableOption($option);
        }

        return $this;
    }
}
