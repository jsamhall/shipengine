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

/**
 * Class VerificationResult
 *
 * @package ShipEngine\AddressVerification
 */
class VerificationResult
{
    /**
     * The verification status
     *
     * @var VerificationStatus
     */
    private VerificationStatus $status;

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
     * @param array $verifyAddressResponse The response from the /addresses/validate request
     */
    public function __construct(array $verifyAddressResponse)
    {
        $this->status = VerificationStatus::from($verifyAddressResponse['status']);
        $this->setMessages($verifyAddressResponse['messages']);

        if (is_array($verifyAddressResponse['matched_address'])) {
            $this->matchedAddress = new MatchedAddress($verifyAddressResponse['matched_address']);
        }
    }

    /**
     * The verification status as an enum.
     */
    public function getStatus(): VerificationStatus
    {
        return $this->status;
    }

    /**
     * Was the address successfully verified?
     */
    public function addressIsVerified(): bool
    {
        return $this->status === VerificationStatus::Verified;
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
     */
    public function getVerificationStatusMessage(): string
    {
        return $this->status->message();
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
        return count($this->messages) > 0;
    }

    private function setMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->messages[] = new AddressMessage($message);
        }

        return $this;
    }
}
