<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Carriers;

use jsamhall\ShipEngine\Carriers\CustomServiceCode;
use jsamhall\ShipEngine\Carriers\DeliveryConfirmation;
use jsamhall\ShipEngine\Carriers\FedEx;
use jsamhall\ShipEngine\Carriers\UPS;
use jsamhall\ShipEngine\Carriers\USPS;
use jsamhall\ShipEngine\Labels\InsuranceProvider;
use jsamhall\ShipEngine\Tracking\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test: pins the exact string "wire values" that are sent to / received from the
 * ShipEngine API. Asserting against a full [caseName => value] map also fails if a case is added,
 * removed, or renamed without updating the expectations.
 */
class WireValueTest extends TestCase
{
    /**
     * @param \BackedEnum[] $cases
     * @return array<string, string>
     */
    private function casesMap(array $cases): array
    {
        $map = [];
        foreach ($cases as $case) {
            $map[$case->name] = $case->value;
        }

        return $map;
    }

    public function testFedExServiceCodeValues(): void
    {
        self::assertSame([
            'Ground'                       => 'fedex_ground',
            'HomeDelivery'                 => 'fedex_home_delivery',
            'TwoDay'                       => 'fedex_2day',
            'TwoDayAm'                     => 'fedex_2day_am',
            'ExpressSaver'                 => 'fedex_express_saver',
            'StandardOvernight'            => 'fedex_standard_overnight',
            'PriorityOvernight'            => 'fedex_priority_overnight',
            'FirstOvernight'               => 'fedex_first_overnight',
            'InternationalGround'          => 'fedex_ground_international',
            'InternationalEconomy'         => 'fedex_international_economy',
            'InternationalPriority'        => 'fedex_international_priority',
            'InternationalFirst'           => 'fedex_international_first',
            'FreightOneDay'                => 'fedex_1_day_freight',
            'FreightTwoDay'                => 'fedex_2_day_freight',
            'FreightThreeDay'              => 'fedex_3_day_freight',
            'FreightFirstOvernight'        => 'fedex_first_overnight_freight',
            'FreightInternationalEconomy'  => 'fedex_international_economy_freight',
            'FreightInternationalPriority' => 'fedex_international_priority_freight',
        ], $this->casesMap(FedEx\ServiceCode::cases()));
    }

    public function testUpsServiceCodeValues(): void
    {
        self::assertSame([
            'Ground'                    => 'ups_ground',
            'GroundInternational'       => 'ups_ground_international',
            'StandardInternational'     => 'ups_standard_international',
            'WorldwideExpress'          => 'ups_worldwide_express',
            'WorldwideExpressPlus'      => 'ups_worldwide_express_plus',
            'WorldwideExpedited'        => 'ups_worldwide_expedited',
            'WorldwideSaver'            => 'ups_worldwide_saver',
            'NextDayAir'                => 'ups_next_day_air',
            'NextDayAirEarlyAm'         => 'ups_next_day_air_early_am',
            'NextDayAirSaver'           => 'ups_next_day_air_saver',
            'NextDayAirInternational'   => 'ups_next_day_air_international',
            'SecondDayAir'              => 'ups_2nd_day_air',
            'SecondDayAirAm'            => 'ups_2nd_day_air_am',
            'SecondDayAirInternational' => 'ups_2nd_day_air_international',
            'ThreeDaySelect'            => 'ups_3_day_select',
        ], $this->casesMap(UPS\ServiceCode::cases()));
    }

    public function testUspsServiceCodeValues(): void
    {
        self::assertSame([
            'MediaMail'                        => 'usps_media_mail',
            'ParcelSelect'                     => 'usps_parcel_select',
            'FirstClassMail'                   => 'usps_first_class_mail',
            'FirstClassMailInternational'      => 'usps_first_class_mail_international',
            'PriorityMail'                     => 'usps_priority_mail',
            'PriorityMailInternational'        => 'usps_priority_mail_international',
            'PriorityMailExpress'              => 'usps_priority_mail_express',
            'PriorityMailExpressInternational' => 'usps_priority_mail_express_international',
        ], $this->casesMap(USPS\ServiceCode::cases()));
    }

    public function testServiceCodeInterfaceIsSharedAcrossEnumAndCustom(): void
    {
        self::assertSame('ups_ground', UPS\ServiceCode::Ground->code());

        // An enum case and a CustomServiceCode with the same wire value compare equal, both ways.
        $custom = new CustomServiceCode('ups_ground');
        self::assertTrue(UPS\ServiceCode::Ground->equals($custom));
        self::assertTrue($custom->equals(UPS\ServiceCode::Ground));
        self::assertFalse(UPS\ServiceCode::Ground->equals(FedEx\ServiceCode::Ground));
    }

    public function testDeliveryConfirmationValues(): void
    {
        self::assertSame('delivery', DeliveryConfirmation::Delivery->value);
        self::assertSame('signature', DeliveryConfirmation::Signature->value);
        self::assertSame('adult_signature', DeliveryConfirmation::AdultSignature->value);
        self::assertSame('direct_signature', DeliveryConfirmation::DirectSignature->value);
    }

    public function testInsuranceProviderValues(): void
    {
        self::assertSame('none', InsuranceProvider::None->value);
        self::assertSame('shipsurance', InsuranceProvider::Shipsurance->value);
        self::assertSame('carrier', InsuranceProvider::Carrier->value);
        self::assertSame('third_party', InsuranceProvider::ThirdParty->value);
    }

    public function testTrackingStatusCodeValues(): void
    {
        self::assertSame('AC', StatusCode::Accepted->value);
        self::assertSame('IT', StatusCode::InTransit->value);
        self::assertSame('DE', StatusCode::Delivered->value);
        self::assertSame('EX', StatusCode::Exception->value);
        self::assertSame('UN', StatusCode::Unknown->value);
        self::assertSame('AT', StatusCode::AttemptedDelivery->value);
        self::assertSame('NY', StatusCode::NotYetInSystem->value);
        self::assertSame('SP', StatusCode::DeliveredToCollectionLocation->value);

        self::assertSame('Delivered', StatusCode::describe('DE'));
        self::assertSame('Unknown', StatusCode::describe('not-a-real-code'));
    }
}
