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

namespace jsamhall\ShipEngine\Tracking;

/**
 * Standardized ShipEngine tracking status codes.
 *
 * @link https://www.shipengine.com/docs/tracking/
 */
enum StatusCode: string
{
    case Accepted = 'AC';
    case InTransit = 'IT';
    case Delivered = 'DE';
    case Exception = 'EX';
    case Unknown = 'UN';
    case AttemptedDelivery = 'AT';
    case NotYetInSystem = 'NY';
    case DeliveredToCollectionLocation = 'SP';

    /**
     * A human readable label for this status code.
     */
    public function label(): string
    {
        return match ($this) {
            self::Accepted                      => 'Accepted',
            self::InTransit                     => 'In Transit',
            self::Delivered                     => 'Delivered',
            self::Exception                     => 'Exception',
            self::Unknown                       => 'Unknown',
            self::AttemptedDelivery             => 'Attempted Delivery',
            self::NotYetInSystem                => 'Not Yet In System',
            self::DeliveredToCollectionLocation => 'Delivered to Collection Location',
        };
    }

    /**
     * Get a human readable label for the given raw status code, falling back to "Unknown" for any
     * code that is not recognized.
     */
    public static function describe(string $code): string
    {
        return (self::tryFrom($code) ?? self::Unknown)->label();
    }
}
