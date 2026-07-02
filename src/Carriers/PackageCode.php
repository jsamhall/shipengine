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

class PackageCode
{
    public function __construct(private readonly string $code)
    {
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function equals(PackageCode $packageCode): bool
    {
        return (string) $packageCode === (string) $this;
    }
}
