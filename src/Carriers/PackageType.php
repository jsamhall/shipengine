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
     */
    protected ?PackageId $packageId;

    /**
     * The code used to identify this Package Type with the Carrier
     * This is used for all shipments, labels and rates
     */
    protected PackageCode $packageCode;

    /**
     * The Name of the Package Type
     */
    protected string $name;

    /**
     * A Description of the Package Type
     */
    protected ?string $description;

    /**
     * @param array $packageData Package Data provided by the ShipEngine Carrier API Response
     */
    public function __construct(array $packageData)
    {
        $this->packageId = isset($packageData['package_id'])
            ? new PackageId($packageData['package_id'])
            : null;

        $this->packageCode = new PackageCode($packageData['package_code']);
        $this->name = $packageData['name'];
        $this->description = $packageData['description'] ?? null;
    }

    public function getPackageId(): ?PackageId
    {
        return $this->packageId;
    }
    
    public function getPackageCode(): PackageCode
    {
        return $this->packageCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
