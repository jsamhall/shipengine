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
 * An advanced option for a Shipment.
 *
 * Unlike {@see ServiceCode}, an advanced option is a (code, value) pair where the value may be
 * supplied at runtime (e.g. a bill-to account number), so it is not modeled as an enum. See
 * {@see AdvancedOptionCode} for the known option codes and {@see CustomAdvancedOption} for the
 * default implementation.
 */
interface AdvancedOption
{
    public function getCode(): string;

    public function getValue(): mixed;
}
