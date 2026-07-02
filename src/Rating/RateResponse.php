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

class RateResponse
{
    /**
     * @var Rate[]
     */
    protected array $rates = [];

    /**
     * @var array
     */
    protected array $invalidRates = [];

    /**
     * @var RatingError[]
     */
    protected array $errors = [];

    protected ShipEngine\ValueObject\Id $requestId;

    protected ShipEngine\Shipment\ShipmentId $shipmentId;

    public function __construct(array $rateResponse)
    {
        $this->requestId = new ShipEngine\ValueObject\Id($rateResponse['rate_request_id']);
        $this->shipmentId = new ShipEngine\Shipment\ShipmentId($rateResponse['shipment_id']);

        foreach ($rateResponse['rates'] as $rate) {
            $this->rates[] = new Rate($rate);
        }

        $this->invalidRates = $rateResponse['invalid_rates'] ?? [];

        foreach ($rateResponse['errors'] ?? [] as $errorDetails) {
            $this->errors[] = RatingError::fromRateResponseError($errorDetails);
        }
    }

    /**
     * @return Rate[]
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    /**
     * Get the Rate for the given Service Code
     */
    public function getRateByServiceCode(ShipEngine\Carriers\ServiceCode|string $serviceCode): Rate|false
    {
        if (! $serviceCode instanceof ShipEngine\Carriers\ServiceCode) {
            $serviceCode = new ShipEngine\Carriers\CustomServiceCode($serviceCode);
        }

        foreach ($this->rates as $rate) {
            if ($rate->getServiceCode()->equals($serviceCode)) {
                return $rate;
            }
        }

        return false;
    }

    public function getRateById(ShipEngine\ValueObject\Id|string $rateId): Rate|false
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
     * @return Rate[]
     */
    public function getRatesByCarrierId(ShipEngine\ValueObject\Id|string $carrierId): array
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
     * @return Rate[]
     */
    public function getRatesByCarrierCode(ShipEngine\Carriers\CarrierCode|string $carrierCode): array
    {
        if (! $carrierCode instanceof ShipEngine\Carriers\CarrierCode) {
            $carrierCode = new ShipEngine\Carriers\CarrierCode($carrierCode);
        }

        return array_filter($this->rates, function ($rate) use ($carrierCode) {
            /** @var Rate $rate */
            return $rate->getCarrierCode()->equals($carrierCode);
        });
    }

    public function getInvalidRates(): array
    {
        return $this->invalidRates;
    }

    /**
     * @return RatingError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getRequestId(): ShipEngine\ValueObject\Id
    {
        return $this->requestId;
    }

    public function getShipmentId(): ShipEngine\Shipment\ShipmentId
    {
        return $this->shipmentId;
    }
}
