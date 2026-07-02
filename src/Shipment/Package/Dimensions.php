<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Shipment\Package;

class Dimensions
{
    private $height;

    private $width;

    private $length;

    private DimensionUnit $unit;

    public function __construct($length, $width, $height, DimensionUnit $unit = DimensionUnit::Inch)
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    public function getUnit(): DimensionUnit
    {
        return $this->unit;
    }
}
