<?php

declare(strict_types=1);

/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\AddressVerification;

/**
 * Outcome of a ShipEngine address validation request.
 */
enum VerificationStatus: string
{
    case Verified = 'verified';
    case Unverified = 'unverified';
    case Error = 'error';
    case Warning = 'warning';

    /**
     * A human-readable description of the verification status.
     */
    public function message(): string
    {
        return match ($this) {
            self::Verified   => 'Address was successfully verified.',
            self::Unverified => 'Address validation was not validated against the database because pre-validation failed.',
            self::Warning    => 'The address was validated, but the address should be double checked.',
            self::Error      => 'The address could not be validated with any degree of certainty against the database.',
        };
    }
}
