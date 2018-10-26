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


class Package
{
    const UNIT_OUNCE = 'ounce';
    const UNIT_POUND = 'pound';
    const UNIT_GRAM  = 'gram';

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
    protected $weightUnit = self::UNIT_OUNCE;

    /**
     * @var Dimension
     */
    protected $dimension;

    /**
     * Package constructor.
     *
     * @param Dimension $dimension
     * @param float  $weightAmount
     * @param string $weightUnit
     */
    public function __construct(Dimension $dimension, $weightAmount, $weightUnit = self::UNIT_OUNCE)
    {
        $this->dimension = $dimension;
        $this->weightAmount = $weightAmount;
        $this->weightUnit = $weightUnit;
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
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }
}