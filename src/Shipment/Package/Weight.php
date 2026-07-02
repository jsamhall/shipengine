<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Shipment\Package;

class Weight
{
    private $value;

    private WeightUnit $unit;

    public function __construct($value, WeightUnit $unit = WeightUnit::Ounce)
    {
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

    public function getUnit(): WeightUnit
    {
        return $this->unit;
    }

    /**
     * Get package weight in ounces
     *
     * @return float|int|mixed
     */
    public function inOunces()
    {
        return $this->unit === WeightUnit::Ounce
            ? $this->value
            : $this->value * 16;
    }
}
