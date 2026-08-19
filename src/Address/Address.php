<?php

declare(strict_types=1);

/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @package jsamhall\ShipEngine\Address
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Address;

class Address
{
    protected ?string $name = null;

    protected ?string $phone = null;

    protected ?string $companyName = null;

    protected ?string $addressLine1 = null;

    protected ?string $addressLine2 = null;

    protected ?string $addressLine3 = null;

    protected ?string $cityLocality = null;

    protected ?string $stateProvince = null;

    protected ?string $postalCode = null;

    protected ?string $countryCode = null;

    /**
     * Whether the address is residential. Defaults to residential; callers must explicitly declare
     * an address non-residential.
     */
    protected ResidentialIndicator $addressResidentialIndicator = ResidentialIndicator::Yes;

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function setAddressLine1(?string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    public function setAddressLine3(?string $addressLine3): self
    {
        $this->addressLine3 = $addressLine3;
        return $this;
    }

    public function setCityLocality(?string $cityLocality): self
    {
        $this->cityLocality = $cityLocality;
        return $this;
    }

    public function setStateProvince(?string $stateProvince): self
    {
        $this->stateProvince = $stateProvince;
        return $this;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function setAddressResidentialIndicator(ResidentialIndicator $addressResidentialIndicator): self
    {
        $this->addressResidentialIndicator = $addressResidentialIndicator;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function getAddressLine3(): ?string
    {
        return $this->addressLine3;
    }

    public function getCityLocality(): ?string
    {
        return $this->cityLocality;
    }

    public function getStateProvince(): ?string
    {
        return $this->stateProvince;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getAddressResidentialIndicator(): ResidentialIndicator
    {
        return $this->addressResidentialIndicator;
    }

    public function toArray(): array
    {
        return [
            'name'           => $this->getName(),
            'phone'          => $this->getPhone(),
            'company_name'   => $this->getCompanyName(),
            'address_line1'  => $this->getAddressLine1(),
            'address_line2'  => $this->getAddressLine2(),
            'address_line3'  => $this->getAddressLine3(),
            'city_locality'  => $this->getCityLocality(),
            'state_province' => $this->getStateProvince(),
            'postal_code'    => $this->getPostalCode(),
            'country_code'   => $this->getCountryCode(),
            'address_residential_indicator' => $this->getAddressResidentialIndicator()->value
        ];
    }
}
