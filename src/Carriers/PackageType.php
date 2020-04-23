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

use jsamhall\ShipEngine;

/**
 * Class PackageType
 *
 * @package ShipEngine\Carriers
 */
class PackageType
{
    /**
     * The identifier of this Package Type within the ShipEngine Carrier Settings
     * Used only with Custom Package Types
     *
     * @link https://docs.shipengine.com/docs/custom-packages
     *
     * @var PackageId
     */
    protected $packageId;

    /**
     * The code used to identify this Package Type with the Carrier
     * This is used for all shipments, labels and rates
     *
     * @var PackageCode
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
     * PackageType constructor.
     *
     * @param array $packageData Package Data provided by the ShipEngine Carrier API Response
     */
    public function __construct(array $packageData)
    {
        list(
            $this->packageId,
            $this->packageCode,
            $this->name,
            $this->description
            ) = [
            new PackageId($packageData['package_id']),
            new PackageCode($packageData['package_code']),
            $packageData['name'],
            $packageData['description']
        ];
    }

    /**
     * @return PackageId
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * @return PackageCode
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
