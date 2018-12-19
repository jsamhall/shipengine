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
 * Class Settings
 *
 * Support for UPS Advanced Functionality, but does not disable by default.
 *
 * @package jsamhall\ShipEngine\Carriers\UPS
 */
class Settings
{
    const PICKUP_TYPE_DAILY = 'daily_pickup';
    const PICKUP_TYPE_OCCASIONAL = 'occasional_pickup';
    const PICKUP_TYPE_CUSTOMER = 'customer_counter';

    const MAIL_ENDORSEMENT_NONE = 'none';
    const MAIL_ENDORSEMENT_RETURN = 'return_service_requested';
    const MAIL_ENDORSEMENT_FORWARD = 'forward_service_requested';
    const MAIL_ENDORSEMENT_ADDRESS = 'address_service_requested';
    const MAIL_ENDORSEMENT_CHANGE = 'change_service_requested';
    const MAIL_ENDORSEMENT_LEAVE = 'leave_if_no_response';

    /** @var string */
    protected $nickname;

    /** @var boolean */
    protected $is_primary_account;

    /** @var string */
    protected $pickup_type;

    /** @var boolean */
    protected $use_carbon_neutral_shipping_program;

    /**
     * @var boolean
     * @deprecated
     */
    protected $use_ground_freight_pricing;

    /** @var boolean */
    protected $use_negotiated_rates;

    /** @var string */
    protected $account_postal_code;

    /** @var Invoice */
    protected $invoice;

    /** @var boolean */
    protected $use_consolidation_services;

    /** @var boolean */
    protected $use_order_number_on_mail_innovations_labels;

    /** @var string */
    protected $mail_innovations_endorsement;

    /** @var string */
    protected $mail_innovations_cost_center;

    /**
     * @param string $nickname
     * @return Settings
     */
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @param bool $isPrimaryAccount
     * @return Settings
     */
    public function setIsPrimaryAccount(bool $isPrimaryAccount): self
    {
        $this->is_primary_account = $isPrimaryAccount;
        return $this;
    }

    /**
     * @param string $pickupType
     * @return Settings
     */
    public function setPickupType(string $pickupType): self
    {
        $allowed = [self::PICKUP_TYPE_DAILY, self::PICKUP_TYPE_OCCASIONAL, self::PICKUP_TYPE_CUSTOMER];

        if (! in_array($pickupType, $allowed)) {
            throw new \InvalidArgumentException($pickupType . ' is not a valid "pickup_type" property. See the constants "PICKUP_TYPE_*" in this class for allowed options.');
        }

        $this->pickup_type = $pickupType;
        return $this;
    }

    /**
     * @param bool $useCarbonNeutralShippingProgram
     * @return Settings
     */
    public function setUseCarbonNeutralShippingProgram(bool $useCarbonNeutralShippingProgram): self
    {
        $this->use_carbon_neutral_shipping_program = $useCarbonNeutralShippingProgram;
        return $this;
    }

    /**
     * @param bool $useGroundFreightPricing
     * @return Settings
     */
    public function setUseGroundFreightPricing(bool $useGroundFreightPricing): self
    {
        $this->use_ground_freight_pricing = $useGroundFreightPricing;
        return $this;
    }

    /**
     * @param bool $useNegotiatedRates
     * @return Settings
     */
    public function setUseNegotiatedRates(bool $useNegotiatedRates): self
    {
        $this->use_negotiated_rates = $useNegotiatedRates;
    }

    /**
     * @param string $accountPostalCode
     * @return Settings
     */
    public function setAccountPostalCode(string $accountPostalCode): self
    {
        $this->account_postal_code = $accountPostalCode;
        return $this;
    }

    /**
     * @param Invoice $invoice
     * @return Settings
     */
    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @param bool $useConsolidationServices
     * @return Settings
     */
    public function setUseConsolidationServices(bool $useConsolidationServices): self
    {
        $this->use_consolidation_services = $useConsolidationServices;
        return $this;
    }

    /**
     * @param bool $useOrderNumberOnMailInnovationsLabels
     * @return Settings
     */
    public function setUseOrderNumberOnMailInnovationsLabels(bool $useOrderNumberOnMailInnovationsLabels): self
    {
        $this->use_order_number_on_mail_innovations_labels = $useOrderNumberOnMailInnovationsLabels;
        return $this;
    }

    /**
     * @param string $mailInnovationsEndorsement
     * @return Settings
     */
    public function setMailInnovationsEndorsement(string $mailInnovationsEndorsement): self
    {
        $allowed = [
            self::MAIL_ENDORSEMENT_NONE, self::MAIL_ENDORSEMENT_RETURN, self::MAIL_ENDORSEMENT_FORWARD,
            self::MAIL_ENDORSEMENT_ADDRESS, self::MAIL_ENDORSEMENT_CHANGE, self::MAIL_ENDORSEMENT_LEAVE
        ];

        if (! in_array($mailInnovationsEndorsement, $allowed)) {
            throw new \InvalidArgumentException($mailInnovationsEndorsement . ' is not a valid "mail_innovations_endorsement" property. See the constants "MAIL_ENDORSEMENT_*" in this class for allowed options.');
        }

        $this->mail_innovations_endorsement = $mailInnovationsEndorsement;
        return $this;
    }

    /**
     * @param string $mailInnovationsCostCenter
     * @return Settings
     */
    public function setMailInnovationsCostCenter(string $mailInnovationsCostCenter): self
    {
        $this->mail_innovations_cost_center = $mailInnovationsCostCenter;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @return bool|null
     */
    public function isPrimaryAccount(): ?bool
    {
        return $this->is_primary_account;
    }

    /**
     * @return null|string
     */
    public function getPickupType(): ?string
    {
        return $this->pickup_type;
    }

    /**
     * @return bool|null
     */
    public function isUseCarbonNeutralShippingProgram(): ?bool
    {
        return $this->use_carbon_neutral_shipping_program;
    }

    /**
     * @return bool|null
     */
    public function isUseGroundFreightPricing(): ?bool
    {
        return $this->use_ground_freight_pricing;
    }

    /**
     * @return bool|null
     */
    public function isUseNegotiatedRates(): ?bool
    {
        return $this->use_negotiated_rates;
    }

    /**
     * @return null|string
     */
    public function getAccountPostalCode(): ?string
    {
        return $this->account_postal_code;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    /**
     * @return bool|null
     */
    public function isUseConsolidationServices(): ?bool
    {
        return $this->use_consolidation_services;
    }

    /**
     * @return bool|null
     */
    public function isUseOrderNumberOnMailInnovationsLabels(): ?bool
    {
        return $this->use_order_number_on_mail_innovations_labels;
    }

    /**
     * @return null|string
     */
    public function getMailInnovationsEndorsement(): ?string
    {
        return $this->mail_innovations_endorsement;
    }

    /**
     * @return null|string
     */
    public function getMailInnovationsCostCenter(): ?string
    {
        return $this->mail_innovations_cost_center;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
        ];
    }
}