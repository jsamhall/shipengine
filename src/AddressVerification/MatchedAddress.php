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

use jsamhall\ShipEngine\Address\Address as Address;
use jsamhall\ShipEngine\Address\ResidentialIndicator;

class MatchedAddress extends Address
{
    /**
     * MatchedAddress constructor
     *
     * @param array $matchedAddress
     */
    public function __construct(array $matchedAddress)
    {
        $this->name = $matchedAddress['name'] ?? '';
        $this->phone = $matchedAddress['phone'] ?? null;
        $this->companyName = $matchedAddress['company_name'] ?? null;
        $this->addressLine1 = $matchedAddress['address_line1'] ?? '';
        $this->addressLine2 = $matchedAddress['address_line2'] ?? null;
        $this->addressLine3 = $matchedAddress['address_line3'] ?? null;
        $this->cityLocality = $matchedAddress['city_locality'] ?? '';
        $this->stateProvince = $matchedAddress['state_province'] ?? '';
        $this->postalCode = $matchedAddress['postal_code'] ?? '';
        $this->countryCode = $matchedAddress['country_code'] ?? '';
        $this->addressResidentialIndicator = ResidentialIndicator::tryFrom(
            (string) ($matchedAddress['address_residential_indicator'] ?? '')
        ) ?? ResidentialIndicator::Unknown;
    }
}
