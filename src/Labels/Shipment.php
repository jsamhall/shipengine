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
     * @var ShipEngine\Carriers\ServiceCode
     */
    protected $serviceCode;


    /**
     * Shipment constructor.
     *
     * @param ShipEngine\Carriers\ServiceCode  $serviceCode
     * @param ShipEngine\Address\Address    $shipTo
     * @param ShipEngine\Address\Address    $shipFrom
     * @param ShipEngine\Shipment\Package[] $packages
     */
    public function __construct(
        ShipEngine\Carriers\ServiceCode $serviceCode,
        ShipEngine\Address\Address $shipTo,
        ShipEngine\Address\Address $shipFrom,
        array $packages = []
    ) {
        $this->serviceCode = $serviceCode;
        parent::__construct($shipTo, $shipFrom, $packages);
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'service_code' => $this->serviceCode->__toString(),
        ]);
    }
}