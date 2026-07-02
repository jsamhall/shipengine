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
 * Delivery confirmation options for a shipment.
 *
 * @link https://docs.shipengine.com/docs/request-delivery-confirmation
 */
enum DeliveryConfirmation: string
{
    case Delivery = 'delivery';
    case Signature = 'signature';
    case AdultSignature = 'adult_signature';
    case DirectSignature = 'direct_signature';
}
