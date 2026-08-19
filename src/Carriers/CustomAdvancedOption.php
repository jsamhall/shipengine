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
 * Default {@see AdvancedOption} implementation: an arbitrary (code, value) pair.
 */
class CustomAdvancedOption implements AdvancedOption
{
    public function __construct(
        private readonly string $code,
        private readonly mixed $value
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
