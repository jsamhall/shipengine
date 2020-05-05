<?php

namespace Tests\Feature\Api\Validation;

use jsamhall\ShipEngine\Address\Address;
use jsamhall\ShipEngine\Exception\ApiErrorResponse;
use jsamhall\ShipEngine\Exception\ApiRequestFailed;
use PHPUnit\Framework\TestCase;
use Tests\Mocks\Validation\AddressMock;

class AddressTest extends TestCase
{
    public function testValidateAddress(): void
    {
        // Arrange
        $address = new Address();
        $address->setAddressLine1("525 S Winchester Blvd")
            ->setCityLocality("San Jose")
            ->setStateProvince("CA")
            ->setPostalCode("95128")
            ->setCountryCode("US")
            ->setAddressResidentialIndicator("unknown");
        $shipEngineMock = new AddressMock();

        $request = $shipEngineMock->mockValidationSuccess();

        // Act
        $response = $request->validateAddress($address);

        // Assert
        $this->assertSame(strtoupper($address->getAddressLine1()), $response->getMatchedAddress()->getAddressLine1());
        $this->assertSame(strtoupper($address->getCityLocality()), $response->getMatchedAddress()->getCityLocality());
    }

    public function testApiResponseCaptured(): void
    {
        // Expects
        $this->expectException(ApiErrorResponse::class);

        // Arrange
        $address = new Address();
        $shipEngineMock = new AddressMock();
        $request = $shipEngineMock->mockInvalidErrorFromShipEngine();

        // Act
        $request->validateAddress($address);
    }

    public function testApiRequestFailedCaptured(): void
    {
        // Expects
        $this->expectException(ApiRequestFailed::class);

        // Arrange
        $address = new Address();
        $shipEngineMock = new AddressMock();
        $request = $shipEngineMock->mockInvalidConnectionToShipEngine();

        // Act
        $request->validateAddress($address);
    }
}
