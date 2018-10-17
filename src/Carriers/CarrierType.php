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

class CarrierType
{
    /**
     * @var string
     */
    protected $carrierType;

    protected function __construct($carrierType)
    {
        $this->carrierType = $carrierType;
    }

    public static function ups()
    {
        return new static("ups");
    }

    public static function fedex()
    {
        return new static("fedex");
    }

    public static function usps()
    {
        return new static("stamps_com");
    }

    function __toString()
    {
        return $this->carrierType;
    }
}