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

use jsamhall\ShipEngine;

class CarrierCode
{
    /**
     * @var string
     */
    protected $code;

    /**
     * CarrierCode constructor.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
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

    public function __toString()
    {
        return $this->code;
    }

    public function equals(CarrierCode $serviceCode)
    {
        return $serviceCode->__toString() === $this->__toString();
    }
}
