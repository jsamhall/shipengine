<?php

namespace jsamhall\ShipEngine\Shipment\Package;

class InsuredValue
{
    protected string $currency = 'usd';

    protected float $amount;

    public function __construct(float $amount, string $currency = 'usd')
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function toArray(): array
    {
        return [
            'currency' => $this->currency,
            'amount'   => $this->amount
        ];
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currency;
    }
}