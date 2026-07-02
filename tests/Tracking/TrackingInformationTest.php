<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Tracking;

use jsamhall\ShipEngine\Tracking\Event;
use jsamhall\ShipEngine\Tracking\StatusCode;
use jsamhall\ShipEngine\Tracking\TrackingInformation;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for TrackingInformation hydration and status helpers.
 */
class TrackingInformationTest extends TestCase
{
    private function fixture(): array
    {
        return [
            'tracking_number'   => '1Z999',
            'tracking_url'      => 'https://example.test/track/1Z999',
            'status_code'       => StatusCode::Delivered->value,
            'status_description'=> 'Delivered',
            'carrier_code'      => 'ups',
            'carrier_id'        => 'se-carrier-1',
            'ship_date'         => '2026-06-01T00:00:00Z',
            'estimated_delivery_date' => '2026-06-04T00:00:00Z',
            'actual_delivery_date'    => '2026-06-03T14:22:00Z',
            'events'            => [
                ['occurred_at' => '2026-06-01T09:00:00Z', 'description' => 'Accepted', 'city_locality' => 'Austin'],
                ['occurred_at' => '2026-06-03T14:22:00Z', 'description' => 'Delivered', 'city_locality' => 'Dallas'],
            ],
        ];
    }

    public function testHydratesScalarsAndValueObjects(): void
    {
        $tracking = new TrackingInformation($this->fixture());

        self::assertSame('1Z999', $tracking->getTrackingNumber());
        self::assertSame('https://example.test/track/1Z999', $tracking->getTrackingUrl());
        self::assertSame('DE', $tracking->getStatusCode());
        self::assertSame(StatusCode::Delivered, $tracking->getStatus());
        self::assertSame('ups', (string) $tracking->getCarrierCode());
        self::assertSame('se-carrier-1', (string) $tracking->getCarrierId());
        self::assertInstanceOf(\DateTime::class, $tracking->getActualDeliveryDate());
    }

    public function testStatusHelpers(): void
    {
        $tracking = new TrackingInformation($this->fixture());

        self::assertTrue($tracking->isDelivered());
        self::assertFalse($tracking->hasException());
        self::assertFalse($tracking->isInTransit());
    }

    public function testEventsAndLatestEvent(): void
    {
        $tracking = new TrackingInformation($this->fixture());

        self::assertCount(2, $tracking->getEvents());
        self::assertContainsOnlyInstancesOf(Event::class, $tracking->getEvents());

        $latest = $tracking->getLatestEvent();
        self::assertInstanceOf(Event::class, $latest);
        self::assertSame('Delivered', $latest->getDescription());
        self::assertSame('Dallas', $latest->getCityLocality());
    }

    public function testMissingOptionalFieldsBecomeNull(): void
    {
        $tracking = new TrackingInformation([
            'tracking_number' => '1Z000',
            'status_code'     => StatusCode::InTransit->value,
            'carrier_code'    => 'ups',
        ]);

        self::assertNull($tracking->getTrackingUrl());
        self::assertNull($tracking->getCarrierId());
        self::assertNull($tracking->getActualDeliveryDate());
        self::assertNull($tracking->getLatestEvent());
        self::assertSame([], $tracking->getEvents());
    }
}
