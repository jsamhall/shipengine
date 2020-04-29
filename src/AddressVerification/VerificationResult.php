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

/**
 * Class Response
 *
 * @package ShipEngine\AddressVerification
 */
class VerificationResult
{
    const STATUS_VERIFIED = 'verified';
    const STATUS_UNVERIFIED = 'unverified';
    const STATUS_ERROR = 'error';
    const STATUS_WARNING = 'warning';

    /**
     * The verification status
     *
     * @var string
     */
    private $status;

    /**
     * @var AddressMessage[]
     */
    private $messages = [];

    /**
     * @var MatchedAddress|null
     */
    private $matchedAddress = null;

    /**
     * AddressVerificationResponse constructor.
     *
     * @param $verifyAddressResponse array The response from the /addresses/validate request
     */
    public function __construct(array $verifyAddressResponse)
    {
        $this->status = $verifyAddressResponse['status'];
        $this->setMessages($verifyAddressResponse['messages']);

        if (is_array($verifyAddressResponse['matched_address'])) {
            $this->matchedAddress = new MatchedAddress($verifyAddressResponse['matched_address']);
        }
    }

    /**
     * Was the address successfully verified?
     *
     * @return bool
     */
    public function addressIsVerified()
    {
        return $this->status == self::STATUS_VERIFIED;
    }

    /**
     * Returns the matched address, or null if no match was found
     *
     * @return MatchedAddress|null
     */
    public function getMatchedAddress()
    {
        return $this->matchedAddress;
    }

    /**
     * Returns a message describing the Verification Status of the address
     *
     * @return mixed
     */
    public function getVerificationStatusMessage()
    {
        $statusMessages = [
            self::STATUS_VERIFIED   => 'Address was successfully verified.',
            self::STATUS_UNVERIFIED => 'Address validation was not validated against the database because pre-validation failed.',
            self::STATUS_WARNING    => 'The address was validated, but the address should be double checked.',
            self::STATUS_ERROR      => 'The address could not be validated with any degree of certainty against the database.'
        ];

        return $statusMessages[$this->status];
    }

    /**
     * Returns any Messages included in the response
     *
     * @return AddressMessage[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {
        return count($this->messages);
    }

    private function setMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->messages[] = new AddressMessage($message);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'status'          => $this->status,
            'messages'        => $this->messages,
            'matched_address' => !empty($this->matchedAddress) ? $this->matchedAddress->toArray() : null,
        ];
    }
}
