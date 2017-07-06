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

namespace jsamhall\ShipEngine\Exception;

use jsamhall\ShipEngine\Exception;


/**
 * The data returned from the Address\FormatterInterface implementation
 * is not valid due to the absence of required array keys.
 *
 * @package ShipEngine\Exception
 */
class InvalidAddressData extends Exception
{
    /**
     * InvalidAddressDataException constructor.
     *
     * @param array $missingKeys The keys missing from the array of address data
     */
    public function __construct(array $missingKeys)
    {
        $message = "Invalid Address Data: missing required key(s): " . implode(',', $missingKeys);
        return parent::__construct($message);
    }
}