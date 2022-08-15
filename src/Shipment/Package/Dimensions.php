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

namespace jsamhall\ShipEngine\Shipment\Package;

class Dimensions
{
    const UNIT_INCH = 'inch';
    const UNIT_CENTIMETER = 'centimeter';

    /**
     * The package length
     *
     * @var float
     */
    protected $dimensionLength;

    /**
     * The package width
     *
     * @var float
     */
    protected $dimensionWidth;

    /**
     * The package height
     *
     * @var float
     */
    protected $dimensionHeight;

    /**
     * The unit of measurement for the package dimensions
     *
     * @var string
     */
    protected $dimensionUnit = self::UNIT_INCH;

    /**
     * Dimensions constructor.
     * @param float $dimensionLength
     * @param float $dimensionWidth
     * @param float $dimensionHeight
     * @param string $dimensionUnit
     */
    public function __construct(float $dimensionLength, float $dimensionWidth, float $dimensionHeight, string $dimensionUnit = self::UNIT_INCH)
    {
        $this->dimensionLength = $dimensionLength;
        $this->dimensionWidth = $dimensionWidth;
        $this->dimensionHeight = $dimensionHeight;
        $this->dimensionUnit = $dimensionUnit;
    }

    public function getDimensionLength(): float
    {
        return $this->dimensionLength;
    }

    public function getDimensionWidth(): float
    {
        return $this->dimensionWidth;
    }

    public function getDimensionHeight(): float
    {
        return $this->dimensionHeight;
    }

    public function getDimensionUnit(): string
    {
        return $this->dimensionUnit;
    }
}
