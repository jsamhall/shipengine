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

namespace jsamhall\ShipEngine\Labels;

/**
 * Insurance providers supported by ShipEngine.
 */
enum InsuranceProvider: string
{
    case None = 'none';
    case Shipsurance = 'shipsurance';
    case Carrier = 'carrier';
    case ThirdParty = 'third_party';
}
