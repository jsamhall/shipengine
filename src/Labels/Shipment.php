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

namespace jsamhall\ShipEngine\Labels;

use jsamhall\ShipEngine;

class Shipment extends ShipEngine\Shipment\AbstractShipment
{
    /**
     * Optional carrierId
     */
    protected ?ShipEngine\Carriers\CarrierId $carrierId = null;

    /**
     * The service code to request a Label for
     */
    protected ShipEngine\Carriers\ServiceCode $serviceCode;

    public function __construct(
        ShipEngine\Carriers\ServiceCode $service,
        ShipEngine\Address\Address      $shipTo,
        ShipEngine\Address\Address      $shipFrom,
        array                           $packages = [],
        ?InsuranceProvider              $insuranceProvider = null
    ) {
        parent::__construct($shipTo, $shipFrom, $packages, $insuranceProvider);
        $this->serviceCode = $service;
    }

    public function getServiceCode(): ShipEngine\Carriers\ServiceCode
    {
        return $this->serviceCode;
    }

    /**
     * Specify a specific carrierId for which this Shipment applies
     * Only necessary when multiple Accounts for the same Carrier have been established
     */
    public function specifyCarrierId(ShipEngine\Carriers\CarrierId $carrierId): static
    {
        $this->carrierId = $carrierId;

        return $this;
    }

    /**
     * Prepare the Shipment Data for transport as an array
     */
    public function toArray(): array
    {
        $data = array_merge(parent::toArray(), [
            'service_code' => $this->serviceCode->code()
        ]);

        if (! is_null($this->carrierId)) {
            $data['carrier_id'] = $this->carrierId->__toString();
        }

        return $data;
    }
}
