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

namespace jsamhall\ShipEngine\Shipment;

use jsamhall\ShipEngine;

abstract class AbstractShipment
{
    /**
     * The destination address of the Shipment
     *
     * @var ShipEngine\Address\Address
     */
    protected $shipTo;

    /**
     * The origin address of the Shipment
     *
     * @var ShipEngine\Address\Address
     */
    protected $shipFrom;

    /**
     * One or more packages to be quoted
     *
     * @var Package[]
     */
    protected $packages = [];

    /**
     * AbstractShipment constructor.
     *
     * @param ShipEngine\Address\Address $shipTo
     * @param ShipEngine\Address\Address $shipFrom
     * @param Package[]                  $packages
     */
    public function __construct(ShipEngine\Address\Address $shipTo, ShipEngine\Address\Address $shipFrom, array $packages = [])
    {
        $this->shipTo = $shipTo;
        $this->shipFrom = $shipFrom;

        foreach ($packages as $package) {
            $this->addPackage($package);
        }
    }


    /**
     * @param Package $package
     * @return static $this
     */
    public function addPackage(Package $package)
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * @return Package[]
     */
    public function getPackages()
    {
        return $this->packages;
    }

    public function toArray()
    {
        return [
            'ship_to'   => $this->shipTo->toArray(),
            'ship_from' => $this->shipFrom->toArray(),
            'packages'  => array_map(function (Package $package) {
                return [
                    'weight' => [
                        'value' => $package->getWeightAmount(),
                        'unit'  => $package->getWeightUnit()
                    ],
                    'dimensions' => [
                        'unit'   => $package->getDimension()->getDimensionUnit(),
                        'length' => $package->getDimension()->getDimensionLength(),
                        'width'  => $package->getDimension()->getDimensionWidth(),
                        'height' => $package->getDimension()->getDimensionHeight(),
                    ],
                ];
            }, $this->packages)
        ];
    }
}