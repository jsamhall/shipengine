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

class RateResponse
{
    /**
     * @var Rate[]
     */
    protected $rates = [];

    /**
     * @var array
     */
    protected $invalidRates = [];

    /**
     * @var string[]
     */
    protected $errors = [];

    /**
     * @var ShipEngine\ValueObject\Id
     */
    protected $requestId;

    /**
     * @var ShipEngine\ValueObject\Id
     */
    protected $shipmentId;


    public function __construct(array $rateResponse)
    {
        $this->requestId = new ShipEngine\ValueObject\Id($rateResponse['rate_request_id']);
        $this->shipmentId = new ShipEngine\Shipment\ShipmentId($rateResponse['shipment_id']);

        foreach ($rateResponse['rates'] as $rate) {
            $this->rates[] = new Rate($rate);
        }

        $this->invalidRates = $rateResponse['invalid_rates'] ?? [];

        foreach($rateResponse['errors'] ?? [] as $errorDetails){
            $this->errors[] = RatingError::fromRateResponseError($errorDetails);
        }
    }

    /**
     * @return Rate[]
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Get the Rate for the given Service Code
     *
     * @param string|ShipEngine\Carriers\ServiceCode $serviceCode
     * @return bool|Rate
     */
    public function getRateByServiceCode($serviceCode)
    {
        if (! $serviceCode instanceof ShipEngine\Carriers\ServiceCode) {
            $serviceCode = new ShipEngine\Carriers\ServiceCode($serviceCode);
        }

        foreach ($this->rates as $rate) {
            if ($rate->getServiceCode()->equals($serviceCode)) {
                return $rate;
            }
        }

        return false;
    }

    /**
     * @param string|ShipEngine\ValueObject\Id $rateId
     * @return bool|Rate
     */
    public function getRateById($rateId)
    {
        if (! $rateId instanceof ShipEngine\ValueObject\Id) {
            $rateId = new ShipEngine\ValueObject\Id($rateId);
        }

        foreach ($this->rates as $rate) {
            if ($rate->getId()->equals($rateId)) {
                return $rate;
            }
        }

        return false;
    }

    /**
     * Returns an array of all Rates for the given CarrierId
     *
     * @param string|ShipEngine\ValueObject\Id $carrierId
     * @return Rate[]
     */
    public function getRatesByCarrierId($carrierId)
    {
        if (! $carrierId instanceof ShipEngine\ValueObject\Id) {
            $carrierId = new ShipEngine\ValueObject\Id($carrierId);
        }

        return array_filter($this->rates, function ($rate) use ($carrierId) {
            /** @var Rate $rate */
            return $rate->getCarrierId()->equals($carrierId);
        });
    }

    /**
     * Returns an array of all Rates for the given CarrierCode
     *
     * @param string|ShipEngine\Carriers\CarrierCode $carrierCode
     * @return Rate[]
     */
    public function getRatesByCarrierCode($carrierCode)
    {
        if (! $carrierCode instanceof ShipEngine\Carriers\CarrierCode) {
            $carrierCode = new ShipEngine\Carriers\CarrierCode($carrierCode);
        }

        return array_filter($this->rates, function ($rate) use ($carrierCode) {
            /** @var Rate $rate */
            return $rate->getCarrierCode()->equals($carrierCode);
        });
    }
    
    /**
     * @return array
     */
    public function getInvalidRates()
    {
        return $this->invalidRates;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return ShipEngine\ValueObject\Id
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return ShipEngine\ValueObject\Id
     */
    public function getShipmentId()
    {
        return $this->shipmentId;
    }
}