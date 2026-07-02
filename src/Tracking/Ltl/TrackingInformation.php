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

namespace jsamhall\ShipEngine\Tracking\Ltl;

use jsamhall\ShipEngine;

/**
 * Class TrackingInformation
 *
 * Wrapper for an LTL (Less Than Truckload) tracking response from the ShipEngine API.
 *
 * @link https://www.shipengine.com/docs/ltl/tracking/
 *
 * @package jsamhall\ShipEngine\Tracking\Ltl
 */
class TrackingInformation extends ShipEngine\Api\Response
{
    /**
     * The shipment's PRO number (essentially a tracking number).
     */
    protected string $proNumber;

    protected ?string $shipFromPostalCode;

    protected ?string $shipToPostalCode;

    protected ?\DateTime $pickupDate;

    protected ?\DateTime $estimatedDeliveryDate;

    protected ?\DateTime $actualDeliveryDate;

    /**
     * The name of the person who signed for delivery, if available.
     */
    protected ?string $signatureName;

    /**
     * @var Event[]
     */
    protected array $events;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->proNumber = $data['pro_number'] ?? '';
        $this->shipFromPostalCode = $data['ship_from']['address']['postal_code'] ?? null;
        $this->shipToPostalCode = $data['ship_to']['address']['postal_code'] ?? null;
        $this->pickupDate = $this->toDateTime($data['pickup']['date'] ?? null);
        $this->estimatedDeliveryDate = $this->toDateTime(
            $data['delivery']['estimated']['date'] ?? null,
            $data['delivery']['estimated']['time'] ?? null
        );
        $this->actualDeliveryDate = $this->toDateTime(
            $data['delivery']['actual']['date'] ?? null,
            $data['delivery']['actual']['time'] ?? null
        );
        $this->signatureName = $data['delivery']['signature']['name'] ?? null;
        $this->events = array_map(function ($event) {
            return new Event($event);
        }, $data['events'] ?? []);
    }

    /**
     * Combine the API's separate date ("YYYY-MM-DD") and optional time ("HH:MM:SS") fields, either
     * of which may be an empty string, into a single DateTime.
     */
    private function toDateTime(?string $date, ?string $time = null): ?\DateTime
    {
        $date = trim((string) $date);
        if ($date === '') {
            return null;
        }

        $time = trim((string) $time);
        $value = $time === '' ? $date : $date . ' ' . $time;

        return new \DateTime($value);
    }

    public function getProNumber(): string
    {
        return $this->proNumber;
    }

    public function getShipFromPostalCode(): ?string
    {
        return $this->shipFromPostalCode;
    }

    public function getShipToPostalCode(): ?string
    {
        return $this->shipToPostalCode;
    }

    public function getPickupDate(): ?\DateTime
    {
        return $this->pickupDate;
    }

    public function getEstimatedDeliveryDate(): ?\DateTime
    {
        return $this->estimatedDeliveryDate;
    }

    public function getActualDeliveryDate(): ?\DateTime
    {
        return $this->actualDeliveryDate;
    }

    public function getSignatureName(): ?string
    {
        return $this->signatureName;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function getLatestEvent(): ?Event
    {
        if (empty($this->events)) {
            return null;
        }

        return $this->events[count($this->events) - 1];
    }

    /**
     * Whether the shipment has been delivered, determined from the actual delivery date or a
     * "delivered" tracking event.
     */
    public function isDelivered(): bool
    {
        if ($this->actualDeliveryDate !== null) {
            return true;
        }

        foreach ($this->events as $event) {
            if ($event->isDelivered()) {
                return true;
            }
        }

        return false;
    }
}
