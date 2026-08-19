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

namespace jsamhall\ShipEngine\Shipment\Package;

/**
 * Units of length accepted by the ShipEngine API for package dimensions.
 */
enum DimensionUnit: string
{
    case Inch = 'inch';
    case Centimeter = 'centimeter';
}
