<?php
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

class AddressMessageDto
{
    /**
     * The reason why the Address failed verification
     *
     * @var string
     */
    protected $reason;

    /**
     * The error message
     *
     * @var string
     */
    protected $message;

    /**
     * The error type
     *
     * @var string
     */
    protected $type;

    public function __construct(array $message)
    {
        $this->reason = $this->translateErrorCode($message['code']);
        $this->message = $message['message'];
        $this->type = $message['type'];
    }

    public function getType()
    {
        return $this->type;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function translateErrorCode(string $code)
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

    public function __toString()
    {
        /** @var string $message */
        $message = sprintf("Error: %s. Message: %s", $this->getReason(), $this->getMessage());

        return $message;
    }
}