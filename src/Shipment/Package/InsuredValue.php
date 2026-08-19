<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Shipment\Package;

class InsuredValue
{
    public function __construct(
        private readonly float $amount,
        private readonly string $currency = 'usd'
    ) {
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
