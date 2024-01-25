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

namespace jsamhall\ShipEngine\Shipment;

use jsamhall\ShipEngine;
use jsamhall\ShipEngine\Carriers\DeliveryConfirmation;
use jsamhall\ShipEngine\Exception;
use jsamhall\ShipEngine\Labels\InsuranceProvider;

abstract class AbstractShipment
{
    private const FEDEX_DIRECT_SIGNATURE_INSURANCE_THRESHOLD = 500;

    /**
     * The destination address of the Shipment
     *
     * @var ShipEngine\Address\Address
     */
    protected $shipTo;

    /**
     * The origin address of the Shipment
     *
     * @var ShipEngine\Address\Address
     */
    protected $shipFrom;

    /**
     * One or more packages to be quoted
     *
     * @var Package[]
     */
    protected $packages = [];

    /**
     * @var ShipEngine\Carriers\AdvancedOption[]
     */
    protected $advancedOptions = [];

    /**
     * @var InsuranceProvider|null
     */
    protected ?InsuranceProvider $insuranceProvider = null;

    /**
     * @var DeliveryConfirmation|null
     */
    protected ?DeliveryConfirmation $deliveryConfirmation = null;

    /**
     * AbstractShipment constructor.
     *
     * @param ShipEngine\Address\Address $shipTo
     * @param ShipEngine\Address\Address $shipFrom
     * @param Package[] $packages
     */
    public function __construct(
        ShipEngine\Address\Address $shipTo,
        ShipEngine\Address\Address $shipFrom,
        array $packages = [],
        ?InsuranceProvider $insuranceProvider = null,
        ?DeliveryConfirmation $deliveryConfirmation = null
    ) {
        $this->shipTo = $shipTo;
        $this->shipFrom = $shipFrom;
        $this->insuranceProvider = $insuranceProvider;
        $this->deliveryConfirmation = $deliveryConfirmation;

        foreach ($packages as $package) {
            $this->addPackage($package);
        }
    }

    /**
     * Add an Advanced Option to the Shipment
     *
     * @link https://docs.shipengine.com/docs/specify-advanced-options
     *
     * @param ShipEngine\Carriers\AdvancedOption $advancedOption
     * @return static $this
     */
    public function addAdvancedOption(ShipEngine\Carriers\AdvancedOption $advancedOption)
    {
        $this->advancedOptions[] = $advancedOption;

        return $this;
    }

    public function specifyInsuranceProvider(InsuranceProvider $insuranceProvider)
    {
        $this->insuranceProvider = $insuranceProvider;
    }

    /**
     * @param Package $package
     * @return static $this
     */
    public function addPackage(Package $package)
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * @return Package[]
     */
    public function getPackages()
    {
        return $this->packages;
    }

    public function toArray()
    {
        $advancedOptions = [];
        foreach ($this->advancedOptions as $advancedOption) {
            $advancedOptions[$advancedOption->getCode()] = $advancedOption->getValue();
        }

        $totalWeight = $this->getTotalWeight();
        $payload = [
            'ship_to'            => $this->shipTo->toArray(),
            'ship_from'          => $this->shipFrom->toArray(),
            'insurance_provider' => $this->insuranceProvider ? $this->insuranceProvider->__toString() : 'none',
            'total_weight'       => [
                'value' => $totalWeight->getValue(),
                'unit'  => $totalWeight->getUnit()
            ],
            'advanced_options'   => count($advancedOptions) ? $advancedOptions : null,
            'packages'           => array_map(function ($package) {
                /** @var ShipEngine\Shipment\Package $package */
                $weight = $package->getWeight();
                $dimensions = $package->getDimensions();

                $data = [
                    'weight'     => [
                        'value' => $weight->getValue(),
                        'unit'  => $weight->getUnit()
                    ],
                    'dimensions' => [
                        'unit'   => $dimensions->getUnit(),
                        'length' => $dimensions->getLength(),
                        'width'  => $dimensions->getWidth(),
                        'height' => $dimensions->getHeight()
                    ]
                ];

                if ($package->hasInsuredValue()) {
                    $data['insured_value'] = $package->getInsuredValue()->toArray();
                }

                if ($package->hasLabelMessages()) {
                    $data['label_messages'] = [];
                    foreach ($package->getLabelMessages() as $labelMessage) {
                        $data['label_messages'][$labelMessage->getLabel()] = $labelMessage->getMessage();
                    }
                }

                return $data;
            }, $this->packages)
        ];

        if (!is_null($this->deliveryConfirmation)) {
            $payload['confirmation'] = $this->deliveryConfirmation->__toString();
        }

        return $payload;
    }

    private function getTotalWeight(): ShipEngine\Shipment\Package\Weight
    {
        $total = 0;
        foreach ($this->packages as $package) {
            $total += $package->getWeight()->inOunces();
        }

        return new ShipEngine\Shipment\Package\Weight($total);
    }

    private function getTotalInsuredValue(): float
    {
        $grossValue = 0;
        foreach ($this->packages as $package) {
            if (!$package->hasInsuredValue()) {
                continue;
            }

            $grossValue += $package->getInsuredValue()->getAmount();
        }

        return $grossValue;
    }

    public function hasDeliveryConfirmation(): bool
    {
        return !is_null($this->deliveryConfirmation);
    }

    /**
     * Set Delivery Confirmation to the Shipment
     * If Delivery Confirmation was previously set, it is overwritten by the provided value
     *
     * @link https://docs.shipengine.com/docs/request-delivery-confirmation
     *
     * @param DeliveryConfirmation $deliveryConfirmation
     * @return static $this
     */
    public function setDeliveryConfirmation(?DeliveryConfirmation $deliveryConfirmation = null)
    {
        $this->deliveryConfirmation = $deliveryConfirmation;

        return $this;
    }
}