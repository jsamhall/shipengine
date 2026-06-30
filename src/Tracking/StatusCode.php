<?php
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
 * Class StatusCode
 *
 * Standardized ShipEngine tracking status codes
 *
 * @link https://www.shipengine.com/docs/tracking/
 *
 * @package jsamhall\ShipEngine\Tracking
 */
class StatusCode
{
    const ACCEPTED = 'AC';
    const IN_TRANSIT = 'IT';
    const DELIVERED = 'DE';
    const EXCEPTION = 'EX';
    const UNKNOWN = 'UN';
    const ATTEMPTED_DELIVERY = 'AT';
    const NOT_YET_IN_SYSTEM = 'NY';
    const DELIVERED_TO_COLLECTION_LOCATION = 'SP';

    /**
     * Map of status codes to human readable labels
     *
     * @var array
     */
    private static $descriptions = [
        self::ACCEPTED                         => 'Accepted',
        self::IN_TRANSIT                       => 'In Transit',
        self::DELIVERED                        => 'Delivered',
        self::EXCEPTION                        => 'Exception',
        self::UNKNOWN                          => 'Unknown',
        self::ATTEMPTED_DELIVERY               => 'Attempted Delivery',
        self::NOT_YET_IN_SYSTEM                => 'Not Yet In System',
        self::DELIVERED_TO_COLLECTION_LOCATION => 'Delivered to Collection Location',
    ];

    /**
     * Get a human readable label for the given status code
     *
     * @param string $code
     * @return string
     */
    public static function describe(string $code): string
    {
        return self::$descriptions[$code] ?? 'Unknown';
    }
}
