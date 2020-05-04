<?php

namespace Feature\Api\Validation;

use GuzzleHttp\Exception\ClientException;
use jsamhall\ShipEngine\Address\Address;
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

    public function testInsufficientValidation(): void
    {
        // Arrange
        $address = new Address();
        $shipEngineMock = new AddressMock();
        $this->expectException(ClientException::class);
        $request = $shipEngineMock->mockValidationFailure();

        // Act
        $request->validateAddress($address);
    }
}
