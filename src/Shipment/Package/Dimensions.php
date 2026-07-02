<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Shipment\Package;

class Dimensions
{
    public function __construct(
        private readonly int|float $length,
        private readonly int|float $width,
        private readonly int|float $height,
        private readonly DimensionUnit $unit = DimensionUnit::Inch
    ) {
    }

    public function getHeight(): int|float
    {
        return $this->height;
    }

    public function getWidth(): int|float
    {
        return $this->width;
    }

    public function getLength(): int|float
    {
        return $this->length;
    }

    public function getUnit(): DimensionUnit
    {
        return $this->unit;
    }
}
