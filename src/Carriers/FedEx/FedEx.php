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

namespace jsamhall\ShipEngine\Carriers\FedEx;

/**
 * Class FedEx
 * @package jsamhall\ShipEngine\Carriers\FedEx
 */
class FedEx
{
    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var string
     */
    protected $account_number;

    /**
     * @var string
     */
    protected $address1;

    /**
     * @var string
     */
    protected $address2;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $country_code;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $postal_code;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var bool
     */
    protected $agree_to_eula;

    /**
     * @var string
     */
    protected $meter_number;

    /**
     * @param string $nickname
     * @return FedEx
     */
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @param string $accountNumber
     * @return FedEx
     */
    public function setAccountNumber(string $accountNumber): self
    {
        $this->account_number = $accountNumber;
        return $this;
    }

    /**
     * @param string $address1
     * @return FedEx
     */
    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @param string $address2
     * @return FedEx
     */
    public function setAddress2(string $address2): self
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @param string $city
     * @return FedEx
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $company
     * @return FedEx
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param string $countryCode
     * @return FedEx
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->country_code = $countryCode;
        return $this;
    }

    /**
     * @param string $email
     * @return FedEx
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $firstName
     * @return FedEx
     */
    public function setFirstName(string $firstName): self
    {
        $this->first_name = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     * @return FedEx
     */
    public function setLastName(string $lastName): self
    {
        $this->last_name = $lastName;
        return $this;
    }

    /**
     * @param string $phone
     * @return FedEx
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $postalCode
     * @return FedEx
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postal_code = $postalCode;
        return $this;
    }

    /**
     * @param string $state
     * @return FedEx
     */
    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param bool $agreement
     * @return FedEx
     */
    public function setAgreeToEula(bool $agreement): self
    {
        $this->agree_to_eula = $agreement;
        return $this;
    }

    /**
     * @param string $meterNumber
     * @return FedEx
     */
    public function setMeterNumber(string $meterNumber): self
    {
        $this->meter_number = $meterNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->account_number;
    }

    /**
     * @return string
     */
    public function getAddress1(): string
    {
        return $this->address1;
    }

    /**
     * @return string
     */
    public function getAddress2(): string
    {
        return $this->address2 ?? '';
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company ?? '';
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return bool
     */
    public function getAgreeToEula(): bool
    {
        return $this->agree_to_eula;
    }

    /**
     * @return string
     */
    public function getMeterNumber(): string
    {
        return $this->meter_number;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'nickname'       => $this->getNickname(),
            'account_number' => $this->getAccountNumber(),
            'address1'       => $this->getAddress1(),
            'address2'       => $this->getAddress2(),
            'city'           => $this->getCity(),
            'company'        => $this->getCompany(),
            'country_code'   => $this->getCountryCode(),
            'email'          => $this->getEmail(),
            'first_name'     => $this->getFirstName(),
            'last_name'      => $this->getLastName(),
            'phone'          => $this->getPhone(),
            'postal_code'    => $this->getPostalCode(),
            'state'          => $this->getState(),
            'agree_to_eula'  => $this->getAgreeToEula(),
            'meter_number'   => $this->getMeterNumber(),
        ];
    }
}
