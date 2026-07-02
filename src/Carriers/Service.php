<?php

declare(strict_types=1);

/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */
namespace jsamhall\ShipEngine\Carriers;

/**
 * Class Service
 *
 * @package ShipEngine\Carriers
 */
class Service
{
    /**
     * The code used to identify this Service when requesting Rates or creating Labels
     */
    protected ServiceCode $code;

    /**
     * The name of this Service
     */
    protected string $name;

    /**
     * Indicates if this service can be used for Domestic shipments
     */
    protected bool $domestic;

    /**
     * Indicates if this Service can be used for International shipments
     */
    protected bool $international;

    /**
     * @param array $serviceData Service Data provided by ShipEngine Carrier API Response
     */
    public function __construct(array $serviceData)
    {
        $this->code = new CustomServiceCode($serviceData['service_code']);
        $this->name = $serviceData['name'];
        $this->domestic = boolval($serviceData['domestic']);
        $this->international = boolval($serviceData['international']);
    }

    public function getCode(): ServiceCode
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDomestic(): bool
    {
        return $this->domestic;
    }

    public function isInternational(): bool
    {
        return $this->international;
    }
}
