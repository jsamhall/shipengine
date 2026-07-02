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
    protected string $trackingNumber;

    protected ?string $trackingUrl;

    protected string $statusCode;

    protected ?string $statusDescription;

    protected ShipEngine\Carriers\CarrierCode $carrierCode;

    protected ?ShipEngine\Carriers\CarrierId $carrierId;

    protected ?string $carrierStatusCode;

    protected ?string $carrierStatusDescription;

    protected ?string $carrierDetailCode;

    protected ?string $exceptionDescription;

    protected ?\DateTime $shipDate;

    protected ?\DateTime $estimatedDeliveryDate;

    protected ?\DateTime $actualDeliveryDate;

    /**
     * @var Event[]
     */
    protected array $events;

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
     */
    private function toDateTime(?string $value): ?\DateTime
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new \DateTime($value);
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

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function getTrackingUrl(): ?string
    {
        return $this->trackingUrl;
    }

    public function getStatusDescription(): ?string
    {
        return $this->statusDescription;
    }

    public function getCarrierCode(): ShipEngine\Carriers\CarrierCode
    {
        return $this->carrierCode;
    }

    public function getCarrierId(): ?ShipEngine\Carriers\CarrierId
    {
        return $this->carrierId;
    }

    public function getCarrierStatusCode(): ?string
    {
        return $this->carrierStatusCode;
    }

    public function getCarrierStatusDescription(): ?string
    {
        return $this->carrierStatusDescription;
    }

    public function getCarrierDetailCode(): ?string
    {
        return $this->carrierDetailCode;
    }

    public function getExceptionDescription(): ?string
    {
        return $this->exceptionDescription;
    }

    public function getShipDate(): ?\DateTime
    {
        return $this->shipDate;
    }

    public function getEstimatedDeliveryDate(): ?\DateTime
    {
        return $this->estimatedDeliveryDate;
    }

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

    public function isDelivered(): bool
    {
        return $this->statusCode === StatusCode::Delivered->value;
    }

    public function hasException(): bool
    {
        return $this->statusCode === StatusCode::Exception->value;
    }

    public function isInTransit(): bool
    {
        return $this->statusCode === StatusCode::InTransit->value;
    }

    public function getLatestEvent(): ?Event
    {
        if (empty($this->events)) {
            return null;
        }

        return $this->events[count($this->events) - 1];
    }
}
