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


class DeliveryConfirmation
{
    /**
     * @var string
     */
    protected $confirmationType;

    protected function __construct($confirmationType)
    {
        $this->confirmationType = $confirmationType;
    }

    public static function delivery()
    {
        return new static("delivery");
    }

    public static function signature()
    {
        return new static("signature");
    }

    public static function adultSignature()
    {
        return new static("adult_signature");
    }

    public static function directSignature()
    {
        return new static("direct_signature");
    }

    function __toString()
    {
        return $this->confirmationType;
    }
}