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

# Version 1.2.1
Add LTL (Less Than Truckload) tracking support.

Introduce `ShipEngine::trackLtlShipment($carrierCode, $proNumber)` which returns a `Tracking\Ltl\TrackingInformation` object exposing the PRO number, ship-from/to postal codes, pickup date, estimated/actual delivery dates, signature name, and an array of `Tracking\Ltl\Event` scans. Uses ShipEngine's beta `v-beta/ltl/tracking` endpoint. See https://www.shipengine.com/docs/ltl/tracking/.