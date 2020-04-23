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

class Options implements \Countable, \IteratorAggregate
{
    /**
     * Array of CarrierIds
     *
     * @var string[]
     */
    protected $carriers = [];

    /**
     * Array of service codes to filter by.
     *
     * @var string[]
     */
    protected $serviceCodes = [];

    /**
     * Array of service codes to filter by.
     *
     * @var string[]
     */
    protected $packageTypes = [];

    /**
     * Options constructor.
     *
     * @param mixed $carriers A carrierId or array of CarrierIds
     * @param string|array $serviceCodes A serviceCode (string) or an array of service codes
     * @param string|array $packageTypes A packageType (string) or an array of package types
     */
    public function __construct($carriers = [], $serviceCodes = [], $packageTypes = [])
    {
        if (! is_array($carriers)) {
            $carriers = [$carriers];
        }

        if (! is_array($serviceCodes)) {
            $serviceCodes = [$serviceCodes];
        }

        if (! is_array($packageTypes)) {
            $packageTypes = [$packageTypes];
        }

        array_map([$this, 'addCarrierId'], $carriers);
        array_map([$this, 'addServiceCode'], $serviceCodes);
        array_map([$this, 'addPackageType'], $packageTypes);
    }

    /**
     * Add a Carrier ID to retrieve Rates for
     *
     * @param string|ShipEngine\Carriers\CarrierId $carrierId
     * @return static $this
     */
    public function addCarrierId($carrierId): self
    {
        $this->carriers[] = is_a($carrierId, ShipEngine\Carriers\CarrierId::class)
            ? $carrierId->__toString()
            : $carrierId;

        return $this;
    }

    public function addServiceCode(string $serviceCode): self
    {
        $this->serviceCodes[] = $serviceCode;

        return $this;
    }

    public function setServiceCodes(array $serviceCodes): self
    {
        $this->serviceCodes = $serviceCodes;

        return $this;
    }

    public function addPackageType(string $packageType): self
    {
        $this->packageTypes[] = $packageType;

        return $this;
    }

    public function setPackageTypes(array $packageTypes): self
    {
        $this->packageTypes = $packageTypes;

        return $this;
    }

    public function count(): int
    {
        return count($this->carriers);
    }

    public function getIterator(): array
    {
        return $this->carriers;
    }

    public function toArray(): array
    {
        $data = [
            'carrier_ids' => array_values($this->carriers)
        ];

        // If you pass an empty array to ShipEngine, it does not filter properly. Only apply if we have an item
        if (count($this->serviceCodes) > 0) {
            $data['service_codes'] = array_values($this->serviceCodes);
        }

        if (count($this->packageTypes) > 0) {
            $data['package_types'] = array_values($this->packageTypes);
        }

        return $data;
    }
}
