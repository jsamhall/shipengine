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

namespace jsamhall\ShipEngine\Address;

/**
 * Whether an address is residential.
 *
 * ShipEngine represents this as "yes" / "no" / "unknown". Addresses built by this library default
 * to {@see self::Yes} (residential); it is up to the caller to explicitly declare an address as
 * non-residential.
 */
enum ResidentialIndicator: string
{
    case Yes = 'yes';
    case No = 'no';
    case Unknown = 'unknown';
}
