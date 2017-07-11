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
     * Package constructor.
     *
     * @param float  $weightAmount
     * @param string $weightUnit
     */
    public function __construct($weightAmount, $weightUnit = self::UNIT_OUNCE)
    {
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
}