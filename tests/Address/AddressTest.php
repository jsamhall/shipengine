<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Address;

use jsamhall\ShipEngine\Address\Address;
use jsamhall\ShipEngine\Address\ArrayFormatter;
use jsamhall\ShipEngine\Address\Factory;
use jsamhall\ShipEngine\Address\ResidentialIndicator;
use PHPUnit\Framework\TestCase;

/**
 * Behavior of the address residential indicator: it is always present in the payload and defaults
 * to residential (yes); callers must explicitly opt out for a commercial address.
 */
class AddressTest extends TestCase
{
    public function testResidentialIndicatorDefaultsToYes(): void
    {
        $address = (new Address())
            ->setName('Jane')
            ->setAddressLine1('1 A St')
            ->setCityLocality('Austin')
            ->setStateProvince('TX')
            ->setPostalCode('78701')
            ->setCountryCode('US');

        self::assertSame(ResidentialIndicator::Yes, $address->getAddressResidentialIndicator());
        self::assertArrayHasKey('address_residential_indicator', $address->toArray());
        self::assertSame('yes', $address->toArray()['address_residential_indicator']);
    }

    public function testClientCanOptOutOfResidential(): void
    {
        $address = (new Address())->setAddressResidentialIndicator(ResidentialIndicator::No);

        self::assertSame('no', $address->toArray()['address_residential_indicator']);
    }

    public function testFactoryDefaultsToResidentialWhenNotProvided(): void
    {
        $factory = new Factory(new ArrayFormatter());
        $address = $factory->factory(['name' => 'Jane', 'city_locality' => 'Austin']);

        self::assertSame(ResidentialIndicator::Yes, $address->getAddressResidentialIndicator());
    }

    public function testFactoryHonorsExplicitNonResidential(): void
    {
        $factory = new Factory(new ArrayFormatter());
        $address = $factory->factory([
            'name'                          => 'Jane',
            'address_residential_indicator' => 'no',
        ]);

        self::assertSame(ResidentialIndicator::No, $address->getAddressResidentialIndicator());
        self::assertSame('no', $address->toArray()['address_residential_indicator']);
    }
}
