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
    const STATUS_DELIVERED = 'delivered';

    /**
     * The raw event status as reported by ShipEngine (e.g. "delivered")
     *
     * @var string
     */
    protected $status;

    /**
     * @var \DateTime|null
     */
    protected $occurredAt;

    /**
     * @var string|null
     */
    protected $cityLocality;

    /**
     * @var string|null
     */
    protected $stateProvince;

    /**
     * A free-text description supplied by the carrier for this event
     *
     * @var string|null
     */
    protected $carrierDescription;

    /**
     * Event constructor.
     *
     * @param array $event
     */
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
     *
     * @param string|null $date
     * @param string|null $time
     * @return \DateTime|null
     */
    private function toDateTime($date, $time)
    {
        $date = trim((string) $date);
        if ($date === '') {
            return null;
        }

        $time = trim((string) $time);
        $value = $time === '' ? $date : $date . ' ' . $time;

        return new \DateTime($value);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isDelivered()
    {
        return strtolower($this->status) === self::STATUS_DELIVERED;
    }

    /**
     * @return \DateTime|null
     */
    public function getOccurredAt()
    {
        return $this->occurredAt;
    }

    /**
     * @return string|null
     */
    public function getCityLocality()
    {
        return $this->cityLocality;
    }

    /**
     * @return string|null
     */
    public function getStateProvince()
    {
        return $this->stateProvince;
    }

    /**
     * @return string|null
     */
    public function getCarrierDescription()
    {
        return $this->carrierDescription;
    }
}
