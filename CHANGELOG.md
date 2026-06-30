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