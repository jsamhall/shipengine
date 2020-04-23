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
     * The DateTime of the Shipment. The day the package will be shipped.
     *
     * @var \DateTime
     */
    protected $shipDate;

    /**
     * The Customs information block of the Shipment. This is optional.
     *
     * @var Customs|null
     */
    protected $customs = null;

    /**
     * One or more packages to be quoted
     *
     * @var Package[]
     */
    protected $packages = [];

    /**
     * AbstractShipment constructor.
     *
     * @param ShipEngine\Address\Address $shipTo
     * @param ShipEngine\Address\Address $shipFrom
     * @param Package[]                  $packages
     */
    public function __construct(ShipEngine\Address\Address $shipTo, ShipEngine\Address\Address $shipFrom, array $packages = [])
    {
        $this->shipTo = $shipTo;
        $this->shipFrom = $shipFrom;

        foreach ($packages as $package) {
            $this->addPackage($package);
        }
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
     * @param \DateTime $dateTime
     * @return $this
     */
    public function setShipDate(\DateTime $dateTime)
    {
        $this->shipDate = $dateTime;

        return $this;
    }

    /**
     * @param Customs $customs
     * @return AbstractShipment
     */
    public function setCustoms(Customs $customs): self
    {
        $this->customs = $customs;

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
        $shipment = [
            'ship_to'   => $this->shipTo->toArray(),
            'ship_from' => $this->shipFrom->toArray(),
            'packages'  => array_map(function (Package $package) {
                $response = [
                    'weight' => [
                        'value' => $package->getWeightAmount(),
                        'unit'  => $package->getWeightUnit(),
                    ],
                ];

                if (! empty($package->getDimension())) {
                    $response['dimensions'] =  [
                        'unit'   => $package->getDimension()->getDimensionUnit(),
                        'length' => $package->getDimension()->getDimensionLength(),
                        'width'  => $package->getDimension()->getDimensionWidth(),
                        'height' => $package->getDimension()->getDimensionHeight(),
                    ];
                }

                if (! empty($package->getPackageCode())) {
                    $response['package_code'] = $package->getPackageCode();
                }

                return $response;
            }, $this->packages)
        ];

        if (! empty($this->customs)) {
            $customItems = [];
            foreach ($this->customs->getCustomItems() as $customItem) {
                $item = [
                    'description'       => $customItem->getDescription(),
                    'quantity'          => $customItem->getQuantity(),
                    'value'             => $customItem->getValue(),
                    'country_of_origin' => $customItem->getCountryOfOrigin(),
                ];

                if (! empty($customItem->getTariffCode())) {
                    $item['harmonized_tariff_code'] = $customItem->getTariffCode();
                }
                $customItems[] = $item;
            }

            $customs = [
                'contents'     => $this->customs->getContents(),
                'non_delivery' => $this->customs->getNonDelivery(),
                'customs_items'=> $customItems
            ];

            $shipment['customs'] = $customs;
        }

        if (! empty($this->shipDate)) {
            $shipment['ship_date'] = $this->shipDate->format('Y-m-d');
        }

        return $shipment;
    }
}
