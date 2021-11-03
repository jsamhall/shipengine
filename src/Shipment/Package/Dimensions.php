<?php

namespace jsamhall\ShipEngine\Shipment\Package;

class Dimensions
{
    const UNIT_INCH = 'inch';
    const UNIT_CENTIMETER = 'centimeter';

    private $height;

    private $width;

    private $length;

    private $unit;

    /**
     * @param $height
     * @param $width
     * @param $length
     * @param $unit
     */
    public function __construct($length, $width, $height, $unit = self:: UNIT_INCH)
    {
        $validUnits = [self::UNIT_INCH, self::UNIT_CENTIMETER];
        if (!in_array($unit, $validUnits)) {
            $msg = sprintf("Unit must be one of: %s", implode(',', $validUnits));
            throw new \InvalidArgumentException($msg);
        }

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

    /**
     * @return mixed|string
     */
    public function getUnit()
    {
        return $this->unit;
    }
}