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
use jsamhall\ShipEngine\Labels\InsuranceProvider;
use jsamhall\ShipEngine\Labels\Shipment;

abstract class AbstractShipment
{
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

    protected ?InsuranceProvider $insuranceProvider = null;

    /**
     * AbstractShipment constructor.
     *
     * @param ShipEngine\Address\Address $shipTo
     * @param ShipEngine\Address\Address $shipFrom
     * @param Package[]                  $packages
     */
    public function __construct(
        ShipEngine\Address\Address $shipTo,
        ShipEngine\Address\Address $shipFrom,
        array $packages = [],
        ?InsuranceProvider $insuranceProvider = null
    ) {
        $this->shipTo = $shipTo;
        $this->shipFrom = $shipFrom;
        $this->insuranceProvider = $insuranceProvider;

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
        return [
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
    }

    private function getTotalWeight(): ShipEngine\Shipment\Package\Weight
    {
        $total = 0;
        foreach ($this->packages as $package) {
            $total += $package->getWeight()->inOunces();
        }

        return new ShipEngine\Shipment\Package\Weight($total);
    }
}