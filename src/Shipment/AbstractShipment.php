<?php

declare(strict_types=1);

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
use jsamhall\ShipEngine\Labels\InsuranceProvider;

abstract class AbstractShipment
{
    private const FEDEX_DIRECT_SIGNATURE_INSURANCE_THRESHOLD = 500;

    /**
     * One or more packages to be quoted
     *
     * @var Package[]
     */
    protected array $packages = [];

    /**
     * @var ShipEngine\Carriers\AdvancedOption[]
     */
    protected array $advancedOptions = [];

    public function __construct(
        protected ShipEngine\Address\Address $shipTo,
        protected ShipEngine\Address\Address $shipFrom,
        array $packages = [],
        protected ?InsuranceProvider $insuranceProvider = null,
        protected ?DeliveryConfirmation $deliveryConfirmation = null
    ) {
        foreach ($packages as $package) {
            $this->addPackage($package);
        }
    }

    /**
     * Add an Advanced Option to the Shipment
     *
     * @link https://docs.shipengine.com/docs/specify-advanced-options
     */
    public function addAdvancedOption(ShipEngine\Carriers\AdvancedOption $advancedOption): static
    {
        $this->advancedOptions[] = $advancedOption;

        return $this;
    }

    public function specifyInsuranceProvider(InsuranceProvider $insuranceProvider): void
    {
        $this->insuranceProvider = $insuranceProvider;
    }

    public function addPackage(Package $package): static
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    public function toArray(): array
    {
        $advancedOptions = [];
        foreach ($this->advancedOptions as $advancedOption) {
            $advancedOptions[$advancedOption->getCode()] = $advancedOption->getValue();
        }

        $totalWeight = $this->getTotalWeight();
        $payload = [
            'ship_to'            => $this->shipTo->toArray(),
            'ship_from'          => $this->shipFrom->toArray(),
            'insurance_provider' => ($this->insuranceProvider ?? InsuranceProvider::None)->value,
            'total_weight'       => [
                'value' => $totalWeight->getValue(),
                'unit'  => $totalWeight->getUnit()->value
            ],
            'advanced_options'   => count($advancedOptions) ? $advancedOptions : null,
            'packages'           => array_map(function (Package $package) {
                $weight = $package->getWeight();
                $dimensions = $package->getDimensions();

                $data = [
                    'weight'     => [
                        'value' => $weight->getValue(),
                        'unit'  => $weight->getUnit()->value
                    ],
                    'dimensions' => [
                        'unit'   => $dimensions->getUnit()->value,
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

        if (! is_null($this->deliveryConfirmation)) {
            $payload['confirmation'] = $this->deliveryConfirmation->value;
        }

        return $payload;
    }

    public function getTotalWeight(): ShipEngine\Shipment\Package\Weight
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
            if (! $package->hasInsuredValue()) {
                continue;
            }

            $grossValue += $package->getInsuredValue()->getAmount();
        }

        return $grossValue;
    }

    public function hasDeliveryConfirmation(): bool
    {
        return ! is_null($this->deliveryConfirmation);
    }

    /**
     * Set Delivery Confirmation to the Shipment
     * If Delivery Confirmation was previously set, it is overwritten by the provided value
     *
     * @link https://docs.shipengine.com/docs/request-delivery-confirmation
     */
    public function setDeliveryConfirmation(?DeliveryConfirmation $deliveryConfirmation = null): static
    {
        $this->deliveryConfirmation = $deliveryConfirmation;

        return $this;
    }
}
