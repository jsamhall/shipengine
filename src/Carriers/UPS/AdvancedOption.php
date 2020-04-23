<?php
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

use jsamhall\ShipEngine;

/**
 * Class AdvancedOption
 *
 * Named constructors for all "advanced options" offered by UPS
 *
 * @package jsamhall\ShipEngine\Carriers\FedEx
 */
class AdvancedOption extends ShipEngine\Carriers\AdvancedOption
{
    public static function billToAccount(string $account)
    {
        return new static('bill_to_account', $account);
    }

    public static function billToParty(string $party)
    {
        return new static('bill_to_party', $party);
    }

    public static function containsAlcohol()
    {
        return new static('contains_alcohol', true);
    }

    public static function deliveredDutyPaid()
    {
        return new static('delivered_duty_paid', true);
    }

    public static function nonMachinable()
    {
        return new static('non_machinable', true);
    }

    public static function saturdayDelivery()
    {
        return new static('saturday_delivery', true);
    }
}
