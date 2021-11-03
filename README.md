## About
A PHP wrapper for the accessing and interacting with the ShipEngine v1 API.

**Not actively maintained though functional for my own needs. Use at your own discretion**

## How to use
```php
$addressFormatter = new Acme\AddressFormatter; // implements Address\FormatterInterface
$shipEngine = new jsamhall\ShipEngine\ShipEngine('your_shipengine_api_key', $addressFormatter);
$carriers = $shipEngine->listCarriers();
```

## Example
Minimal example to implement the [Quick Start](https://docs.shipengine.com/docs/quickstart-create-a-label) example:

```php

use jsamhall\ShipEngine\Address\ArrayFormatter;
use jsamhall\ShipEngine\ShipEngine;
use jsamhall\ShipEngine\Address\Address;
use jsamhall\ShipEngine\Shipment\Package;
use jsamhall\ShipEngine\Labels\Shipment;
use jsamhall\ShipEngine\Carriers\USPS\ServiceCode;

$to = new Address();
$to->setName('Mickey and Minnie Mouse');
$to->setPhone('+1 (714) 781-456');
$to->setCompanyName('The Walt Disney Company');
$to->setAddressLine1('address_line1');
$to->setCityLocality('Burbank');
$to->setStateProvince('CA');
$to->setPostalCode('91521');
$to->setCountryCode('US');
$to->setAddressResidentialIndicator('No');

$from = new Address;
$from->setName('Mickey and Minnie Mouse');
$from->setPhone('+1 (714) 781-456');
$from->setCompanyName('The Walt Disney Company');
$from->setAddressLine1('address_line1');
$from->setCityLocality('Burbank');
$from->setStateProvince('CA');
$from->setPostalCode('91521');
$from->setCountryCode('US');
$from->setAddressResidentialIndicator('No');

$weight = new Package\Weight(1.0);
$dimensions = new Package\Dimensions(10.0, 15.0, 8.0);
$package = new Package($weight, $dimensions);

$shipment = new Shipment(ServiceCode::priorityMail(), $to, $from, [$package]);

$addressFormatter = new ArrayFormatter();
$shipEngine = new ShipEngine('your_shipengine_api_key', $addressFormatter);
$testMode = true;
$label = $shipEngine->createLabel($shipment, $testMode);
```
## Addresses
ShipEngine expects a certain address format. This Library offers the `Address\Address` class which conforms
to this format. All methods that require an Address as part of the request (e.g., validation, rating, labels etc.)
expect an `Address\Address` in order to ensure consistency and compatibility.

You should write an implementation `Address\FormatterInterface` that extracts data from your domain-specific
Address in the format expected by the ShipEngine API. This implementation is a constructor argument for the
`Address\Factory` class which will translate your Domain's Address Model to an instance of `Address\Address`

This formatter is available in the public interface of the `jsamhall\ShipEngine` instance:

```php
/** @var Acme\Domain\Address $domainAddress */
$domainAddress = $this->addressRepository->find(1234);
$shipEngineAddress = $shipEngine->formatAddress($domainAddress);

// now $shipEngineAddress can be used for building e.g. an instance of Labels\Shipment
```

## Information
Please visit https://www.shipengine.com/ for information regarding, and to sign up for the ShipEngine platform.

Please visit https://docs.shipengine.com/docs for ShipEngine's official API documentation.

This project is in no way associated to or endorsed by ShipEngine, ShipStation or any of their partners.

ShipEngine and ShipStation are registered trademarks. All rights reserved.