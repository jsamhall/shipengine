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
 * Shared {@see ServiceCode::equals()} implementation, comparing on the wire code so an enum case and
 * a {@see CustomServiceCode} with the same value compare equal.
 */
trait ServiceCodeComparison
{
    public function equals(ServiceCode $other): bool
    {
        return $other->code() === $this->code();
    }
}
