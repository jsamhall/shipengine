<?php

namespace jsamhall\ShipEngine\Shipment\Package;

class Weight
{
    const UNIT_OUNCE = 'ounce';
    const UNIT_POUND = 'pound';

    private $value;

    private $unit = self::UNIT_OUNCE;

    /**
     * @param mixed $value
     * @param string $unit
     */
    public function __construct($value, $unit = self::UNIT_OUNCE)
    {
        $validUnits = [self::UNIT_OUNCE, self::UNIT_POUND];
        if (!in_array($unit, $validUnits)) {
            throw new \InvalidArgumentException("Unit must be one of: %s", implode(',', $validUnits));
        }

        $this->value = $value;
        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Get package weight in ounces
     * @return float|int|mixed
     */
    public function inOunces()
    {
        return $this->unit === self::UNIT_OUNCE
            ? $this->value
            : $this->value * 16;
    }
}