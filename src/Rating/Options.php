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

namespace jsamhall\ShipEngine\Rating;

use jsamhall\ShipEngine;

class Options
{
    /**
     * Array of CarrierIds
     *
     * @var string[]
     */
    protected array $carriers = [];

    /**
     * @var string[]
     */
    protected array $services = [];

    /**
     * @var string[]
     */
    protected array $packageTypes = [];

    /**
     * @param array $carrierIds A carrierId or array of CarrierIds
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
     */
    public function addCarrierId(ShipEngine\Carriers\CarrierId|string $carrierId): void
    {
        $id = $carrierId instanceof ShipEngine\Carriers\CarrierId ? $carrierId->__toString() : $carrierId;

        if (in_array($id, $this->carriers)) {
            return;
        }

        $this->carriers[] = $id;
    }

    public function addServiceCode(string $serviceCode): void
    {
        if (in_array($serviceCode, $this->services)) {
            return;
        }

        $this->services[] = $serviceCode;
    }

    public function addPackageType(string $packageType): void
    {
        if (in_array($packageType, $this->packageTypes)) {
            return;
        }

        $this->packageTypes[] = $packageType;
    }

    public function toArray(): array
    {
        return array_filter([
            'carrier_ids'   => array_values($this->carriers),
            'service_codes' => array_values($this->services),
            'package_types' => array_values($this->packageTypes)
        ], function ($value) {
            return is_array($value) && count($value) > 0;
        });
    }
}
