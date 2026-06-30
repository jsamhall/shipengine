# Spec: Package Tracking (carrier code + tracking number)

Status: **Proposed** · Target package version: **1.2.0** · Author: John Hall

## 1. Goal

Add the ability to retrieve tracking information for a shipment using the
**carrier code + tracking number** approach documented at
<https://www.shipengine.com/docs/tracking/>.

Single new public call on the facade:

```php
$tracking = $shipEngine->trackPackage('usps', '9405511899223197428490');

echo $tracking->getStatusDescription();          // "Delivered"
echo $tracking->getStatusCode();                 // "DE"
echo $tracking->isDelivered() ? 'done' : 'open'; // true
foreach ($tracking->getEvents() as $event) {
    printf("%s — %s (%s)\n",
        $event->getOccurredAt()->format('c'),
        $event->getDescription(),
        $event->getCityLocality()
    );
}
```

Out of scope (can be follow-ups): webhook/subscription tracking (`/v1/tracking/start`,
`/v1/tracking/stop`), tracking by `label_id`, and tracking by `package_id`.

## 2. API reference (the endpoint we are wrapping)

| | |
|---|---|
| Method | `GET` |
| Path | `/v1/tracking` |
| Query params | `carrier_code` (required), `tracking_number` (required) |
| Auth | `api-key: <key>` header (already handled by `RequestFactory::initRequest`) |
| Plan | **Advanced plan or higher** — our account qualifies |
| Precondition | The carrier must be connected to the ShipEngine account, even for labels created elsewhere |

### Response body (HTTP 200)

```jsonc
{
  "tracking_number": "9405511899223197428490",
  "tracking_url": "https://tools.usps.com/go/TrackConfirmAction?...",
  "status_code": "DE",
  "status_description": "Delivered",
  "carrier_code": "usps",
  "carrier_id": 12345,
  "carrier_status_code": "01",
  "carrier_status_description": "Your item was delivered...",
  "carrier_detail_code": null,
  "ship_date": "2024-01-08T00:00:00Z",            // nullable
  "estimated_delivery_date": "2024-01-12T00:00:00Z", // nullable
  "actual_delivery_date": "2024-01-12T15:21:00Z",  // nullable
  "exception_description": null,                    // nullable
  "events": [
    {
      "occurred_at": "2024-01-12T15:21:00Z",
      "carrier_occurred_at": "2024-01-12T10:21:00",
      "description": "Delivered, Front Door/Porch",
      "city_locality": "BURBANK",
      "state_province": "CA",
      "postal_code": "91521",
      "country_code": "US",
      "company_name": "",
      "signer": "",
      "event_code": "DEL",
      "carrier_status_code": "01",
      "carrier_status_description": "Delivered...",
      "latitude": 34.18,    // nullable
      "longitude": -118.31  // nullable
    }
  ]
}
```

### Standardized `status_code` values

| Code | Meaning |
|------|---------|
| `AC` | Accepted |
| `IT` | In Transit |
| `DE` | Delivered |
| `EX` | Exception |
| `UN` | Unknown |
| `AT` | Attempted Delivery |
| `NY` | Not Yet In System |
| `SP` | Delivered to collection location |

### Error behavior

If the carrier is not connected, the carrier code is unknown, or the request is
otherwise rejected, ShipEngine returns a body containing an `errors` array.
`Api\Request::send()` already detects this and throws `Exception\ApiErrorResponse`.
**No new error handling is required** — the tracking call inherits it. An unknown
*tracking number* for a connected carrier typically returns HTTP 200 with
`status_code: "NY"` rather than an error.

## 3. How it fits the existing architecture

The package uses a consistent 3-layer shape per feature:

```
ShipEngine (facade)  ->  Api\RequestFactory (builds cURL Request)  ->  Feature\Response object
   getRates()              getShipmentRates()                            Rating\RateResponse
   createLabel()           createLabel()                                 Labels\Response
   trackPackage()  (NEW)   getTrackingInformation()  (NEW)               Tracking\TrackingInformation (NEW)
```

We add a new `Tracking\` namespace mirroring `Rating\` and `Labels\`. No existing
class changes its behavior; the only edits to existing files are **additive**
(one facade method, one factory method, and an optional `buildUrl` enhancement).

## 4. New files

```
src/Tracking/TrackingInformation.php   // top-level response object
src/Tracking/Event.php                 // one tracking scan/event
src/Tracking/StatusCode.php            // status-code constants + helpers
tests/Tracking/TrackingInformationTest.php
tests/Tracking/EventTest.php
```

Reused existing types: `Carriers\CarrierCode`, `Carriers\CarrierId`,
`ValueObject\Id`, `Exception\ApiErrorResponse`.

## 5. Component specs

### 5.1 `RequestFactory::getTrackingInformation()` (edit `src/Api/RequestFactory.php`)

This is the only piece of genuinely new plumbing because no existing GET endpoint
passes a query string. Build the query with `http_build_query` (URL-encodes the
tracking number safely) and follow the same GET shape as `listCarriers()`.

```php
/**
 * Build a Request to the Tracking "GetTrackingInformation" endpoint.
 *
 * @link https://www.shipengine.com/docs/tracking/
 *
 * @param string $carrierCode   e.g. "usps", "fedex", "ups"
 * @param string $trackingNumber
 * @return Request
 */
public function getTrackingInformation(string $carrierCode, string $trackingNumber)
{
    $query = http_build_query([
        'carrier_code'    => $carrierCode,
        'tracking_number' => $trackingNumber,
    ]);

    $url = $this->buildUrl('tracking') . '?' . $query;

    return $this->initRequest($url, [
        CURLOPT_HTTPGET => true,
    ]);
}
```

**Optional hardening (recommended, not required):** `initRequest()` reads
`$params[CURLOPT_POST]` even on GET requests, which emits an "Undefined array key"
notice under PHP 8. While touching this file, change line ~276 to
`if (($params[CURLOPT_POST] ?? false) === true)`. This is pre-existing behavior
shared by all GET endpoints; fixing it is a clean side-benefit but can be skipped
to keep the change strictly additive.

### 5.2 `ShipEngine::trackPackage()` (edit `src/ShipEngine.php`)

Accept either a raw string carrier code or a `Carriers\CarrierCode` value object,
matching how `RateResponse::getRatesByCarrierCode()` already accepts both.

```php
/**
 * Get tracking information for a package by carrier code and tracking number.
 *
 * @link https://www.shipengine.com/docs/tracking/
 *
 * @param string|Carriers\CarrierCode $carrierCode
 * @param string $trackingNumber
 * @return Tracking\TrackingInformation
 * @throws Exception\ApiErrorResponse  if the carrier is not connected / request rejected
 * @throws Exception\ApiRequestFailed  on transport failure
 */
public function trackPackage($carrierCode, string $trackingNumber): Tracking\TrackingInformation
{
    $carrierCode = (string) $carrierCode; // accepts CarrierCode (has __toString) or string

    $response = $this->requestFactory
        ->getTrackingInformation($carrierCode, $trackingNumber)
        ->send();

    return new Tracking\TrackingInformation($response->getData());
}
```

### 5.3 `Tracking\TrackingInformation` (new `src/Tracking/TrackingInformation.php`)

Top-level response object. Mirrors `Labels\Response` (extends `Api\Response` so the
raw array stays accessible) but **null-guards every date** because tracking dates
are nullable.

Properties / getters:

| API field | Property | Getter | Type |
|---|---|---|---|
| `tracking_number` | `$trackingNumber` | `getTrackingNumber()` | `string` |
| `tracking_url` | `$trackingUrl` | `getTrackingUrl()` | `?string` |
| `status_code` | `$statusCode` | `getStatusCode()` | `string` |
| `status_description` | `$statusDescription` | `getStatusDescription()` | `?string` |
| `carrier_code` | `$carrierCode` | `getCarrierCode()` | `Carriers\CarrierCode` |
| `carrier_id` | `$carrierId` | `getCarrierId()` | `?Carriers\CarrierId` |
| `carrier_status_code` | `$carrierStatusCode` | `getCarrierStatusCode()` | `?string` |
| `carrier_status_description` | `$carrierStatusDescription` | `getCarrierStatusDescription()` | `?string` |
| `carrier_detail_code` | `$carrierDetailCode` | `getCarrierDetailCode()` | `?string` |
| `ship_date` | `$shipDate` | `getShipDate()` | `?\DateTime` |
| `estimated_delivery_date` | `$estimatedDeliveryDate` | `getEstimatedDeliveryDate()` | `?\DateTime` |
| `actual_delivery_date` | `$actualDeliveryDate` | `getActualDeliveryDate()` | `?\DateTime` |
| `exception_description` | `$exceptionDescription` | `getExceptionDescription()` | `?string` |
| `events` | `$events` | `getEvents()` | `Tracking\Event[]` |

Convenience helpers (delegate to `StatusCode`):

- `isDelivered(): bool`  → `status_code === 'DE'`
- `hasException(): bool`  → `status_code === 'EX'`
- `isInTransit(): bool`  → `status_code === 'IT'`
- `getLatestEvent(): ?Event`  → last element of `events` (API returns chronological order)

Constructor sketch (note the private null-safe date helper):

```php
class TrackingInformation extends ShipEngine\Api\Response
{
    // ... typed properties ...

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->trackingNumber           = $data['tracking_number'];
        $this->trackingUrl              = $data['tracking_url'] ?? null;
        $this->statusCode               = $data['status_code'];
        $this->statusDescription        = $data['status_description'] ?? null;
        $this->carrierCode              = new ShipEngine\Carriers\CarrierCode($data['carrier_code']);
        $this->carrierId                = isset($data['carrier_id'])
            ? new ShipEngine\Carriers\CarrierId($data['carrier_id'])
            : null;
        $this->carrierStatusCode        = $data['carrier_status_code'] ?? null;
        $this->carrierStatusDescription = $data['carrier_status_description'] ?? null;
        $this->carrierDetailCode        = $data['carrier_detail_code'] ?? null;
        $this->exceptionDescription     = $data['exception_description'] ?? null;

        $this->shipDate              = $this->toDateTime($data['ship_date'] ?? null);
        $this->estimatedDeliveryDate = $this->toDateTime($data['estimated_delivery_date'] ?? null);
        $this->actualDeliveryDate    = $this->toDateTime($data['actual_delivery_date'] ?? null);

        $this->events = array_map(function ($event) {
            return new Event($event);
        }, $data['events'] ?? []);
    }

    private function toDateTime(?string $value): ?\DateTime
    {
        return ($value === null || $value === '') ? null : new \DateTime($value);
    }
}
```

> Note on `CarrierId`: the tracking response's `carrier_id` is a **numeric** id
> (e.g. `12345`), unlike the string `se-…` ids used elsewhere. `Carriers\CarrierId`
> extends `ValueObject\Id`, which stores the value untyped and only `__toString`s it,
> so wrapping the integer is safe. If wrapping feels wrong here, fall back to storing
> it as a plain `?int` — call this out at review (see §9, decision D3).

### 5.4 `Tracking\Event` (new `src/Tracking/Event.php`)

One scan in the `events` array. Plain value object, constructor takes the raw event
array. Dates use the same null-safe helper.

| API field | Getter | Type |
|---|---|---|
| `occurred_at` | `getOccurredAt()` | `?\DateTime` |
| `carrier_occurred_at` | `getCarrierOccurredAt()` | `?\DateTime` |
| `description` | `getDescription()` | `?string` |
| `city_locality` | `getCityLocality()` | `?string` |
| `state_province` | `getStateProvince()` | `?string` |
| `postal_code` | `getPostalCode()` | `?string` |
| `country_code` | `getCountryCode()` | `?string` |
| `company_name` | `getCompanyName()` | `?string` |
| `signer` | `getSigner()` | `?string` |
| `event_code` | `getEventCode()` | `?string` |
| `carrier_status_code` | `getCarrierStatusCode()` | `?string` |
| `carrier_status_description` | `getCarrierStatusDescription()` | `?string` |
| `latitude` | `getLatitude()` | `?float` |
| `longitude` | `getLongitude()` | `?float` |

### 5.5 `Tracking\StatusCode` (new `src/Tracking/StatusCode.php`)

The package targets PHP 7.4+ (no native enums — see `RatingError`'s typed property
but no enum usage). Model status codes as **class constants** plus a small
description map, consistent with `Amount::CURRENCY_USD` and the carrier
`ServiceCode` constants style.

```php
class StatusCode
{
    const ACCEPTED            = 'AC';
    const IN_TRANSIT          = 'IT';
    const DELIVERED           = 'DE';
    const EXCEPTION           = 'EX';
    const UNKNOWN             = 'UN';
    const ATTEMPTED_DELIVERY  = 'AT';
    const NOT_YET_IN_SYSTEM   = 'NY';
    const DELIVERED_TO_COLLECTION_LOCATION = 'SP';

    private const DESCRIPTIONS = [
        self::ACCEPTED           => 'Accepted',
        self::IN_TRANSIT         => 'In Transit',
        self::DELIVERED          => 'Delivered',
        self::EXCEPTION          => 'Exception',
        self::UNKNOWN            => 'Unknown',
        self::ATTEMPTED_DELIVERY => 'Attempted Delivery',
        self::NOT_YET_IN_SYSTEM  => 'Not Yet In System',
        self::DELIVERED_TO_COLLECTION_LOCATION => 'Delivered to Collection Location',
    ];

    public static function describe(string $code): string
    {
        return self::DESCRIPTIONS[$code] ?? 'Unknown';
    }
}
```

`TrackingInformation::isDelivered()` etc. compare against these constants so callers
never hard-code the two-letter strings.

## 6. Edge cases

1. **In-transit package** → `actual_delivery_date` is null; date getters must return
   `null`, not throw. (Covered by `toDateTime` guard.)
2. **`NY` — not yet in system** → valid 200 response with an empty or tiny `events`
   array. `getEvents()` returns `[]`; `getLatestEvent()` returns `null`.
3. **Carrier not connected / bad carrier code** → `errors` array → existing
   `ApiErrorResponse` is thrown by `Request::send()`. Document that callers should
   catch it.
4. **Missing optional fields** (`tracking_url`, `carrier_detail_code`, lat/long) →
   all read with `?? null`.
5. **`exception_description` set** → surfaced via `getExceptionDescription()`;
   `hasException()` is driven by `status_code === 'EX'`.

## 7. Backwards compatibility & risk

- Purely additive: one new namespace, two additive method insertions. No existing
  signature changes. **No breaking change** → minor version bump to `1.2.0`.
- The only edit to shared code is the new `getTrackingInformation()` method and the
  optional one-line `initRequest` notice fix. Risk: low.

## 8. Testing plan

Follow the existing PHPUnit setup (`phpunit.xml`, dev dep `phpunit 4.0.*`). Note the
pinned PHPUnit 4 predates namespaced `TestCase`; mirror whatever the current tests
use. Tests operate on **canned JSON arrays** (no live API calls):

- `TrackingInformationTest`
  - maps a full delivered-package fixture → asserts every getter
  - in-transit fixture → asserts `getActualDeliveryDate() === null`, `isInTransit()` true
  - `NY` fixture with no `events` → `getEvents() === []`, `getLatestEvent() === null`
  - accepts a `CarrierCode` object and a raw string for the facade arg
- `EventTest`
  - full event fixture → all getters
  - event with null `latitude`/`longitude` → `?float` getters return null
- `StatusCodeTest`
  - `describe()` for each known code + the unknown fallback

Add fixtures under `tests/Tracking/fixtures/*.json` captured from a real Advanced-plan
response (sanitized).

## 9. Decisions to confirm before implementation

- **D1 — Facade method name.** Proposed `trackPackage($carrierCode, $trackingNumber)`.
  Alternatives: `getTrackingInformation(...)` (verbose, matches factory) or
  `track(...)` (terse). *Recommendation: `trackPackage`.*
- **D2 — Response class name.** Proposed `Tracking\TrackingInformation`. Alternative
  `Tracking\Response` (matches `Labels\Response`) but less descriptive when the var is
  passed around. *Recommendation: `TrackingInformation`.*
- **D3 — `carrier_id` modeling.** Wrap the numeric id in `Carriers\CarrierId`
  (consistent) vs. expose plain `?int`. *Recommendation: wrap it.*
- **D4 — Date return type.** Nullable `\DateTime` in the constructor (this spec) vs.
  `Rate`'s string-or-`asDateTime` flag. *Recommendation: nullable `\DateTime` — cleaner,
  and tracking dates are always full timestamps.*

## 10. Implementation checklist

1. [ ] `src/Tracking/StatusCode.php` — constants + `describe()`
2. [ ] `src/Tracking/Event.php` — getters + null-safe dates
3. [ ] `src/Tracking/TrackingInformation.php` — getters, helpers, `toDateTime`
4. [ ] `RequestFactory::getTrackingInformation()` (+ optional notice fix)
5. [ ] `ShipEngine::trackPackage()`
6. [ ] Tests + fixtures under `tests/Tracking/`
7. [ ] README "Tracking" section with a usage example
8. [ ] `CHANGELOG.md` entry + bump `composer.json` version to `1.2.0`
