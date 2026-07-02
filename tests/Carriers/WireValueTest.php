<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\Carriers;

use jsamhall\ShipEngine\Carriers\DeliveryConfirmation;
use jsamhall\ShipEngine\Carriers\FedEx;
use jsamhall\ShipEngine\Carriers\UPS;
use jsamhall\ShipEngine\Carriers\USPS;
use jsamhall\ShipEngine\Labels\InsuranceProvider;
use jsamhall\ShipEngine\Tracking\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test: pins the exact string "wire values" that are sent to / received from the
 * ShipEngine API. These MUST be preserved when the corresponding classes are converted to backed
 * enums in v2.0 (the enum `case` values have to equal these strings).
 */
class WireValueTest extends TestCase
{
    public function testFedExServiceCodeValues(): void
    {
        $expected = [
            'ground'                      => 'fedex_ground',
            'homeDelivery'                => 'fedex_home_delivery',
            'twoDay'                      => 'fedex_2day',
            'twoDayAm'                    => 'fedex_2day_am',
            'expressSaver'                => 'fedex_express_saver',
            'standardOvernight'           => 'fedex_standard_overnight',
            'priorityOvernight'           => 'fedex_priority_overnight',
            'firstOvernight'              => 'fedex_first_overnight',
            'internationalGround'         => 'fedex_ground_international',
            'internationalEconomy'        => 'fedex_international_economy',
            'internationalPriority'       => 'fedex_international_priority',
            'internationalFirst'          => 'fedex_international_first',
            'freightOneDay'               => 'fedex_1_day_freight',
            'freightTwoDay'               => 'fedex_2_day_freight',
            'freightThreeDay'             => 'fedex_3_day_freight',
            'freightFirstOvernight'       => 'fedex_first_overnight_freight',
            'freightInternationalEconomy' => 'fedex_international_economy_freight',
            'freightInternationalPriority'=> 'fedex_international_priority_freight',
        ];

        foreach ($expected as $factory => $wireValue) {
            self::assertSame($wireValue, (string) FedEx\ServiceCode::$factory());
        }
    }

    public function testUpsServiceCodeValues(): void
    {
        $expected = [
            'ground'                     => 'ups_ground',
            'groundInternational'        => 'ups_ground_international',
            'standardInternational'      => 'ups_standard_international',
            'worldwideExpress'           => 'ups_worldwide_express',
            'worldwideExpressPlus'       => 'ups_worldwide_express_plus',
            'worldwideExpedited'         => 'ups_worldwide_expedited',
            'worldwideSaver'             => 'ups_worldwide_saver',
            'nextDayAir'                 => 'ups_next_day_air',
            'nextDayAirEarlyAm'          => 'ups_next_day_air_early_am',
            'nextDayAirSaver'            => 'ups_next_day_air_saver',
            'nextDayAirInternational'    => 'ups_next_day_air_international',
            'secondDayAir'               => 'ups_2nd_day_air',
            'secondDayAirAm'             => 'ups_2nd_day_air_am',
            'secondDayAirInternational'  => 'ups_2nd_day_air_international',
            'threeDaySelect'             => 'ups_3_day_select',
        ];

        foreach ($expected as $factory => $wireValue) {
            self::assertSame($wireValue, (string) UPS\ServiceCode::$factory());
        }
    }

    public function testUspsServiceCodeValues(): void
    {
        $expected = [
            'mediaMail'                        => 'usps_media_mail',
            'parcelSelect'                     => 'usps_parcel_select',
            'firstClassMail'                   => 'usps_first_class_mail',
            'firstClassMailInternational'      => 'usps_first_class_mail_international',
            'priorityMail'                     => 'usps_priority_mail',
            'priorityMailInternational'        => 'usps_priority_mail_international',
            'priorityMailExpress'              => 'usps_priority_mail_express',
            'priorityMailExpressInternational' => 'usps_priority_mail_express_international',
        ];

        foreach ($expected as $factory => $wireValue) {
            self::assertSame($wireValue, (string) USPS\ServiceCode::$factory());
        }
    }

    public function testDeliveryConfirmationValues(): void
    {
        self::assertSame('delivery', (string) DeliveryConfirmation::delivery());
        self::assertSame('signature', (string) DeliveryConfirmation::signature());
        self::assertSame('adult_signature', (string) DeliveryConfirmation::adultSignature());
        self::assertSame('direct_signature', (string) DeliveryConfirmation::directSignature());
    }

    public function testInsuranceProviderValues(): void
    {
        self::assertSame('none', (string) InsuranceProvider::none());
        self::assertSame('shipsurance', (string) InsuranceProvider::shipsurance());
        self::assertSame('carrier', (string) InsuranceProvider::carrier());
        self::assertSame('third_party', (string) InsuranceProvider::thirdParty());
    }

    public function testTrackingStatusCodeValues(): void
    {
        self::assertSame('AC', StatusCode::ACCEPTED);
        self::assertSame('IT', StatusCode::IN_TRANSIT);
        self::assertSame('DE', StatusCode::DELIVERED);
        self::assertSame('EX', StatusCode::EXCEPTION);
        self::assertSame('UN', StatusCode::UNKNOWN);
        self::assertSame('AT', StatusCode::ATTEMPTED_DELIVERY);
        self::assertSame('NY', StatusCode::NOT_YET_IN_SYSTEM);
        self::assertSame('SP', StatusCode::DELIVERED_TO_COLLECTION_LOCATION);

        self::assertSame('Delivered', StatusCode::describe(StatusCode::DELIVERED));
        self::assertSame('Unknown', StatusCode::describe('not-a-real-code'));
    }
}
