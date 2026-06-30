<?php
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
    /**
     * The date/time at which this event occurred
     *
     * @var \DateTime|null
     */
    protected $occurredAt;

    /**
     * The date/time at which this event occurred according to the Carrier
     *
     * @var \DateTime|null
     */
    protected $carrierOccurredAt;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $cityLocality;

    /**
     * @var string|null
     */
    protected $stateProvince;

    /**
     * @var string|null
     */
    protected $postalCode;

    /**
     * @var string|null
     */
    protected $countryCode;

    /**
     * @var string|null
     */
    protected $companyName;

    /**
     * @var string|null
     */
    protected $signer;

    /**
     * @var string|null
     */
    protected $eventCode;

    /**
     * @var string|null
     */
    protected $carrierStatusCode;

    /**
     * @var string|null
     */
    protected $carrierStatusDescription;

    /**
     * @var float|null
     */
    protected $latitude;

    /**
     * @var float|null
     */
    protected $longitude;

    /**
     * Event constructor.
     *
     * @param array $event
     */
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
     *
     * @param string|null $value
     * @return \DateTime|null
     */
    private function toDateTime(?string $value): ?\DateTime
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new \DateTime($value);
    }

    /**
     * @return \DateTime|null
     */
    public function getOccurredAt(): ?\DateTime
    {
        return $this->occurredAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getCarrierOccurredAt(): ?\DateTime
    {
        return $this->carrierOccurredAt;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getCityLocality(): ?string
    {
        return $this->cityLocality;
    }

    /**
     * @return string|null
     */
    public function getStateProvince(): ?string
    {
        return $this->stateProvince;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @return string|null
     */
    public function getSigner(): ?string
    {
        return $this->signer;
    }

    /**
     * @return string|null
     */
    public function getEventCode(): ?string
    {
        return $this->eventCode;
    }

    /**
     * @return string|null
     */
    public function getCarrierStatusCode(): ?string
    {
        return $this->carrierStatusCode;
    }

    /**
     * @return string|null
     */
    public function getCarrierStatusDescription(): ?string
    {
        return $this->carrierStatusDescription;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
}
