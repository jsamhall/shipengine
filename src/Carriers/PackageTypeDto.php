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
 * Class PackageTypeDto
 *
 * @package ShipEngineApi\Carriers
 */
class PackageTypeDto
{
    /**
     * The identifier of this Package Type within the ShipEngine Carrier Settings
     * Used only with Custom Package Types
     *
     * @link https://docs.shipengine.com/docs/custom-packages
     *
     * @var mixed
     */
    protected $packageId;

    /**
     * The code used to identify this Package Type with the Carrier
     * This is used for all shipments, labels and rates
     *
     * @var string
     */
    protected $packageCode;

    /**
     * The Name of the Package Type
     *
     * @var string
     */
    protected $name;

    /**
     * A Description of the Package Type
     *
     * @var string
     */
    protected $description;

    /**
     * PackageTypeDto constructor.
     *
     * @param array $packageData Package Data provided by the ShipEngine Carrier API Response
     */
    public function __construct(array $packageData)
    {
        list($this->packageId, $this->packageCode, $this->name, $this->description) = array_values($packageData);
    }

    /**
     * @return mixed
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * @return string
     */
    public function getPackageCode()
    {
        return $this->packageCode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}