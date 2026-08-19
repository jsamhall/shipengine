<?php

declare(strict_types=1);

/**
 * ShipEngine API Wrapper
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Carriers;

/**
 * A carrier service code.
 *
 * Implemented both by the carrier-specific backed enums (a known, closed set of service codes, e.g.
 * {@see FedEx\ServiceCode}) and by {@see CustomServiceCode}, which wraps an arbitrary service code
 * string returned by the ShipEngine API.
 */
interface ServiceCode
{
    /**
     * The service code as sent to / received from the ShipEngine API (e.g. "ups_ground").
     */
    public function code(): string;

    /**
     * Whether this service code refers to the same carrier service as another, regardless of whether
     * either side is an enum case or a {@see CustomServiceCode}.
     */
    public function equals(ServiceCode $other): bool;
}
