<?php

declare(strict_types=1);

/**
 * ShipEngine API Wrapper
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Carriers\UPS;

use jsamhall\ShipEngine\Carriers\ServiceCode as ServiceCodeInterface;
use jsamhall\ShipEngine\Carriers\ServiceCodeComparison;

/**
 * UPS service codes in ShipEngine.
 */
enum ServiceCode: string implements ServiceCodeInterface
{
    use ServiceCodeComparison;

    case Ground = 'ups_ground';
    case GroundInternational = 'ups_ground_international';
    case StandardInternational = 'ups_standard_international';
    case WorldwideExpress = 'ups_worldwide_express';
    case WorldwideExpressPlus = 'ups_worldwide_express_plus';
    case WorldwideExpedited = 'ups_worldwide_expedited';
    case WorldwideSaver = 'ups_worldwide_saver';
    case NextDayAir = 'ups_next_day_air';
    case NextDayAirEarlyAm = 'ups_next_day_air_early_am';
    case NextDayAirSaver = 'ups_next_day_air_saver';
    case NextDayAirInternational = 'ups_next_day_air_international';
    case SecondDayAir = 'ups_2nd_day_air';
    case SecondDayAirAm = 'ups_2nd_day_air_am';
    case SecondDayAirInternational = 'ups_2nd_day_air_international';
    case ThreeDaySelect = 'ups_3_day_select';

    public function code(): string
    {
        return $this->value;
    }
}
