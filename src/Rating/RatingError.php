<?php

namespace jsamhall\ShipEngine\Rating;

class RatingError
{
    private array $details;

    protected function __construct(array $details)
    {
        $this->details = $details;
    }

    public static function fromRateResponseError(array $details)
    {
        return new self($details);
    }

    public function getMessage()
    {
        return $this->details['message'] ?? 'No message was provided';
    }

    public function getSource()
    {
        return $this->details['error_source'];
    }

    public function getCarrierName()
    {
        return $this->details['carrier_name'];
    }

    // other parameters include: error_type, error_code, carrier_id, carrier_code
}