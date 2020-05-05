<?php

namespace Tests\Mocks\Validation;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use jsamhall\ShipEngine\Address\ArrayFormatter;
use jsamhall\ShipEngine\ShipEngine;

class AddressMock
{
    public function mockValidationSuccess(): ShipEngine
    {
        $addressResponse = [
            [
                "status" => "verified",
                "original_address" => [
                    "name"                          => null,
                    "phone"                         => null,
                    "company_name"                  => null,
                    "address_line1"                 => "525 S Winchester Blvd",
                    "address_line2"                 => null,
                    "address_line3"                 => null,
                    "city_locality"                 => "San Jose",
                    "state_province"                => "CA",
                    "postal_code"                   => "95128",
                    "country_code"                  => "US",
                    "address_residential_indicator" => "unknown"
                ],
                "matched_address" => [
                    "name"                          => null,
                    "phone"                         => null,
                    "company_name"                  => null,
                    "address_line1"                 => "525 S WINCHESTER BLVD",
                    "address_line2"                 => "",
                    "address_line3"                 => null,
                    "city_locality"                 => "SAN JOSE",
                    "state_province"                => "CA",
                    "postal_code"                   => "95128-2537",
                    "country_code"                  => "US",
                    "address_residential_indicator" => "no"
                ],
                "messages" => []
            ]
        ];
        $mock = new MockHandler([new Response(200, [], json_encode($addressResponse))]);
        $mockStack = HandlerStack::create($mock);
        $mockOptions = ['handler' => $mockStack];

        $addressFormatter = new ArrayFormatter();

        return new ShipEngine("", $addressFormatter, $mockOptions);
    }

    public function mockInvalidErrorFromShipEngine(): ShipEngine
    {
        $response = [
            'request_id' => 'c57869c1-a123-444e-80da-a7e7fedf4c27',
            'errors' => [
                [
                    'error_source' => 'shipengine',
                    'error_type' => 'security',
                    'error_code' => 'unauthorized',
                    'message' => 'The API key is invalid. Please see https://www.shipengine.com/docs/auth'
                ]
            ]
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($response))]);
        $mockStack = HandlerStack::create($mock);
        $mockOptions = ['handler' => $mockStack];

        $addressFormatter = new ArrayFormatter();

        return new ShipEngine("", $addressFormatter, $mockOptions);
    }

    public function mockInvalidConnectionToShipEngine(): ShipEngine
    {
        $mock = new MockHandler([
            new BadResponseException("Insufficient or Incorrect Address Data", new Request('POST', 'https://api.shipengine.com/v1/addresses/validate'))
        ]);
        $mockStack = HandlerStack::create($mock);
        $mockOptions = ['handler' => $mockStack];

        $addressFormatter = new ArrayFormatter();

        return new ShipEngine("", $addressFormatter, $mockOptions);
    }
}
