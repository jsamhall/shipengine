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
        $this->name = $matchedAddress['name'];
        $this->phone = $matchedAddress['phone'];
        $this->email = $matchedAddress['email'];
        $this->companyName = $matchedAddress['company_name'];
        $this->addressLine1 = $matchedAddress['address_line1'];
        $this->addressLine2 = $matchedAddress['address_line2'];
        $this->addressLine3 = $matchedAddress['address_line3'];
        $this->cityLocality = $matchedAddress['city_locality'];
        $this->stateProvince = $matchedAddress['state_province'];
        $this->postalCode = $matchedAddress['postal_code'];
        $this->countryCode = $matchedAddress['country_code'];
        $this->addressResidentialIndicator = $matchedAddress['address_residential_indicator'];
    }
}
