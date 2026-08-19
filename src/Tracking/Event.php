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

namespace jsamhall\ShipEngine\Tracking;

/**
 * Class Event
 *
 * Represents a single tracking scan/event
 *
 * @package jsamhall\ShipEngine\Tracking
 */
class Event
{
    protected ?\DateTime $occurredAt;

    protected ?\DateTime $carrierOccurredAt;

    protected ?string $description;

    protected ?string $cityLocality;

    protected ?string $stateProvince;

    protected ?string $postalCode;

    protected ?string $countryCode;

    protected ?string $companyName;

    protected ?string $signer;

    protected ?string $eventCode;

    protected ?string $carrierStatusCode;

    protected ?string $carrierStatusDescription;

    protected ?float $latitude;

    protected ?float $longitude;

    public function __construct(array $event)
    {
        $this->occurredAt = $this->toDateTime($event['occurred_at'] ?? null);
        $this->carrierOccurredAt = $this->toDateTime($event['carrier_occurred_at'] ?? null);
        $this->description = $event['description'] ?? null;
        $this->cityLocality = $event['city_locality'] ?? null;
        $this->stateProvince = $event['state_province'] ?? null;
        $this->postalCode = $event['postal_code'] ?? null;
        $this->countryCode = $event['country_code'] ?? null;
        $this->companyName = $event['company_name'] ?? null;
        $this->signer = $event['signer'] ?? null;
        $this->eventCode = $event['event_code'] ?? null;
        $this->carrierStatusCode = $event['carrier_status_code'] ?? null;
        $this->carrierStatusDescription = $event['carrier_status_description'] ?? null;
        $this->latitude = isset($event['latitude']) ? (float) $event['latitude'] : null;
        $this->longitude = isset($event['longitude']) ? (float) $event['longitude'] : null;
    }

    /**
     * Convert a date string into a DateTime instance
     */
    private function toDateTime(?string $value): ?\DateTime
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new \DateTime($value);
    }

    public function getOccurredAt(): ?\DateTime
    {
        return $this->occurredAt;
    }

    public function getCarrierOccurredAt(): ?\DateTime
    {
        return $this->carrierOccurredAt;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCityLocality(): ?string
    {
        return $this->cityLocality;
    }

    public function getStateProvince(): ?string
    {
        return $this->stateProvince;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getSigner(): ?string
    {
        return $this->signer;
    }

    public function getEventCode(): ?string
    {
        return $this->eventCode;
    }

    public function getCarrierStatusCode(): ?string
    {
        return $this->carrierStatusCode;
    }

    public function getCarrierStatusDescription(): ?string
    {
        return $this->carrierStatusDescription;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
}
