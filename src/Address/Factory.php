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

namespace jsamhall\ShipEngine\Address;

class Factory
{
    public function __construct(protected readonly FormatterInterface $formatter)
    {
    }

    /**
     * Builds a ShipEngine address from the provided addressData
     *
     * @param mixed $address Address as expected by the Formatter implementation
     * @return Address ShipEngine Address DTO
     */
    public function factory($address): Address
    {
        $formatted = $this->formatter->format($address);

        $shipEngineAddress = (new Address)
            ->setName($formatted['name'] ?? '')
            ->setPhone($formatted['phone'] ?? '')
            ->setCompanyName($formatted['company_name'] ?? '')
            ->setAddressLine1($formatted['address_line1'] ?? '')
            ->setAddressLine2($formatted['address_line2'] ?? null)
            ->setAddressLine3($formatted['address_line3'] ?? null)
            ->setCityLocality($formatted['city_locality'] ?? '')
            ->setStateProvince($formatted['state_province'] ?? '')
            ->setPostalCode($formatted['postal_code'] ?? '')
            ->setCountryCode($formatted['country_code'] ?? '');

        // Only override the default (residential) when the formatter explicitly provides a value.
        $indicator = ResidentialIndicator::tryFrom((string) ($formatted['address_residential_indicator'] ?? ''));
        if ($indicator !== null) {
            $shipEngineAddress->setAddressResidentialIndicator($indicator);
        }

        return $shipEngineAddress;
    }
}
