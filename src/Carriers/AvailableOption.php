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
     *
     * @var string
     */
    protected $name;

    /**
     * The default value provided for this Option when not specified for a Shipment
     *
     * @var string
     */
    protected $defaultValue;

    /**
     * A description of this Option
     *
     * @var string
     */
    protected $description;

    /**
     * Option constructor.
     *
     * @param array $optionData Carrier Option Data as provided by the ShipEngine API
     */
    public function __construct(array $optionData)
    {
        list($this->name, $this->defaultValue, $this->description) = array_values($optionData);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Alias for getName
     *
     * @return string
     */
    public function getCode()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function isDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
