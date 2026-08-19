<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Shipment;

use jsamhall\ShipEngine\Shipment\Package\DimensionUnit;
use jsamhall\ShipEngine\Shipment\Package\Dimensions;
use jsamhall\ShipEngine\Shipment\Package\Weight;
use jsamhall\ShipEngine\Shipment\Package\WeightUnit;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for Weight/Dimensions. In v2.0 the UNIT_* constants + in_array() validation
 * were replaced by the WeightUnit/DimensionUnit backed enums; these tests pin the wire values and
 * conversion behavior. Invalid units are now unrepresentable at the type level, so the previous
 * "rejects unknown unit" tests are gone by construction.
 */
class PackageMeasurementTest extends TestCase
{
    public function testWeightDefaultsToOunces(): void
    {
        $weight = new Weight(10);

        self::assertSame(10, $weight->getValue());
        self::assertSame(WeightUnit::Ounce, $weight->getUnit());
    }

    public function testUnitEnumWireValues(): void
    {
        self::assertSame('ounce', WeightUnit::Ounce->value);
        self::assertSame('pound', WeightUnit::Pound->value);
        self::assertSame('inch', DimensionUnit::Inch->value);
        self::assertSame('centimeter', DimensionUnit::Centimeter->value);
    }

    public function testInOuncesConvertsPounds(): void
    {
        self::assertSame(160, (new Weight(10, WeightUnit::Pound))->inOunces());
        self::assertSame(10, (new Weight(10, WeightUnit::Ounce))->inOunces());
    }

    public function testDimensionsGetters(): void
    {
        $dimensions = new Dimensions(12, 8, 4);

        self::assertSame(12, $dimensions->getLength());
        self::assertSame(8, $dimensions->getWidth());
        self::assertSame(4, $dimensions->getHeight());
        self::assertSame(DimensionUnit::Inch, $dimensions->getUnit());
    }
}
