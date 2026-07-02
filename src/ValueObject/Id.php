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

namespace jsamhall\ShipEngine\ValueObject;

class Id
{
    public function __construct(protected readonly string $id)
    {
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(Id $id): bool
    {
        return (string) $id === (string) $this;
    }
}
