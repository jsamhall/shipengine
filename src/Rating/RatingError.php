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

class RatingError
{
    protected function __construct(private readonly array $details)
    {
    }

    public static function fromRateResponseError(array $details): self
    {
        return new self($details);
    }

    public function getMessage(): string
    {
        return $this->details['message'] ?? 'No message was provided';
    }

    public function getSource(): ?string
    {
        return $this->details['error_source'] ?? null;
    }

    public function getCarrierName(): ?string
    {
        return $this->details['carrier_name'] ?? null;
    }

    // other parameters include: error_type, error_code, carrier_id, carrier_code
}
