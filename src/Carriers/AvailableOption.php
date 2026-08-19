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
 * Class AvailableOption
 *
 * A Data Transfer Object wrapping an Option provided by the Carrier
 * For example, UPS provides a Saturday Delivery option
 *
 * @package ShipEngine\Carriers
 */
class AvailableOption
{
    /**
     * The name of the Option
     */
    protected string $name;

    /**
     * The default value provided for this Option when not specified for a Shipment
     */
    protected mixed $defaultValue;

    /**
     * A description of this Option
     */
    protected string $description;

    /**
     * @param array $optionData Carrier Option Data as provided by the ShipEngine API
     */
    public function __construct(array $optionData)
    {
        $this->name = $optionData['name'] ?? '';
        $this->defaultValue = $optionData['default_value'] ?? null;
        $this->description = $optionData['description'] ?? '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Alias for getName
     */
    public function getCode(): string
    {
        return $this->name;
    }

    public function isDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
