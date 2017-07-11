<?php
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

class MatchedAddress extends Address
{
    /**
     * MatchedAddress constructor
     *
     * @param array $matchedAddress
     */
    public function __construct(array $matchedAddress)
    {
        list(
            $this->name,
            $this->phone,
            $this->companyName,
            $this->addressLine1,
            $this->addressLine2,
            $this->addressLine3,
            $this->cityLocality,
            $this->stateProvince,
            $this->postalCode,
            $this->countryCode,
            $this->addressResidentialIndicator
            ) = array_values($matchedAddress);
    }
}