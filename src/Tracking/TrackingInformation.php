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

use jsamhall\ShipEngine;

/**
 * Class TrackingInformation
 *
 * Wrapper for a Tracking response from the ShipEngine API
 *
 * @package jsamhall\ShipEngine\Tracking
 */
class TrackingInformation extends ShipEngine\Api\Response
{
    /**
     * @var string
     */
    protected $trackingNumber;

    /**
     * @var string|null
     */
    protected $trackingUrl;

    /**
     * @var string
     */
    protected $statusCode;

    /**
     * @var string|null
     */
    protected $statusDescription;

    /**
     * @var ShipEngine\Carriers\CarrierCode
     */
    protected $carrierCode;

    /**
     * @var ShipEngine\Carriers\CarrierId|null
     */
    protected $carrierId;

    /**
     * @var string|null
     */
    protected $carrierStatusCode;

    /**
     * @var string|null
     */
    protected $carrierStatusDescription;

    /**
     * @var string|null
     */
    protected $carrierDetailCode;

    /**
     * @var string|null
     */
    protected $exceptionDescription;

    /**
     * @var \DateTime|null
     */
    protected $shipDate;

    /**
     * @var \DateTime|null
     */
    protected $estimatedDeliveryDate;

    /**
     * @var \DateTime|null
     */
    protected $actualDeliveryDate;

    /**
     * @var Event[]
     */
    protected $events;

    /**
     * TrackingInformation constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->trackingNumber = $data['tracking_number'];
        $this->trackingUrl = $data['tracking_url'] ?? null;
        $this->statusCode = $data['status_code'];
        $this->statusDescription = $data['status_description'] ?? null;
        $this->carrierCode = new ShipEngine\Carriers\CarrierCode($data['carrier_code']);
        $this->carrierId = isset($data['carrier_id'])
            ? new ShipEngine\Carriers\CarrierId((string) $data['carrier_id'])
            : null;
        $this->carrierStatusCode = $data['carrier_status_code'] ?? null;
        $this->carrierStatusDescription = $data['carrier_status_description'] ?? null;
        $this->carrierDetailCode = $data['carrier_detail_code'] ?? null;
        $this->exceptionDescription = $data['exception_description'] ?? null;
        $this->shipDate = $this->toDateTime($data['ship_date'] ?? null);
        $this->estimatedDeliveryDate = $this->toDateTime($data['estimated_delivery_date'] ?? null);
        $this->actualDeliveryDate = $this->toDateTime($data['actual_delivery_date'] ?? null);
        $this->events = array_map(function ($e) {
            return new Event($e);
        }, $data['events'] ?? []);
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
     * @return string
     */
    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    /**
     * @return string|null
     */
    public function getTrackingUrl(): ?string
    {
        return $this->trackingUrl;
    }

    /**
     * The raw ShipEngine status code (e.g. "DE").
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * The status as a {@see StatusCode} enum, or null if ShipEngine returned a code this library
     * does not yet recognize.
     */
    public function getStatus(): ?StatusCode
    {
        return StatusCode::tryFrom($this->statusCode);
    }

    /**
     * @return string|null
     */
    public function getStatusDescription(): ?string
    {
        return $this->statusDescription;
    }

    /**
     * @return ShipEngine\Carriers\CarrierCode
     */
    public function getCarrierCode()
    {
        return $this->carrierCode;
    }

    /**
     * @return ShipEngine\Carriers\CarrierId|null
     */
    public function getCarrierId()
    {
        return $this->carrierId;
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
     * @return string|null
     */
    public function getCarrierDetailCode(): ?string
    {
        return $this->carrierDetailCode;
    }

    /**
     * @return string|null
     */
    public function getExceptionDescription(): ?string
    {
        return $this->exceptionDescription;
    }

    /**
     * @return \DateTime|null
     */
    public function getShipDate(): ?\DateTime
    {
        return $this->shipDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getEstimatedDeliveryDate(): ?\DateTime
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getActualDeliveryDate(): ?\DateTime
    {
        return $this->actualDeliveryDate;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->statusCode === StatusCode::Delivered->value;
    }

    /**
     * @return bool
     */
    public function hasException(): bool
    {
        return $this->statusCode === StatusCode::Exception->value;
    }

    /**
     * @return bool
     */
    public function isInTransit(): bool
    {
        return $this->statusCode === StatusCode::InTransit->value;
    }

    /**
     * @return Event|null
     */
    public function getLatestEvent()
    {
        if (empty($this->events)) {
            return null;
        }

        return $this->events[count($this->events) - 1];
    }
}
