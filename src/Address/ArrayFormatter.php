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

namespace jsamhall\ShipEngine\Address;


class ArrayFormatter implements FormatterInterface
{
    /** @inheritdoc */
    public function format($address)
    {
        return [
            'name'           => $address['name'] ?? '',
            'phone'          => $address['phone'] ?? '',
            'company_name'   => $address['company_name'] ?? '',
            'address_line1'  => $address['address_line1'] ?? '',
            'address_line2'  => $address['address_line2'] ?? '',
            'address_line3'  => $address['address_line3'] ?? '',
            'city_locality'  => $address['city_locality'] ?? '',
            'state_province' => $address['state_province'] ?? '',
            'postal_code'    => $address['postal_code'] ?? '',
            'country_code'   => $address['country_code'] ?? '',
            'address_residential_indicator' => $address['address_residential_indicator'] ?? 'unknown',
        ];
    }
}
