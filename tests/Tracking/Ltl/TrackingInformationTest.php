<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Tracking\Ltl;

use jsamhall\ShipEngine\Tracking\Ltl\Event;
use jsamhall\ShipEngine\Tracking\Ltl\TrackingInformation;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for LTL tracking hydration, using the example response from
 * https://www.shipengine.com/docs/ltl/tracking/ (note the empty-string delivery dates).
 */
class TrackingInformationTest extends TestCase
{
    private function fixture(): array
    {
        return [
            'pro_number' => '123456789',
            'ship_from'  => ['address' => ['postal_code' => '30250']],
            'ship_to'    => ['address' => ['postal_code' => '36801']],
            'pickup'     => ['date' => '2021-05-31'],
            'delivery'   => [
                'estimated' => ['date' => '', 'time' => ''],
                'actual'    => ['date' => '', 'time' => ''],
                'signature' => ['name' => 'MARK PITTS'],
            ],
            'events' => [
                [
                    'status'   => 'delivered',
                    'date'     => '2021-06-01',
                    'time'     => '17:55:00',
                    'location' => ['city_locality' => 'Regina', 'state_province' => 'SK'],
                    'carrier'  => ['description' => 'Delivered 06/01/2021 12:27 PM;Recvd by: MARK PITTS'],
                ],
            ],
        ];
    }

    public function testHydratesTopLevelFields(): void
    {
        $tracking = new TrackingInformation($this->fixture());

        self::assertSame('123456789', $tracking->getProNumber());
        self::assertSame('30250', $tracking->getShipFromPostalCode());
        self::assertSame('36801', $tracking->getShipToPostalCode());
        self::assertSame('MARK PITTS', $tracking->getSignatureName());
        self::assertSame('2021-05-31', $tracking->getPickupDate()->format('Y-m-d'));
    }

    public function testEmptyDeliveryDatesBecomeNull(): void
    {
        $tracking = new TrackingInformation($this->fixture());

        self::assertNull($tracking->getEstimatedDeliveryDate());
        self::assertNull($tracking->getActualDeliveryDate());
    }

    public function testDeliveredIsInferredFromEventWhenActualDateMissing(): void
    {
        // The example has no actual delivery date but a "delivered" event + signature.
        $tracking = new TrackingInformation($this->fixture());

        self::assertTrue($tracking->isDelivered());
    }

    public function testEventHydration(): void
    {
        $tracking = new TrackingInformation($this->fixture());

        self::assertCount(1, $tracking->getEvents());
        self::assertContainsOnlyInstancesOf(Event::class, $tracking->getEvents());

        $event = $tracking->getLatestEvent();
        self::assertInstanceOf(Event::class, $event);
        self::assertSame('delivered', $event->getStatus());
        self::assertTrue($event->isDelivered());
        self::assertSame('2021-06-01 17:55:00', $event->getOccurredAt()->format('Y-m-d H:i:s'));
        self::assertSame('Regina', $event->getCityLocality());
        self::assertSame('SK', $event->getStateProvince());
        self::assertSame('Delivered 06/01/2021 12:27 PM;Recvd by: MARK PITTS', $event->getCarrierDescription());
    }

    public function testMinimalPayloadWithNoEvents(): void
    {
        $tracking = new TrackingInformation(['pro_number' => '999']);

        self::assertSame('999', $tracking->getProNumber());
        self::assertNull($tracking->getShipFromPostalCode());
        self::assertNull($tracking->getPickupDate());
        self::assertNull($tracking->getSignatureName());
        self::assertSame([], $tracking->getEvents());
        self::assertNull($tracking->getLatestEvent());
        self::assertFalse($tracking->isDelivered());
    }
}
