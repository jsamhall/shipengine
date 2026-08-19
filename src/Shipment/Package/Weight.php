<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Shipment\Package;

class Weight
{
    public function __construct(
        private readonly int|float $value,
        private readonly WeightUnit $unit = WeightUnit::Ounce
    ) {
    }

    public function getValue(): int|float
    {
        return $this->value;
    }

    public function getUnit(): WeightUnit
    {
        return $this->unit;
    }

    /**
     * Get package weight in ounces
     */
    public function inOunces(): int|float
    {
        return $this->unit === WeightUnit::Ounce
            ? $this->value
            : $this->value * 16;
    }
}
