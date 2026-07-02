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

use jsamhall\ShipEngine\Carriers\AdvancedOption as AdvancedOptionInterface;
use jsamhall\ShipEngine\Carriers\AdvancedOptionCode;
use jsamhall\ShipEngine\Carriers\CustomAdvancedOption;

/**
 * Named constructors for the "advanced options" offered by UPS.
 */
final class AdvancedOption
{
    public static function billToAccount(string $account): AdvancedOptionInterface
    {
        return new CustomAdvancedOption(AdvancedOptionCode::BillToAccount->value, $account);
    }

    public static function billToParty(string $party): AdvancedOptionInterface
    {
        return new CustomAdvancedOption(AdvancedOptionCode::BillToParty->value, $party);
    }

    public static function containsAlcohol(): AdvancedOptionInterface
    {
        return new CustomAdvancedOption(AdvancedOptionCode::ContainsAlcohol->value, true);
    }

    public static function deliveredDutyPaid(): AdvancedOptionInterface
    {
        return new CustomAdvancedOption(AdvancedOptionCode::DeliveredDutyPaid->value, true);
    }

    public static function nonMachinable(): AdvancedOptionInterface
    {
        return new CustomAdvancedOption(AdvancedOptionCode::NonMachinable->value, true);
    }

    public static function saturdayDelivery(): AdvancedOptionInterface
    {
        return new CustomAdvancedOption(AdvancedOptionCode::SaturdayDelivery->value, true);
    }
}
