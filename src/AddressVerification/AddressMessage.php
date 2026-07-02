<?php

declare(strict_types=1);

/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\AddressVerification;

class AddressMessage
{
    /**
     * The reason why the Address failed verification
     */
    protected string $reason;

    /**
     * The error message
     */
    protected string $message;

    /**
     * The error type
     */
    protected string $type;

    public function __construct(array $message)
    {
        $this->reason = $this->translateErrorCode($message['code']);
        $this->message = $message['message'];
        $this->type = $message['type'];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    private function translateErrorCode(string $code): string
    {
        $map = [
            'a1001' => 'The country is not supported.',
            'a1002' => 'Parts of the address could not be verified.',
            'a1003' => 'Some fields were modified while verifying the address.',
            'a1004' => 'The address was found but appears incomplete.',
            'a1005' => 'The address failed pre-validation.'
        ];

        return array_key_exists($code, $map) ? $map[$code] : 'Unknown Failure';
    }

    public function __toString(): string
    {
        return sprintf("Error: %s. Message: %s", $this->getReason(), $this->getMessage());
    }
}
