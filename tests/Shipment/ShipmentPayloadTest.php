<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Shipment;

use jsamhall\ShipEngine\Address\Address;
use jsamhall\ShipEngine\Carriers\DeliveryConfirmation;
use jsamhall\ShipEngine\Carriers\UPS;
use jsamhall\ShipEngine\Labels;
use jsamhall\ShipEngine\Rating;
use jsamhall\ShipEngine\Shipment\Package;
use jsamhall\ShipEngine\Shipment\Package\Dimensions;
use jsamhall\ShipEngine\Shipment\Package\Weight;
use jsamhall\ShipEngine\Shipment\Package\WeightUnit;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for the toArray() request-payload builders in AbstractShipment and its
 * Rating/Labels subclasses. This is the most behavior-sensitive part of the library — the exact
 * shape of these arrays is the API contract, so it must be preserved through the refactor.
 */
class ShipmentPayloadTest extends TestCase
{
    private function address(string $name): Address
    {
        return (new Address())
            ->setName($name)
            ->setPhone('555-0100')
            ->setCompanyName('Acme')
            ->setAddressLine1('123 Main St')
            ->setCityLocality('Austin')
            ->setStateProvince('TX')
            ->setPostalCode('78701')
            ->setCountryCode('US');
    }

    private function package(): Package
    {
        return new Package(new Weight(16, WeightUnit::Ounce), new Dimensions(12, 8, 4));
    }

    public function testRatingShipmentPayloadShape(): void
    {
        $shipment = new Rating\Shipment(
            $this->address('Ship To'),
            $this->address('Ship From'),
            [$this->package()]
        );

        $payload = $shipment->toArray();

        self::assertSame('Ship To', $payload['ship_to']['name']);
        self::assertSame('Ship From', $payload['ship_from']['name']);
        self::assertSame('yes', $payload['ship_to']['address_residential_indicator']);
        self::assertSame('none', $payload['insurance_provider']);
        self::assertSame(['value' => 16, 'unit' => 'ounce'], $payload['total_weight']);
        self::assertNull($payload['advanced_options']);
        self::assertSame('no_validation', $payload['validate_address']);

        self::assertCount(1, $payload['packages']);
        self::assertSame(['value' => 16, 'unit' => 'ounce'], $payload['packages'][0]['weight']);
        self::assertSame(
            ['unit' => 'inch', 'length' => 12, 'width' => 8, 'height' => 4],
            $payload['packages'][0]['dimensions']
        );

        // No delivery confirmation set => key absent
        self::assertArrayNotHasKey('confirmation', $payload);
    }

    public function testAdvancedOptionsAndConfirmationAreIncluded(): void
    {
        $shipment = new Rating\Shipment(
            $this->address('Ship To'),
            $this->address('Ship From'),
            [$this->package()],
            null,
            DeliveryConfirmation::Signature
        );
        $shipment->addAdvancedOption(UPS\AdvancedOption::saturdayDelivery());

        $payload = $shipment->toArray();

        self::assertSame(['saturday_delivery' => true], $payload['advanced_options']);
        self::assertSame('signature', $payload['confirmation']);
    }

    public function testLabelsShipmentAddsServiceCodeAndCarrierId(): void
    {
        $shipment = new Labels\Shipment(
            UPS\ServiceCode::ground(),
            $this->address('Ship To'),
            $this->address('Ship From'),
            [$this->package()]
        );
        $shipment->specifyCarrierId(new \jsamhall\ShipEngine\Carriers\CarrierId('se-carrier-1'));

        $payload = $shipment->toArray();

        self::assertSame('ups_ground', $payload['service_code']);
        self::assertSame('se-carrier-1', $payload['carrier_id']);
        self::assertSame('none', $payload['insurance_provider']);
    }

    public function testLabelMessagesAppearInPackagePayload(): void
    {
        $package = $this->package();
        $package->addLabelMessage('Handle with care');

        $shipment = new Rating\Shipment(
            $this->address('Ship To'),
            $this->address('Ship From'),
            [$package]
        );

        $payload = $shipment->toArray();

        self::assertSame(['reference1' => 'Handle with care'], $payload['packages'][0]['label_messages']);
    }
}
