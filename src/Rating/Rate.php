<?php

declare(strict_types=1);

/**
 * ShipEngine API Wrapper
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Rating;

use DateTime;
use Exception;
use jsamhall\ShipEngine;

class Rate
{
    /**
     * Id of this rate, used when creating a Label using this Rate
     */
    protected ShipEngine\Rating\RateId $id;

    protected ?string $type = null;

    /**
     * The ShipEngine ID of the Carrier from which this rate was retrieved
     */
    protected ShipEngine\Carriers\CarrierId $carrierId;

    /**
     * The code used to identify the Carrier when only one account exists
     * within the ShipEngine platform.
     */
    protected ShipEngine\Carriers\CarrierCode $carrierCode;

    protected ?string $carrierNickname = null;

    protected ?string $carrierFriendlyName = null;

    protected ?ShipEngine\ValueObject\Amount $shippingAmount = null;

    protected ?ShipEngine\ValueObject\Amount $insuranceAmount = null;

    protected ?ShipEngine\ValueObject\Amount $confirmationAmount = null;

    protected ?ShipEngine\ValueObject\Amount $otherAmount = null;

    protected ?string $zone = null;

    protected ?string $packageType = null;

    /**
     * Number of days until Delivery
     */
    protected ?int $deliveryDays = null;

    /**
     * Is the service guaranteed?
     */
    protected ?bool $guaranteedService = null;

    protected ?string $estimatedDeliveryDate = null;

    /**
     * A delivery date message as provided by the Carrier
     * e.g. "Monday 7/17 by 11:00 PM"
     */
    protected ?string $carrierDeliveryDays = null;

    /**
     * The pickup date for the Shipment
     */
    protected ?string $shipDate = null;

    /**
     * Are negotiated rates applied to this Rate? (carrier specific)
     */
    protected ?bool $negotiatedRatesApplied = null;

    /**
     * The name of the Service as described by the Carrier
     * e.g. UPS® Ground
     */
    protected ?string $serviceName = null;

    /**
     * The internal code used to reference this Service
     * e.g. ups_ground
     */
    protected ShipEngine\Carriers\ServiceCode $serviceCode;

    protected ?bool $trackable = null;

    /**
     * @var string[]
     */
    protected array $warningMessages = [];

    /**
     * @var string[]
     */
    protected array $errorMessages = [];

    public function __construct(array $rateData)
    {
        $this->id = new RateId($rateData['rate_id']);
        $this->carrierId = new ShipEngine\Carriers\CarrierId($rateData['carrier_id']);
        $this->carrierCode = new ShipEngine\Carriers\CarrierCode($rateData['carrier_code']);
        $this->serviceCode = new ShipEngine\Carriers\CustomServiceCode($rateData['service_code']);

        $this->type = $rateData['rate_type'] ?? null;
        $this->carrierNickname = $rateData['carrier_nickname'] ?? null;
        $this->carrierFriendlyName = $rateData['carrier_friendly_name'] ?? null;

        $this->shippingAmount = $this->amountFrom($rateData, 'shipping_amount');
        $this->insuranceAmount = $this->amountFrom($rateData, 'insurance_amount');
        $this->confirmationAmount = $this->amountFrom($rateData, 'confirmation_amount');
        $this->otherAmount = $this->amountFrom($rateData, 'other_amount');

        $this->zone = isset($rateData['zone']) ? (string) $rateData['zone'] : null;
        $this->packageType = $rateData['package_type'] ?? null;
        $this->deliveryDays = isset($rateData['delivery_days']) ? (int) $rateData['delivery_days'] : null;
        $this->guaranteedService = isset($rateData['guaranteed_service']) ? (bool) $rateData['guaranteed_service'] : null;
        $this->estimatedDeliveryDate = $rateData['estimated_delivery_date'] ?? null;
        $this->carrierDeliveryDays = $rateData['carrier_delivery_days'] ?? null;
        $this->shipDate = $rateData['ship_date'] ?? null;
        $this->negotiatedRatesApplied = isset($rateData['negotiated_rate']) ? (bool) $rateData['negotiated_rate'] : null;
        $this->serviceName = $rateData['service_type'] ?? null;
        $this->trackable = isset($rateData['trackable']) ? (bool) $rateData['trackable'] : null;
        $this->warningMessages = $rateData['warning_messages'] ?? [];
        $this->errorMessages = $rateData['error_messages'] ?? [];
    }

    private function amountFrom(array $rateData, string $key): ?ShipEngine\ValueObject\Amount
    {
        if (! isset($rateData[$key])) {
            return null;
        }

        return new ShipEngine\ValueObject\Amount($rateData[$key]['amount'], $rateData[$key]['currency']);
    }

    public function getId(): RateId
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getCarrierId(): ShipEngine\Carriers\CarrierId
    {
        return $this->carrierId;
    }

    public function getCarrierCode(): ShipEngine\Carriers\CarrierCode
    {
        return $this->carrierCode;
    }

    public function getCarrierNickname(): ?string
    {
        return $this->carrierNickname;
    }

    public function getCarrierFriendlyName(): ?string
    {
        return $this->carrierFriendlyName;
    }

    public function getGrossAmount(): float
    {
        return ($this->shippingAmount?->getAmount() ?? 0.0)
            + ($this->insuranceAmount?->getAmount() ?? 0.0)
            + ($this->confirmationAmount?->getAmount() ?? 0.0)
            + ($this->otherAmount?->getAmount() ?? 0.0);
    }

    public function getShippingAmount(): ?ShipEngine\ValueObject\Amount
    {
        return $this->shippingAmount;
    }

    public function getInsuranceAmount(): ?ShipEngine\ValueObject\Amount
    {
        return $this->insuranceAmount;
    }

    public function getConfirmationAmount(): ?ShipEngine\ValueObject\Amount
    {
        return $this->confirmationAmount;
    }

    public function getOtherAmount(): ?ShipEngine\ValueObject\Amount
    {
        return $this->otherAmount;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function getPackageType(): ?string
    {
        return $this->packageType;
    }

    public function getDeliveryDays(): ?int
    {
        return $this->deliveryDays;
    }

    public function isGuaranteedService(): ?bool
    {
        return $this->guaranteedService;
    }

    /**
     * @throws Exception If returning a datetime object and the date string is malformed
     */
    public function getEstimatedDeliveryDate(bool $asDateTime = false): \DateTime|string|null
    {
        if ($this->estimatedDeliveryDate === null) {
            return null;
        }

        return $asDateTime ? new DateTime($this->estimatedDeliveryDate) : $this->estimatedDeliveryDate;
    }

    public function getCarrierDeliveryDays(): ?string
    {
        return $this->carrierDeliveryDays;
    }

    public function getShipDate(): ?string
    {
        return $this->shipDate;
    }

    public function hasNegotiatedRatesApplied(): ?bool
    {
        return $this->negotiatedRatesApplied;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function getServiceCode(): ShipEngine\Carriers\ServiceCode
    {
        return $this->serviceCode;
    }

    public function isTrackable(): ?bool
    {
        return $this->trackable;
    }

    /**
     * @return string[]
     */
    public function getWarningMessages(): array
    {
        return $this->warningMessages;
    }

    /**
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }
}
