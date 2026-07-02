<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Tests\AddressVerification;

use jsamhall\ShipEngine\Address\ResidentialIndicator;
use jsamhall\ShipEngine\AddressVerification\VerificationResult;
use jsamhall\ShipEngine\AddressVerification\VerificationStatus;
use PHPUnit\Framework\TestCase;

/**
 * Characterization test for VerificationResult, whose STATUS_* constants became the
 * VerificationStatus backed enum in v2.0.
 */
class VerificationResultTest extends TestCase
{
    public function testVerifiedResult(): void
    {
        $result = new VerificationResult([
            'status'          => 'verified',
            'messages'        => [],
            'matched_address' => null,
        ]);

        self::assertTrue($result->addressIsVerified());
        self::assertSame(VerificationStatus::Verified, $result->getStatus());
        self::assertSame('Address was successfully verified.', $result->getVerificationStatusMessage());
        self::assertFalse($result->hasMessages());
        self::assertNull($result->getMatchedAddress());
    }

    public function testErrorResultWithMatchedAddress(): void
    {
        $result = new VerificationResult([
            'status'   => 'error',
            'messages' => [
                ['code' => 'a1001', 'message' => 'The country is not supported.', 'type' => 'error'],
            ],
            'matched_address' => [
                'name'                          => 'Jane Doe',
                'phone'                         => null,
                'company_name'                  => null,
                'address_line1'                 => '123 Main St',
                'address_line2'                 => null,
                'address_line3'                 => null,
                'city_locality'                 => 'Austin',
                'state_province'                => 'TX',
                'postal_code'                   => '78701',
                'country_code'                  => 'US',
                'address_residential_indicator' => 'no',
            ],
        ]);

        self::assertFalse($result->addressIsVerified());
        self::assertSame(VerificationStatus::Error, $result->getStatus());
        self::assertTrue($result->hasMessages());

        $matched = $result->getMatchedAddress();
        self::assertNotNull($matched);
        self::assertSame('Austin', $matched->getCityLocality());
        self::assertSame(ResidentialIndicator::No, $matched->getAddressResidentialIndicator());
    }
}
