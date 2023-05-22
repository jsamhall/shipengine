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

namespace jsamhall\ShipEngine\Labels;

use jsamhall\ShipEngine;

class Shipment extends ShipEngine\Shipment\AbstractShipment
{

    /**
     * Optional carrierId
     *
     * @var ShipEngine\Carriers\CarrierId|null
     */
    protected $carrierId = null;

    /**
     * The service code to request a Label for
     *
     * @var ShipEngine\Carriers\ServiceCode
     */
    protected $serviceCode;

    /**
     * @var ShipEngine\Carriers\DeliveryConfirmation|null
     */
    protected $deliveryConfirmation = null;


    public function __construct(
        ShipEngine\Carriers\ServiceCode $service,
        ShipEngine\Address\Address      $shipTo,
        ShipEngine\Address\Address      $shipFrom,
        array                           $packages = [],
        ?InsuranceProvider              $insuranceProvider = null
    ) {
        parent::__construct($shipTo, $shipFrom, $packages, $insuranceProvider);
        $this->serviceCode = $service;
    }

    public function getServiceCode(): ShipEngine\Carriers\ServiceCode
    {
        return $this->serviceCode;
    }


    /**
     * Specify a specific carrierId for which this Shipment applies
     * Only necessary when multiple Accounts for the same Carrier have been established
     *
     * @param ShipEngine\Carriers\CarrierId $carrierId
     * @return $this
     */
    public function specifyCarrierId(ShipEngine\Carriers\CarrierId $carrierId)
    {
        $this->carrierId = $carrierId;

        return $this;
    }
    /**
     * Add Delivery Confirmation to the Label Shipment
     *
     * @link https://docs.shipengine.com/docs/request-delivery-confirmation
     *
     * @param ShipEngine\Carriers\DeliveryConfirmation $deliveryConfirmation
     * @return static $this
     */
    public function setDeliveryConfirmation(ShipEngine\Carriers\DeliveryConfirmation $deliveryConfirmation)
    {
        $this->deliveryConfirmation = $deliveryConfirmation;

        return $this;
    }

    /**
     * Prepare the Shipment Data for transport as an array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array_merge(parent::toArray(), [
            'service_code' => $this->serviceCode->__toString()
        ]);

        if (!is_null($this->deliveryConfirmation)) {
            $data['confirmation'] = $this->deliveryConfirmation->__toString();
        }

        if (!is_null($this->carrierId)) {
            $data['carrier_id'] = $this->carrierId->__toString();
        }

        return $data;
    }
}