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

namespace jsamhall\ShipEngine\Address;

use jsamhall\ShipEngine\Exception;

class Factory
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * Factory constructor.
     *
     * @param FormatterInterface $addressFormatter
     */
    public function __construct(FormatterInterface $addressFormatter)
    {
        $this->formatter = $addressFormatter;
    }

    /**
     * Builds a ShipEngine address from the provided addressData
     *
     * @param mixed $address Address as expected by the Formatter implementation
     * @return Address ShipEngine Address DTO
     */
    public function factory($address)
    {
        $formatted = $this->formatter->format($address);

        return (new Address)
            ->setName($formatted['name'] ?? '')
            ->setPhone($formatted['phone'] ?? '')
            ->setCompanyName($formatted['company_name'] ?? '')
            ->setAddressLine1($formatted['address_line1'] ?? '')
            ->setAddressLine2($formatted['address_line2'] ?? null)
            ->setAddressLine3($formatted['address_line3'] ?? null)
            ->setCityLocality($formatted['city_locality'] ?? '')
            ->setStateProvince($formatted['state_province'] ?? '')
            ->setPostalCode($formatted['postal_code'] ?? '')
            ->setCountryCode($formatted['country_code'] ?? '')
            ->setAddressResidentialIndicator($formatted['address_residential_indicator'] ?? 'unknown');
    }

    public function getAddressData($addresses): array
    {
        return array_map(function ($address) {
            return $address instanceof Address
                ? $address->toArray()
                : $this->factory($address)->toArray();
        }, $addresses);
    }
}
