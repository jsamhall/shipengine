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

namespace jsamhall\ShipEngine\Rating;

/**
 * Address validation modes for a rating request.
 *
 * @link https://docs.shipengine.com/docs/address-validation
 */
enum AddressValidation: string
{
    case NoValidation = 'no_validation';
    case ValidateOnly = 'validate_only';
    case ValidateAndClean = 'validate_and_clean';
}
