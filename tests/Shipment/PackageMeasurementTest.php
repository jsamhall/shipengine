<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Shipment;

use jsamhall\ShipEngine\Shipment\Package\Dimensions;
use jsamhall\ShipEngine\Shipment\Package\Weight;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for Weight/Dimensions. The UNIT_* constants + in_array() validation here are
 * prime enum candidates in v2.0; these tests pin the valid wire values and conversion behavior.
 */
class PackageMeasurementTest extends TestCase
{
    public function testWeightDefaultsToOunces(): void
    {
        $weight = new Weight(10);

        self::assertSame(10, $weight->getValue());
        self::assertSame('ounce', $weight->getUnit());
    }

    public function testWeightUnitWireValues(): void
    {
        self::assertSame('ounce', Weight::UNIT_OUNCE);
        self::assertSame('pound', Weight::UNIT_POUND);
    }

    public function testInOuncesConvertsPounds(): void
    {
        self::assertSame(160, (new Weight(10, Weight::UNIT_POUND))->inOunces());
        self::assertSame(10, (new Weight(10, Weight::UNIT_OUNCE))->inOunces());
    }

    public function testDimensionsWireValuesAndGetters(): void
    {
        self::assertSame('inch', Dimensions::UNIT_INCH);
        self::assertSame('centimeter', Dimensions::UNIT_CENTIMETER);

        $dimensions = new Dimensions(12, 8, 4);

        self::assertSame(12, $dimensions->getLength());
        self::assertSame(8, $dimensions->getWidth());
        self::assertSame(4, $dimensions->getHeight());
        self::assertSame('inch', $dimensions->getUnit());
    }

    public function testDimensionsRejectsUnknownUnit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Dimensions(1, 1, 1, 'furlong');
    }

    /**
     * Regression: Weight previously passed the unit list as the exception's int $code argument,
     * raising a TypeError on PHP 8 instead of a usable InvalidArgumentException.
     */
    public function testWeightRejectsUnknownUnit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unit must be one of: ounce, pound');

        new Weight(10, 'stone');
    }
}
