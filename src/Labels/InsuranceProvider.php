<?php

namespace jsamhall\ShipEngine\Labels;

class InsuranceProvider
{
    private string $code;

    protected function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function none()
    {
        return new self('none');
    }

    public static function shipsurance()
    {
        return new self('shipsurance');
    }

    public static function carrier()
    {
        return new self('carrier');
    }

    public static function thirdParty()
    {
        return new self('third_party');
    }

    public function __toString()
    {
        return $this->code;
    }
}