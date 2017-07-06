## About
A PHP wrapper for the accessing and interacting with the ShipEngine v1 API.

## How to use
```php
$shipEngine = new jsamhall\ShipEngine('your_shipengine_api_key');
$carriers = $shipEngine->listCarriers();
```

## Addresses
ShipEngine expects a certain address format. This Library offers the `Address\AddressDto` class which conforms
to this format. All methods that require an Address as part of the request (e.g., validation, rating, labels etc.)
expect an `Address\AddressDto` in order to ensure consistency and compatibility.

You should write an implementation `Address\FormatterInterface` that extracts data from your domain-specific
Address in the format expected by the ShipEngine API. This implementation is a constructor argument for the
`Address\Factory` class which will translate your Domain's Address Model to an instance of `Address\AddressDto`

## Information
Please visit https://www.shipengine.com/ for information regarding, and to sign up for the ShipEngine platform.

Please visit https://docs.shipengine.com/docs for ShipEngine's official API documentation.

This project is in no way associated to or endorsed by ShipEngine, ShipStation or any of their partners.

ShipEngine and ShipStation are registered trademarks. All rights reserved.