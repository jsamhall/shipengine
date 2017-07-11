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

namespace jsamhall\ShipEngine\Rating;

use jsamhall\ShipEngine;

class Rate
{
    /**
     * Id of this rate, used when creating a Label using this Rate
     *
     * @var RateId
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * The ShipEngine ID of the Carrier from which this rate was retrieved
     *
     * @var ShipEngine\Carriers\CarrierId
     */
    protected $carrierId;

    /**
     * The code used to identify the Carrier when only one account exists
     * within the ShipEngine platform. When multiple accounts exist, the
     * internal carrier_id must be used to reference the Carrier account
     *
     * @var ShipEngine\Carriers\CarrierCode
     */
    protected $carrierCode;

    /**
     * The nickname given to the Carrier by the ShipEngine account holder
     * e.g. My Company UPS
     *
     * @var string
     */
    protected $carrierNickname;

    /**
     * The carrier's "Friendly Name" as displayed in ShipEngine
     * e.g. UPS
     *
     * @var string
     */
    protected $carrierFriendlyName;

    /**
     * @var ShipEngine\ValueObject\Amount
     */
    protected $shippingAmount;

    /**
     * @var ShipEngine\ValueObject\Amount
     */
    protected $insuranceAmount;

    /**
     * @var ShipEngine\ValueObject\Amount
     */
    protected $confirmationAmount;

    /**
     * @var ShipEngine\ValueObject\Amount
     */
    protected $otherAmount;

    /**
     * @var string
     */
    protected $zone;

    /**
     * @var string
     */
    protected $packageType;

    /**
     * Number of days until Delivery
     *
     * @var int
     */
    protected $deliveryDays;

    /**
     * Is the service guaranteed?
     *
     * @var bool
     */
    protected $guaranteedService;

    /**
     * @var \DateTime
     */
    protected $estimatedDeliveryDate;

    /**
     * A delivery date message as provided by the Carrier
     * e.g. "Monday 7/17 by 11:00 PM"
     *
     * @var string
     */
    protected $carrierDeliveryDays;

    /**
     * The pickup date for the Shipment
     *
     * @var \DateTime
     */
    protected $shipDate;

    /**
     * Are negotiated rates applied to this Rate? (carrier specific)
     *
     * @var boolean
     */
    protected $negotiatedRatesApplied;

    /**
     * The name of the Service as described by the Carrier
     * e.g. UPS® Ground
     *
     * @var string
     */
    protected $serviceName;

    /**
     * The internal code used to reference this Service
     * e.g. ups_ground
     *
     * @var ShipEngine\Carriers\ServiceCode
     */
    protected $serviceCode;

    /**
     * @var bool
     */
    protected $trackable;

    /**
     * Array of Warning Messages
     *
     * @var string[]
     */
    protected $warningMessages = [];

    /**
     * Array of Error Messages
     *
     * @var string[]
     */
    protected $errorMessages = [];

    public function __construct(array $rateData)
    {
        $this->id = new RateId($rateData['rate_id']);
        $this->carrierId = new ShipEngine\Carriers\CarrierId($rateData['carrier_id']);
        $this->carrierCode = new ShipEngine\Carriers\CarrierCode($rateData['carrier_code']);
        $this->serviceCode = new ShipEngine\Carriers\ServiceCode($rateData['service_code']);

        $map = [
            'rate_type'               => 'type',
            'carrier_nickname'        => 'carrierNickname',
            'carrier_friendly_name'   => 'carrierFriendlyName',
            'shipping_amount'         => 'shippingAmount',
            'insurance_amount'        => 'insuranceAmount',
            'confirmation_amount'     => 'confirmationAmount',
            'other_amount'            => 'otherAmount',
            'zone'                    => 'zone',
            'package_type'            => 'packageType',
            'delivery_days'           => 'deliveryDays',
            'guaranteed_service'      => 'guaranteed_service',
            'estimated_delivery_date' => 'estimatedDeliveryDate',
            'carrier_delivery_days'   => 'carrierDeliveryDays',
            'ship_date'               => 'shipDate',
            'negotiated_rate'         => 'negotiatedRatesApplied',
            'service_type'            => 'service_name',
            'trackable'               => 'trackable',
            'warning_messages'        => 'warningMessages',
            'error_messages'          => 'errorMessages'
        ];

        foreach ($map as $key => $property) {
            if (! isset($rateData[$key])) {
                continue;
            }

            $value = $rateData[$key];

            if (strpos($key, '_amount')) {
                $value = new ShipEngine\ValueObject\Amount($value['amount'], $value['currency']);
            }

            $this->$property = $value;
        }
    }

    /**
     * @return RateId;
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return ShipEngine\Carriers\CarrierId;
     */
    public function getCarrierId()
    {
        return $this->carrierId;
    }

    /**
     * @return ShipEngine\Carriers\CarrierCode
     */
    public function getCarrierCode()
    {
        return $this->carrierCode;
    }

    /**
     * @return string
     */
    public function getCarrierNickname()
    {
        return $this->carrierNickname;
    }

    /**
     * @return string
     */
    public function getCarrierFriendlyName()
    {
        return $this->carrierFriendlyName;
    }

    /**
     * @return ShipEngine\ValueObject\Amount
     */
    public function getShippingAmount()
    {
        return $this->shippingAmount;
    }

    /**
     * @return ShipEngine\ValueObject\Amount
     */
    public function getInsuranceAmount()
    {
        return $this->insuranceAmount;
    }

    /**
     * @return ShipEngine\ValueObject\Amount
     */
    public function getConfirmationAmount()
    {
        return $this->confirmationAmount;
    }

    /**
     * @return ShipEngine\ValueObject\Amount
     */
    public function getOtherAmount()
    {
        return $this->otherAmount;
    }

    /**
     * @return string
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @return string
     */
    public function getPackageType()
    {
        return $this->packageType;
    }

    /**
     * @return int
     */
    public function getDeliveryDays()
    {
        return $this->deliveryDays;
    }

    /**
     * @return boolean
     */
    public function isGuaranteedService()
    {
        return $this->guaranteedService;
    }

    /**
     * @return \DateTime
     */
    public function getEstimatedDeliveryDate()
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * @return string
     */
    public function getCarrierDeliveryDays()
    {
        return $this->carrierDeliveryDays;
    }

    /**
     * @return \DateTime
     */
    public function getShipDate()
    {
        return $this->shipDate;
    }

    /**
     * @return boolean
     */
    public function hasNegotiatedRatesApplied()
    {
        return $this->negotiatedRatesApplied;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return ShipEngine\Carriers\ServiceCode
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    /**
     * @return boolean
     */
    public function isTrackable()
    {
        return $this->trackable;
    }

    /**
     * @return \string[]
     */
    public function getWarningMessages()
    {
        return $this->warningMessages;
    }

    /**
     * @return \string[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}