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

/**
 * Class Event
 *
 * A single LTL (Less Than Truckload) tracking event.
 *
 * @link https://www.shipengine.com/docs/ltl/tracking/
 *
 * @package jsamhall\ShipEngine\Tracking\Ltl
 */
class Event
{
    public const STATUS_DELIVERED = 'delivered';

    /**
     * The raw event status as reported by ShipEngine (e.g. "delivered"). ShipEngine does not
     * publish a closed set of LTL statuses, so this is left as a string.
     */
    protected string $status;

    protected ?\DateTime $occurredAt;

    protected ?string $cityLocality;

    protected ?string $stateProvince;

    /**
     * A free-text description supplied by the carrier for this event.
     */
    protected ?string $carrierDescription;

    public function __construct(array $event)
    {
        $this->status = $event['status'] ?? '';
        $this->occurredAt = $this->toDateTime($event['date'] ?? null, $event['time'] ?? null);
        $this->cityLocality = $event['location']['city_locality'] ?? null;
        $this->stateProvince = $event['location']['state_province'] ?? null;
        $this->carrierDescription = $event['carrier']['description'] ?? null;
    }

    /**
     * Combine the API's separate date ("YYYY-MM-DD") and time ("HH:MM:SS") fields, either of which
     * may be an empty string, into a single DateTime.
     */
    private function toDateTime(?string $date, ?string $time): ?\DateTime
    {
        $date = trim((string) $date);
        if ($date === '') {
            return null;
        }

        $time = trim((string) $time);
        $value = $time === '' ? $date : $date . ' ' . $time;

        return new \DateTime($value);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isDelivered(): bool
    {
        return strtolower($this->status) === self::STATUS_DELIVERED;
    }

    public function getOccurredAt(): ?\DateTime
    {
        return $this->occurredAt;
    }

    public function getCityLocality(): ?string
    {
        return $this->cityLocality;
    }

    public function getStateProvince(): ?string
    {
        return $this->stateProvince;
    }

    public function getCarrierDescription(): ?string
    {
        return $this->carrierDescription;
    }
}
