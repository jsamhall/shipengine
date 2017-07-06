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

/**
 * Class Dto
 *
 * @package jsamhall\ShipEngine\Address
 */
class AddressDto
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $companyName;

    /**
     * @var string
     */
    protected $addressLine1;

    /**
     * @var string|null
     */
    protected $addressLine2 = null;

    /**
     * @var string|null
     */
    protected $addressLine3 = null;

    /**
     * @var string
     */
    protected $cityLocality;

    /**
     * @var string
     */
    protected $stateProvince;

    /**
     * @var string
     */
    protected $postalCode;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $addressResidentialIndicator;

    /**
     * @param string $name
     * @return AddressDto
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param null|string $phone
     * @return AddressDto
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param null|string $companyName
     * @return AddressDto
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @param string $addressLine1
     * @return AddressDto
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * @param null|string $addressLine2
     * @return AddressDto
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    /**
     * @param null|string $addressLine3
     * @return AddressDto
     */
    public function setAddressLine3($addressLine3)
    {
        $this->addressLine3 = $addressLine3;
        return $this;
    }

    /**
     * @param string $cityLocality
     * @return AddressDto
     */
    public function setCityLocality($cityLocality)
    {
        $this->cityLocality = $cityLocality;
        return $this;
    }

    /**
     * @param string $stateProvince
     * @return AddressDto
     */
    public function setStateProvince($stateProvince)
    {
        $this->stateProvince = $stateProvince;
        return $this;
    }

    /**
     * @param string $postalCode
     * @return AddressDto
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @param string $countryCode
     * @return AddressDto
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @param string $addressResidentialIndicator
     * @return AddressDto
     */
    public function setAddressResidentialIndicator($addressResidentialIndicator)
    {
        $this->addressResidentialIndicator = $addressResidentialIndicator;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @return string|null
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @return string|null
     */
    public function getAddressLine3()
    {
        return $this->addressLine3;
    }

    /**
     * @return string
     */
    public function getCityLocality()
    {
        return $this->cityLocality;
    }

    /**
     * @return string
     */
    public function getStateProvince()
    {
        return $this->stateProvince;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getAddressResidentialIndicator()
    {
        return $this->addressResidentialIndicator;
    }

    /**
     * @return array
     */
    public function toArray()
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
            'country_code'   => $this->getCountryCode()
        ];
    }
}