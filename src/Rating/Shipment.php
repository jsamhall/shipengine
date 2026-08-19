<?php

declare(strict_types=1);

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
    protected AddressValidation $validateAddress = AddressValidation::NoValidation;

    public function disableAddressValidation(): static
    {
        $this->validateAddress = AddressValidation::NoValidation;

        return $this;
    }

    public function enableAddressValidation(): static
    {
        $this->validateAddress = AddressValidation::ValidateOnly;

        return $this;
    }

    public function enableAddressValidationWithCleaning(): static
    {
        $this->validateAddress = AddressValidation::ValidateAndClean;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'validate_address' => $this->validateAddress->value,
        ]);
    }
}
