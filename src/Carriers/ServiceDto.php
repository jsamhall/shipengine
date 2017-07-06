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
namespace jsamhall\ShipEngine\Carriers;

/**
 * Class ServiceDto
 *
 * @package ShipEngine\Carriers
 */
class ServiceDto
{
    /**
     * The code used to identify this Service when requesting Rates or creating Labels
     *
     * @var string
     */
    protected $code;

    /**
     * The name of this Service
     *
     * @var string
     */
    protected $name;

    /**
     * Indicates if this service can be used for Domestic shipments
     *
     * @var bool
     */
    protected $domestic;

    /**
     * Indicates if this Service can be used for International shipments
     *
     * @var bool
     */
    protected $international;

    /**
     * ServiceDto constructor.
     *
     * @param array $serviceData Service Data provided by ShipEngine Carrier API Response
     */
    public function __construct(array $serviceData)
    {
        list($this->code, $this->name, $this->domestic, $this->international) = [
            $serviceData['service_code'],
            $serviceData['name'],
            boolval($serviceData['domestic']),
            boolval($serviceData['international'])
        ];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isDomestic()
    {
        return $this->domestic;
    }

    /**
     * @return bool
     */
    public function isInternational()
    {
        return $this->international;
    }
}