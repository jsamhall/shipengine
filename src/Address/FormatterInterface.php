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
namespace jsamhall\ShipEngine\Address;

interface FormatterInterface
{
    /**
     * Returns an array of Address Data as required by the Address which ensures
     * conformity to the ShipEngine API address model
     *
     * Address model properties can be found here:
     *
     * @link https://docs.shipengine.com/docs/address-validation#section-v1addressesvalidate
     *
     * @param mixed $address A framework address 
     * @return array Array of Address Data as required for ShipEngine API
     */
    public function format($address);
}