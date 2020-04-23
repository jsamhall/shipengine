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

namespace jsamhall\ShipEngine\Carriers;

/**
 * Class AdvancedOption
 *
 * AdvancedOption for a Shipment
 *
 * @package jsamhall\ShipEngine\Carriers
 */
class AdvancedOption
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * AdvancedOption constructor.
     *
     * @param string $code
     * @param mixed  $value
     */
    public function __construct($code, $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
