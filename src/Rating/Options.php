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

class Options
{
    /**
     * Array of CarrierIds
     *
     * @var string[]
     */
    protected $carriers = [];

    protected $services = [];

    protected $packageTypes = [];

    /**
     * Options constructor.
     *
     * @param mixed $carrierIds A carrierId or array of CarrierIds
     * @param string[] $serviceCodes Service codes for which to retrieve rates
     * @param string[] $packageTypes Package Type Codes for which to retrieve rates
     */
    public function __construct(
        array $carrierIds = [],
        array $serviceCodes = [],
        array $packageTypes = []
    ) {
        array_map([$this, 'addCarrierId'], $carrierIds);
        array_map([$this, 'addServiceCode'], $serviceCodes);
        array_map([$this, 'addPackageType'], $packageTypes);
    }

    /**
     * Add a Carrier ID to retrieve Rates for
     *
     * @param string|ShipEngine\Carriers\CarrierId $carrierId
     * @return void
     */
    public function addCarrierId($carrierId)
    {
        $id = is_a($carrierId, ShipEngine\Carriers\CarrierId::class) ? $carrierId->__toString() : $carrierId;

        if (in_array($id, $this->carriers)) {
            return;
        }

        $this->carriers[] = $id;
    }

    public function addServiceCode(string $serviceCode)
    {
        if (in_array($serviceCode, $this->services)) {
            return;
        }

        $this->services[] = $serviceCode;
    }

    public function addPackageType(string $packageType)
    {
        if (in_array($packageType, $this->packageTypes)) {
            return;
        }

        $this->packageTypes[] = $packageType;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'carrier_ids'   => array_values($this->carriers),
            'service_codes' => array_values($this->services),
            'package_types' => array_values($this->packageTypes)
        ];
    }
}