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

class AddressMessage
{
    /**
     * Field names returned in response.
     *
     * @var array
     */
    const PROPERTY_NAMES = [
        'name',
        'phone',
        'company_name',
        'address_line1',
        'address_line2',
        'address_line3',
        'city_locality',
        'state_province',
        'postal_code',
        'country_code',
    ];

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

    /**
     * The field with error.
     *
     * @var string
     */
    protected $field;

    public function __construct(array $message)
    {
        $this->reason = $this->translateErrorCode($message['code']);
        $this->field = $this->identifyFieldFromMessage($message['message']);
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

    public function getField()
    {
        return $this->field;
    }

    private function identifyFieldFromMessage(string $message)
    {
        foreach (self::PROPERTY_NAMES as $propertyName) {
            if (strpos($message, $propertyName) !== false) {
                return $propertyName;
            }
        }

        return 'unknown';
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