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
     * @var Weight
     */
    private $weight;

    /**
     * @var Dimensions
     */
    private $dimensions;

    /**
     * @param Weight $weight
     * @param Dimensions $dimensions
     */
    public function __construct(Weight $weight, Dimensions $dimensions)
    {
        $this->weight = $weight;
        $this->dimensions = $dimensions;
    }

    /**
     * @return Weight
     */
    public function getWeight(): Weight
    {
        return $this->weight;
    }

    /**
     * @return Dimensions
     */
    public function getDimensions(): Dimensions
    {
        return $this->dimensions;
    }

    /**
     * @return float
     */
    public function getWeightAmount()
    {
        return $this->weight->getValue();
    }

    /**
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weight->getUnit();
    }
}