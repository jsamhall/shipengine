<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\ValueObject;

use jsamhall\ShipEngine\Carriers\CarrierCode;
use jsamhall\ShipEngine\Carriers\CustomServiceCode;
use jsamhall\ShipEngine\Carriers\PackageCode;
use jsamhall\ShipEngine\ValueObject\Amount;
use jsamhall\ShipEngine\ValueObject\Id;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for the open (arbitrary-string) value objects. These stay value objects in
 * v2.0 (they wrap unbounded API strings), so their __toString/equals contract must be preserved.
 */
class ValueObjectTest extends TestCase
{
    public function testIdToStringAndEquals(): void
    {
        $a = new Id('se-12345');
        $b = new Id('se-12345');
        $c = new Id('se-99999');

        self::assertSame('se-12345', (string) $a);
        self::assertTrue($a->equals($b));
        self::assertFalse($a->equals($c));
    }

    public function testCustomServiceCodeEquals(): void
    {
        $a = new CustomServiceCode('ups_ground');
        $b = new CustomServiceCode('ups_ground');
        $c = new CustomServiceCode('fedex_ground');

        self::assertSame('ups_ground', $a->code());
        self::assertTrue($a->equals($b));
        self::assertFalse($a->equals($c));
    }

    public function testCarrierAndPackageCode(): void
    {
        self::assertSame('ups', (string) new CarrierCode('ups'));
        self::assertTrue((new CarrierCode('ups'))->equals(new CarrierCode('ups')));

        self::assertSame('package', (string) new PackageCode('package'));
        self::assertTrue((new PackageCode('package'))->equals(new PackageCode('package')));
    }

    public function testAmountDefaultsToUsd(): void
    {
        $amount = new Amount(12.50);

        self::assertSame(12.50, $amount->getAmount());
        self::assertSame('usd', $amount->getCurrency());
        self::assertSame('eur', (new Amount(5.0, 'eur'))->getCurrency());
    }
}
