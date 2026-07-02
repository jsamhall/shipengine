# ShipEngine Wrapper — PHP 8.1 Compatibility & Type-Modernization Audit (toward v2.0.0)

_Audited against PHP 8.3.20 (CLI). Target: lock the package to `php ^8.1` so it runs cleanly on
both 8.1 and 8.3, and adopt enums + modern type features. v2.0.0 is expected because several
changes are intentionally breaking._

## 0. Baseline

- `php -l` passes on **every** file in `src/` — there are **no fatal parse errors** under 8.3.
  All findings below are runtime behavior, deprecations, or design/typing improvements.
- There is **no `tests/` directory** (though `phpunit.xml` points at `./tests/`), and
  `phpunit/phpunit: 4.0.*` cannot even install on PHP 8. You are refactoring without a safety net.
- `composer.json` declares **no PHP constraint at all**, so nothing stops the package from being
  installed on an unsupported runtime today.

---

## 1. Tooling / packaging blockers (fix first)

| # | Item | Action |
|---|------|--------|
| 1.1 | No PHP version constraint | Add `"require": { "php": "^8.1", "ext-curl": "*", "ext-json": "*" }`. Use `^8.1` (>=8.1,<9), **not** `>=8.1` (which would allow a future 9.0). |
| 1.2 | PHPUnit 4.0.* is PHP-8-incompatible | Bump `require-dev` to `phpunit/phpunit: ^10.5` (PHPUnit 10 requires PHP 8.1+, matching our floor). |
| 1.3 | No tests exist | Add at least characterization tests for the `toArray()` payload builders and the response DTO constructors **before** the enum refactor — they are the pieces most likely to silently change shape. |
| 1.4 | `version: 1.2.0` in composer.json | Bump to `2.0.0`. (Also fine to drop the `version` key entirely and let a git tag define it — Composer prefers VCS tags.) |
| 1.5 | `phpunit.xml` uses removed attributes | `syntaxCheck`, `convertErrorsToExceptions`, `backupStaticAttributes` etc. were removed in PHPUnit 10 — regenerate the config with `--migrate-configuration`. |

---

## 2. PHP 8.x compatibility issues (runtime), ranked

### 2.1 HIGH — Dynamic property + silent functional bug (`Rating/Rate.php:188`)
```php
'guaranteed_service' => 'guaranteed_service',   // maps API key -> property name
```
The property is `$guaranteedService`, so this writes to a **non-existent** `$guaranteed_service`.
- **PHP 8.2+**: creating an undeclared property is deprecated (`E_DEPRECATED`); it becomes an error in a future major.
- **Functional bug (all versions)**: `isGuaranteedService()` reads `$this->guaranteedService`, which is never set → always `null`.
- Fix: change the map value to `'guaranteedService'`.

### 2.2 MEDIUM (latent) — `TypeError` in exception construction (`Shipment/Package/Weight.php:22`)
```php
throw new \InvalidArgumentException("Unit must be one of: %s", implode(',', $validUnits));
```
`Exception::__construct(string $message, int $code = 0, ?Throwable $previous = null)` — the second
argument is `$code:int`, but a **string** is passed. Under PHP 8 this throws
`TypeError: ...Argument #2 ($code) must be of type int, string given`, masking the real error. The
`%s` is also never interpolated. Only triggers on an invalid unit, so it's latent — but the failure
mode is exactly when you want a clear message. Fix:
```php
throw new \InvalidArgumentException(sprintf("Unit must be one of: %s", implode(', ', $validUnits)));
```
(The sibling `Dimensions.php:28` already does this correctly — good reference.)

### 2.3 LOW — cURL handle type doc (`Api/Request.php:23`)
`curl_init()` returns a `\CurlHandle` **object** on PHP 8, not a `resource`. Works fine; the
`@var resource` docblock is just wrong. When adding typed properties, type it `private \CurlHandle $curlHandle;`.

### 2.4 LOW — `strpos()` truthiness (`Rating/Rate.php:206`)
```php
if (strpos($key, '_amount')) { ... }
```
Relies on a truthy position. Safe for the current keys but fragile (position 0 would be falsy).
Replace with `str_contains($key, '_amount')` (PHP 8.0+, clearer intent).

### 2.5 LOW — Fragile positional hydration
`AvailableOption.php:51` (`array_values($optionData)`) and `MatchedAddress.php:25-37`
(`list(...) = array_values(...)`) depend on the API returning keys in a fixed order. Not a version
issue, but worth hardening to explicit keys while you're in here.

---

## 3. Enum refactor opportunities

There are **two categories**, and the distinction matters:

- **Closed sets** — a fixed, known universe of values → ideal `enum` candidates.
- **Open sets** — must wrap arbitrary strings returned by the API → must stay value objects.

The base `Carriers\ServiceCode`, `CarrierCode`, `PackageCode`, and `ValueObject\Id` are **open**:
they're constructed from arbitrary API responses (e.g. `Rate.php:175` does
`new ServiceCode($rateData['service_code'])`). **These cannot become enums.** They should instead
get the value-object modernization from §4.

### 3.1 Tier 1 — clean, self-contained enum conversions (low risk)

| Source | Today | Convert to | Notes |
|--------|-------|-----------|-------|
| `Tracking/StatusCode.php` | class + 8 consts + `$descriptions` map + `describe()` | `enum StatusCode: string` with a `label(): string` method | The textbook case. Keep a `describe(string): string` static shim for one release if downstream code calls it. `TrackingInformation` compares `=== StatusCode::DELIVERED` etc. — those become `=== StatusCode::Delivered->value` (statusCode is stored as the raw string) or store the enum. |
| `Shipment/Package/Weight.php` | `UNIT_OUNCE`/`UNIT_POUND` + `in_array` guard | `enum WeightUnit: string` | Enum type-hint replaces the manual validation entirely (and removes bug 2.2). |
| `Shipment/Package/Dimensions.php` | `UNIT_INCH`/`UNIT_CENTIMETER` + guard | `enum DimensionUnit: string` | Same benefit. |
| `Labels/InsuranceProvider.php` | value object, 4 static factories, `__toString` | `enum InsuranceProvider: string` | Cases: `None`, `Shipsurance`, `Carrier`, `ThirdParty`. **BC note:** `AbstractShipment::toArray():133` calls `->__toString()` — enums have `->value`, not `__toString`. Update that call site. |
| `Carriers/DeliveryConfirmation.php` | value object, 4 static factories, `__toString` | `enum DeliveryConfirmation: string` | **BC note:** `AbstractShipment::toArray():173` calls `->__toString()` → change to `->value`. |
| `AddressVerification/VerificationResult.php` | `STATUS_*` consts | `enum VerificationStatus: string` | Constructor stores `$status` from the API; store the enum via `VerificationStatus::from()`. |
| `Rating/Shipment.php` | `ADDRESS_*` consts | `enum AddressValidation: string` | Drives `validate_address` in the payload. |
| `ValueObject/Amount.php` | `CURRENCY_USD` const | `enum Currency: string` (optional) | Lower priority; single constant today. |

**Why enums here are a real win, not cosmetics:**
- Illegal values become unrepresentable at the type level (no more `in_array` guards / `TypeError`s).
- `Enum::from()/tryFrom()` gives you validated hydration from API strings for free.
- Methods can live on the enum (`StatusCode::Delivered->label()`), colocating the value↔label map.

### 3.2 Tier 2 — the carrier `ServiceCode` / `AdvancedOption` hierarchy (design decision required)

Today these use classic inheritance:
```
FedEx\ServiceCode  extends  Carriers\ServiceCode   (open value object)
UPS\ServiceCode    extends  Carriers\ServiceCode
USPS\ServiceCode   extends  Carriers\ServiceCode
FedEx\AdvancedOption extends Carriers\AdvancedOption
UPS\AdvancedOption   extends Carriers\AdvancedOption
```
**Constraint: a PHP `enum` cannot `extend` a class** (it may only `implement` interfaces). So the
carrier subclasses can't simply become enums while the base stays a class. Two viable paths:

**Option A — interface-based (recommended; cleaner long-term typing, bigger change):**
- Turn `Carriers\ServiceCode` into an **interface** (e.g. `ServiceCode { public function value(): string; }`).
- Add an open `Carriers\CustomServiceCode` (or `GenericServiceCode`) value object implementing it, used
  for arbitrary API-sourced codes (`Rate`, `Service`, `RateResponse::getRateByServiceCode`).
- Convert `FedEx\ServiceCode`, `UPS\ServiceCode`, `USPS\ServiceCode` to **backed enums implementing
  the interface**. Same for `AdvancedOption`.
- Call sites that type-hint `Carriers\ServiceCode` keep working (interface is satisfied by both the
  enums and the value object). This is the largest but most idiomatic 2.0 shape.

**Option B — additive convenience (lighter, less breaking):**
- Keep `Carriers\ServiceCode` as a value object (modernized per §4).
- Introduce carrier enums (`FedEx\ServiceCode` etc.) as **standalone** backed enums that no longer
  extend the base, plus a `->toServiceCode(): Carriers\ServiceCode` bridge for the existing APIs.
- Less type elegance, but smaller blast radius on downstream code.

This is the one fork that materially changes how big v2.0 is — worth deciding before coding.

---

## 4. Broader type-safety modernization (non-enum)

Apply consistently across `src/`:

1. **`declare(strict_types=1);`** at the top of every file. Behavior-affecting (breaking) but correct for a 2.0.
2. **Typed properties + constructor property promotion** (PHP 8.0). The many DTOs (`Address`,
   `Carrier`, `Service`, `Rate`, `Event`, `TrackingInformation`, `Amount`, `AdvancedOption`, the `Id`
   subclasses…) currently carry `@var` docblocks and untyped props. Promotion collapses most
   constructors substantially.
3. **`readonly` properties** (PHP 8.1) for the immutable value objects and response DTOs
   (`Id`, `Amount`, `ServiceCode`/`CarrierCode`/`PackageCode`, `Rate`, `Event`, etc.).
   ⚠️ Use readonly **properties**, not `readonly class` — the latter is PHP **8.2** and would break
   the 8.1 floor.
4. **Return types on every method** (many getters/setters still lack them, e.g. all of `Address`,
   `Carrier`, `Rate`).
5. **`: never` return type** (PHP 8.1) on `Exception::apiErrorResponse()` and
   `Exception::apiRequestFailed()` — they always throw. This also makes the redundant outer
   `throw Exception::apiRequestFailed(...)` in `Request.php:50,56` semantically clear (the factory
   already throws).
6. **Hydration vs. typed properties:** `Rate::__construct` assigns via `$this->$property = $value`
   only when the API key is present. With non-nullable typed properties, a missing key leaves the
   property *uninitialized* and later access throws `Error`. Give these properties nullable types
   and/or defaults, or hydrate explicitly.

### 4.1 Stay inside the 8.1 feature set
Because the package must also run on 8.1, use only features available there:
- ✅ Safe: enums, `readonly` **properties**, `never`, pure intersection types, first-class callable
  syntax, `new` in initializers, fibers.
- 🚫 Avoid (8.2+ only): `readonly class`, DNF types, `null`/`false`/`true` standalone types.
- 🚫 Avoid (8.3+ only): typed class constants, `#[\Override]`, dynamic class-constant fetch.

---

## 5. Misc cleanup / latent bugs to fold into 2.0 (not version-related)

- **`Api/RequestFactory.php:247` `voidLabel()` uses `CURLOPT_PUT => true`** without an input file.
  `CURLOPT_PUT` expects a body via `CURLOPT_INFILE`/`CURLOPT_INFILESIZE`; for a bodyless PUT the
  correct option is `CURLOPT_CUSTOMREQUEST => 'PUT'`. Likely a real bug — verify void actually works.
- **`Api/RequestFactory.php:309` `calculateContentLength()`** is an empty dead method — remove.
- **`Address::toArray()`** omits `address_residential_indicator` even though the property/setter
  exist and `MatchedAddress` populates it — either wire it into the payload or drop the field.
- **`Api/Response::getData()`** uses `== null` (loose). Prefer `=== null`.
- Update `README.md` / `CHANGELOG.md` with the 2.0 breaking-change list.

---

## 6. Suggested sequencing

1. **Packaging + safety net:** add `php ^8.1`, PHPUnit ^10.5, migrate `phpunit.xml`, write
   characterization tests for the `toArray()` builders and DTO hydration. Bump to 2.0.0-dev.
2. **Straight fixes** (no design debate): 2.1, 2.2, 2.4, and the §5 cleanups.
3. **Tier-1 enums** (§3.1) — self-contained, mechanical, high value.
4. **Decide Tier-2 strategy** (§3.2 Option A vs B), then execute.
5. **Type modernization sweep** (§4): `strict_types`, typed/promoted/readonly properties, return
   types, `never`.
6. Finalize CHANGELOG/README, tag `2.0.0`.
