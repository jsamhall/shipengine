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

namespace jsamhall\ShipEngine\Carriers\UPS;

/**
 * Class UPS
 * @package jsamhall\ShipEngine\Carriers\USPS
 */
class UPS
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
    protected $account_country_code;

    /**
     * @var string
     */
    protected $account_postal_code;

    /**
     * @var string
     */
    protected $title;

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
    protected $company;

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
    protected $state;

    /**
     * @var string
     */
    protected $postal_code;

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
    protected $phone;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * @var bool
     */
    protected $agree_to_technology_agreement;

    /**
     * @param string $nickname
     * @return UPS
     */
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @param string $number
     * @return UPS
     */
    public function setAccountNumber(string $number): self
    {
        $this->account_number = $number;
        return $this;
    }

    /**
     * @param string $countryCode
     * @return UPS
     */
    public function setAccountCountryCode(string $countryCode): self
    {
        $this->account_country_code = $countryCode;
        return $this;
    }

    /**
     * @param string $postalCode
     * @return UPS
     */
    public function setAccountPostalCode(string $postalCode): self
    {
        $this->account_postal_code = $postalCode;
        return $this;
    }

    /**
     * @param string $title
     * @return UPS
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $firstName
     * @return UPS
     */
    public function setFirstName(string $firstName): self
    {
        $this->first_name = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     * @return UPS
     */
    public function setLastName(string $lastName): self
    {
        $this->last_name = $lastName;
        return $this;
    }

    /**
     * @param string $company
     * @return UPS
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param string $line1
     * @return UPS
     */
    public function setAddress1(string $line1): self
    {
        $this->address1 = $line1;
        return $this;
    }

    /**
     * @param string $line2
     * @return UPS
     */
    public function setAddress2(string $line2): self
    {
        $this->address2 = $line2;
        return $this;
    }

    /**
     * @param string $city
     * @return UPS
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $state
     * @return UPS
     */
    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $postalCode
     * @return UPS
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postal_code = $postalCode;
        return $this;
    }

    /**
     * @param string $countryCode
     * @return UPS
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->country_code = $countryCode;
        return $this;
    }

    /**
     * @param string $email
     * @return UPS
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $phone
     * @return UPS
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param Invoice $invoice
     * @return UPS
     */
    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
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
    public function getAccountCountryCode(): string
    {
        return $this->account_country_code;
    }

    /**
     * @return string
     */
    public function getAccountPostalCode(): string
    {
        return $this->account_postal_code;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
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
    public function getCompany(): string
    {
        return $this->company ?? '';
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
    public function getState(): string
    {
        return $this->state;
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
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return Invoice
     */
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'nickname'             => $this->getNickname(),
            'account_number'       => $this->getAccountNumber(),
            'account_country_code' => $this->getAccountCountryCode(),
            'account_postal_code'  => $this->getAccountPostalCode(),
            'title'                => $this->getTitle(),
            'first_name'           => $this->getFirstName(),
            'last_name'            => $this->getLastName(),
            'company'              => $this->getCompany(),
            'address1'             => $this->getAddress1(),
            'address2'             => $this->getAddress2(),
            'city'                 => $this->getCity(),
            'state'                => $this->getState(),
            'postal_code'          => $this->getPostalCode(),
            'country_code'         => $this->getCountryCode(),
            'email'                => $this->getEmail(),
            'phone'                => $this->getPhone(),
            'invoice'              => $this->getInvoice()->toArray(),
        ];
    }
}