## Change Log
# Version 1.0.0
Introduce functionality for Voiding Labels

Introduce Package\Weight and Package\Dimensions DTOs

Change Package \jsamhall\ShipEngine\Shipment\Package constructor arguments to accept Weight and Dimension DTOs. This change is not backwards compatible.

# Version 1.0.1
Add accessors for Carrier Code and Service Code to Label Response DTO

# Version 1.2.0
Add package Tracking support.

Introduce `ShipEngine::trackPackage($carrierCode, $trackingNumber)` which returns a `Tracking\TrackingInformation` object exposing standardized status (`Tracking\StatusCode`), carrier status, ship/estimated/actual delivery dates, and an array of `Tracking\Event` scans. Requires the ShipEngine Advanced plan or higher.

# Version 2.0.0
**Breaking release.** Requires **PHP 8.1+** and modernizes the library around enums and strict typing.

### Requirements
- Now requires `php: ^8.1` (previously unconstrained).
- Dev dependency updated to `phpunit/phpunit: ^10.5`; a characterization test suite was added.

### Enums replacing constants / value objects / named constructors
- `Tracking\StatusCode` is now a backed enum (`StatusCode::Delivered`, …). The old class constants
  (`StatusCode::DELIVERED`) are removed — use the cases or their `->value`. `StatusCode::describe(string)`
  is retained and each case has `->label()`.
- `Shipment\Package\Weight` / `Dimensions` now take `WeightUnit` / `DimensionUnit` enums instead of the
  `UNIT_*` string constants. Invalid units are no longer representable.
- `Labels\InsuranceProvider` and `Carriers\DeliveryConfirmation` are now backed enums; their named
  constructors (`InsuranceProvider::none()`, `DeliveryConfirmation::signature()`) become cases
  (`InsuranceProvider::None`, `DeliveryConfirmation::Signature`).
- `AddressVerification\VerificationResult` status constants became the `VerificationStatus` enum
  (`VerificationResult::getStatus()` returns it).
- Rating\Shipment address-validation constants became the `Rating\AddressValidation` enum.

### Carrier service codes — interface + enums
- `Carriers\ServiceCode` is now an **interface** (`code(): string`, `equals()`), not a class.
- `Carriers\FedEx\ServiceCode`, `UPS\ServiceCode`, `USPS\ServiceCode` are backed enums implementing it;
  named constructors (`::ground()`) become cases (`::Ground`). Wire values are unchanged.
- `Carriers\CustomServiceCode` wraps an arbitrary service-code string (e.g. one from the API).
- Service codes are no longer `Stringable` — use `->code()` instead of `(string)` casting (PHP enums
  cannot define `__toString()`).

### Advanced options — interface + factories
- `Carriers\AdvancedOption` is now an interface; `Carriers\CustomAdvancedOption` is the default
  implementation and `Carriers\AdvancedOptionCode` enumerates the known option codes.
- `FedEx\AdvancedOption` / `UPS\AdvancedOption` are factory classes; their named constructors
  (`::saturdayDelivery()`, `::billToAccount($account)`, …) are unchanged.

### Addresses
- `address_residential_indicator` is now always present in the address payload and defaults to
  **residential** (`ResidentialIndicator::Yes`). Call
  `setAddressResidentialIndicator(ResidentialIndicator::No)` for a commercial address.
  `setAddressResidentialIndicator()` now requires a `ResidentialIndicator` (previously a string).

### Typing
- `declare(strict_types=1)` throughout; typed properties, return types, and constructor promotion /
  `readonly` on the value objects. `Address` getters and `Api\Response::getData()` now reflect their
  real (sometimes nullable) types.

### Bug fixes
- `Rate::isGuaranteedService()` now returns the real value (was always `null`; also removed a PHP 8.2
  dynamic-property deprecation).
- `RequestFactory::voidLabel()` uses `CURLOPT_CUSTOMREQUEST => 'PUT'` (was `CURLOPT_PUT`).
- `Weight` no longer raises a `TypeError` on an invalid unit (moot now that units are an enum).