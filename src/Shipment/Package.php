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

use jsamhall\ShipEngine\Shipment\Package\Dimensions;
use jsamhall\ShipEngine\Shipment\Package\Weight;

class Package
{

    /**
     * The package weight
     *
     * @var float
     */
    protected $weightAmount;

    /**
     * The unit of measurement for the package weight
     *
     * @var string
     */
    protected $weightUnit = Weight::UNIT_OUNCE;

    /**
     * @var Dimensions|null
     */
    protected $dimension;

    /**
     * @var string|null
     */
    protected $packageCode;

    /**
     * Package constructor.
     * @param Dimensions|null $dimension
     * @param $weightAmount
     * @param string $weightUnit
     * @param null $packageCode
     */
    public function __construct(?Dimensions $dimension, $weightAmount, string $weightUnit = Weight::UNIT_OUNCE, $packageCode = null)
    {
        $this->dimension = $dimension;
        $this->weightAmount = $weightAmount;
        $this->weightUnit = $weightUnit;
        $this->packageCode = $packageCode;
    }

    /**
     * @return float
     */
    public function getWeightAmount()
    {
        return $this->weightAmount;
    }

    /**
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weightUnit;
    }

    /**
     * @return Dimensions
     */
    public function getDimension(): ?Dimensions
    {
        return $this->dimension;
    }

    /**
     * @return null|string
     */
    public function getPackageCode(): ?string
    {
        return $this->packageCode;
    }
}
