## About
A PHP wrapper for the accessing and interacting with the ShipEngine v1 API.

**Entirely untested and still in development**

**ST: Tested via the consumer of the SDK, but still in active deployment**

## How to use
```php
$addressFormatter = new Acme\AddressFormatter; // implements Address\FormatterInterface
$shipEngine = new jsamhall\ShipEngine('your_shipengine_api_key', $addressFormatter);
$carriers = $shipEngine->listCarriers();
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

## Carriers
ShipEngine connects with many carriers. This library can connect with the following carriers:

 * USPS via (Stamps.com)
 * UPS (Worldwide)
 * FedEx (US & CA)

Each carrier is created differently. For example, for stamps.com you may do the following:

```php
$stampsDotCom = jsamhall\ShipEngine\Carriers\USPS\Factory::factory("nickname", "username", "password");

$shipEngine->connectStampsDotCom($stampsDotCom);
// returns "carrierId"
```

For UPS, there is no factory method to generate the class. This is because UPS takes too many parameters
to easily create an interface.

```php
$ups = new jsamhall\ShipEngine\Carriers\UPS();

$shipEngine->connectUps($ups);
// returns "CarrierId"
```

For FedEx, there is no factory to generate the class. This is because, like UPS, FedEx takes too many
parameters to easily create an interface.

```php
$fedex = new jsamhall\ShipEngine\Carriers\FedEx();

$shipEngine->connectFedEx($fedex);
// returns "CarrierId"
```

### Removing Carriers
In order to remove Carriers, you need the `CarrierCode` class that represents the Carrier you wish to delete
as well as the `carrierId`, which usually begins with `se-######`.

```php
$stamps = new jsamhall\ShipEngine\Carriers\CarrierCode("usps");

$response = $shipEngine->removeCarrier($stamps, "se-333333");

if ($response->isSuccesful()) // removed w/ no error.
```

### Voiding Labels
In order to void a label, you need a `LabelId` object.

```php
$labelId = new jsamhall\ShipEngine\Labels\LabelId("asdfadsf");

$response = $shipEngine->voidLabel($labelId); // Labels\VoidResponse
```

### Rates
In order to get a rate, you need a `RateId` object.

```php
$labelId = new jsamhall\ShipEngine\Rating\RateId("se-99999999");

$response = $shipEngine->getRate($labelId); // Rating\Rate
```

## Information
Please visit https://www.shipengine.com/ for information regarding, and to sign up for the ShipEngine platform.

Please visit https://docs.shipengine.com/docs for ShipEngine's official API documentation.

This project is in no way associated to or endorsed by ShipEngine, ShipStation or any of their partners.

ShipEngine and ShipStation are registered trademarks. All rights reserved.
