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
     * The shipment's PRO number (essentially a tracking number)
     *
     * @var string
     */
    protected $proNumber;

    /**
     * @var string|null
     */
    protected $shipFromPostalCode;

    /**
     * @var string|null
     */
    protected $shipToPostalCode;

    /**
     * @var \DateTime|null
     */
    protected $pickupDate;

    /**
     * @var \DateTime|null
     */
    protected $estimatedDeliveryDate;

    /**
     * @var \DateTime|null
     */
    protected $actualDeliveryDate;

    /**
     * The name of the person who signed for delivery, if available
     *
     * @var string|null
     */
    protected $signatureName;

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
     *
     * @param string|null $date
     * @param string|null $time
     * @return \DateTime|null
     */
    private function toDateTime($date, $time = null)
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
    public function getProNumber()
    {
        return $this->proNumber;
    }

    /**
     * @return string|null
     */
    public function getShipFromPostalCode()
    {
        return $this->shipFromPostalCode;
    }

    /**
     * @return string|null
     */
    public function getShipToPostalCode()
    {
        return $this->shipToPostalCode;
    }

    /**
     * @return \DateTime|null
     */
    public function getPickupDate()
    {
        return $this->pickupDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getEstimatedDeliveryDate()
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getActualDeliveryDate()
    {
        return $this->actualDeliveryDate;
    }

    /**
     * @return string|null
     */
    public function getSignatureName()
    {
        return $this->signatureName;
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
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

    /**
     * Whether the shipment has been delivered, determined from the actual delivery date or a
     * "delivered" tracking event.
     *
     * @return bool
     */
    public function isDelivered()
    {
        if (!is_null($this->actualDeliveryDate)) {
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
