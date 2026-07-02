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

namespace jsamhall\ShipEngine\Carriers\FedEx;

use jsamhall\ShipEngine\Carriers\ServiceCode as ServiceCodeInterface;
use jsamhall\ShipEngine\Carriers\ServiceCodeComparison;

/**
 * FedEx service codes in ShipEngine.
 */
enum ServiceCode: string implements ServiceCodeInterface
{
    use ServiceCodeComparison;

    case Ground = 'fedex_ground';
    case HomeDelivery = 'fedex_home_delivery';
    case TwoDay = 'fedex_2day';
    case TwoDayAm = 'fedex_2day_am';
    case ExpressSaver = 'fedex_express_saver';
    case StandardOvernight = 'fedex_standard_overnight';
    case PriorityOvernight = 'fedex_priority_overnight';
    case FirstOvernight = 'fedex_first_overnight';
    case InternationalGround = 'fedex_ground_international';
    case InternationalEconomy = 'fedex_international_economy';
    case InternationalPriority = 'fedex_international_priority';
    case InternationalFirst = 'fedex_international_first';
    case FreightOneDay = 'fedex_1_day_freight';
    case FreightTwoDay = 'fedex_2_day_freight';
    case FreightThreeDay = 'fedex_3_day_freight';
    case FreightFirstOvernight = 'fedex_first_overnight_freight';
    case FreightInternationalEconomy = 'fedex_international_economy_freight';
    case FreightInternationalPriority = 'fedex_international_priority_freight';

    public function code(): string
    {
        return $this->value;
    }
}
