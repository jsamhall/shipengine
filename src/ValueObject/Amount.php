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
namespace jsamhall\ShipEngine\ValueObject;

class Amount
{
    const CURRENCY_USD = "usd";

    /**
     * A three letter ISO currency code
     *
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $amount;

    /**
     * Amount constructor.
     *
     * @param float  $amount
     * @param string $currency
     */
    public function __construct(float $amount, $currency = self::CURRENCY_USD)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }
}