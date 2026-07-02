<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Rating;

use jsamhall\ShipEngine\Rating\Rate;
use jsamhall\ShipEngine\Rating\RateResponse;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for RateResponse/Rate hydration from a ShipEngine `rate_response` payload.
 * Pins the current mapping so the type-modernization sweep (typed/promoted properties) can't
 * silently change how the response is parsed.
 */
class RateResponseTest extends TestCase
{
    private function fixture(): array
    {
        return [
            'rate_request_id' => 'se-req-1',
            'shipment_id'     => 'se-ship-1',
            'rates'           => [
                [
                    'rate_id'               => 'se-rate-1',
                    'carrier_id'            => 'se-carrier-1',
                    'carrier_code'          => 'ups',
                    'service_code'          => 'ups_ground',
                    'rate_type'             => 'shipment',
                    'carrier_nickname'      => 'My UPS',
                    'carrier_friendly_name' => 'UPS',
                    'shipping_amount'       => ['amount' => 10.50, 'currency' => 'usd'],
                    'insurance_amount'      => ['amount' => 0.0, 'currency' => 'usd'],
                    'confirmation_amount'   => ['amount' => 0.0, 'currency' => 'usd'],
                    'other_amount'          => ['amount' => 1.50, 'currency' => 'usd'],
                    'zone'                  => '5',
                    'package_type'          => 'package',
                    'delivery_days'         => 3,
                    'guaranteed_service'    => true,
                    'negotiated_rate'       => true,
                    'service_type'          => 'UPS Ground',
                    'trackable'             => true,
                ],
                [
                    'rate_id'               => 'se-rate-2',
                    'carrier_id'            => 'se-carrier-2',
                    'carrier_code'          => 'fedex',
                    'service_code'          => 'fedex_ground',
                    'shipping_amount'       => ['amount' => 12.00, 'currency' => 'usd'],
                    'insurance_amount'      => ['amount' => 0.0, 'currency' => 'usd'],
                    'confirmation_amount'   => ['amount' => 0.0, 'currency' => 'usd'],
                    'other_amount'          => ['amount' => 0.0, 'currency' => 'usd'],
                    'service_type'          => 'FedEx Ground',
                ],
            ],
            'invalid_rates'   => [],
            'errors'          => [],
        ];
    }

    public function testHydratesRatesAndIds(): void
    {
        $response = new RateResponse($this->fixture());

        self::assertCount(2, $response->getRates());
        self::assertContainsOnlyInstancesOf(Rate::class, $response->getRates());
        self::assertSame('se-req-1', (string) $response->getRequestId());
        self::assertSame('se-ship-1', (string) $response->getShipmentId());
    }

    public function testRateFieldMapping(): void
    {
        $response = new RateResponse($this->fixture());
        $rate = $response->getRateById('se-rate-1');

        self::assertInstanceOf(Rate::class, $rate);
        self::assertSame('ups_ground', (string) $rate->getServiceCode());
        self::assertSame('ups', (string) $rate->getCarrierCode());
        self::assertSame('UPS Ground', $rate->getServiceName());
        self::assertSame(10.50, $rate->getShippingAmount()->getAmount());
        self::assertSame('usd', $rate->getShippingAmount()->getCurrency());
        self::assertSame(12.00, $rate->getGrossAmount());
    }

    /**
     * Regression: `guaranteed_service` previously mapped to a non-existent property, so this getter
     * always returned null AND created a dynamic property (deprecated on PHP 8.2+). With
     * failOnDeprecation enabled, this test also fails if the dynamic-property write regresses.
     */
    public function testGuaranteedServiceAndNegotiatedRateHydrate(): void
    {
        $response = new RateResponse($this->fixture());
        $rate = $response->getRateById('se-rate-1');

        self::assertTrue($rate->isGuaranteedService());
        self::assertTrue($rate->hasNegotiatedRatesApplied());
        self::assertSame(3, $rate->getDeliveryDays());
    }

    public function testLookupHelpers(): void
    {
        $response = new RateResponse($this->fixture());

        self::assertSame('se-rate-1', (string) $response->getRateByServiceCode('ups_ground')->getId());
        self::assertFalse($response->getRateByServiceCode('does_not_exist'));
        self::assertCount(1, $response->getRatesByCarrierCode('fedex'));
        self::assertFalse($response->getRateById('nope'));
    }
}
