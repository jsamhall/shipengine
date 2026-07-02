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

namespace jsamhall\ShipEngine\Carriers;

/**
 * Known ShipEngine advanced-option codes.
 *
 * @link https://docs.shipengine.com/docs/specify-advanced-options
 */
enum AdvancedOptionCode: string
{
    case ContainsAlcohol = 'contains_alcohol';
    case DeliveredDutyPaid = 'delivered_duty_paid';
    case NonMachinable = 'non_machinable';
    case SaturdayDelivery = 'saturday_delivery';
    case BillToAccount = 'bill_to_account';
    case BillToParty = 'bill_to_party';
}
