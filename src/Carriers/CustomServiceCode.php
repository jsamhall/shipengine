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
 * A {@see ServiceCode} for an arbitrary carrier service code string — e.g. one returned by the
 * ShipEngine API that does not correspond to a known FedEx/UPS/USPS enum case.
 */
class CustomServiceCode implements ServiceCode
{
    use ServiceCodeComparison;

    public function __construct(private readonly string $code)
    {
    }

    public function code(): string
    {
        return $this->code;
    }
}
