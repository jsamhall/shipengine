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
namespace jsamhall\ShipEngine\Rating;

use jsamhall\ShipEngine;

class Shipment extends ShipEngine\Shipment\AbstractShipment
{
    const ADDRESS_NO_VALIDATION = 'no_validation';
    const ADDRESS_VALIDATE_ONLY = 'validate_only';
    const ADDRESS_VALIDATE_AND_CLEAN = 'validate_and_clean';

    protected $validateAddress = self::ADDRESS_NO_VALIDATION;

    public function disableAddressValidation()
    {
        $this->validateAddress = self::ADDRESS_NO_VALIDATION;

        return $this;
    }

    public function enableAddressValidation()
    {
        $this->validateAddress = self::ADDRESS_VALIDATE_ONLY;
    }

    public function enableAddressValidationWithCleaning()
    {
        $this->validateAddress = self::ADDRESS_VALIDATE_AND_CLEAN;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'validate_address' => $this->validateAddress,
        ]);
    }
}