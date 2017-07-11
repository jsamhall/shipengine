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

namespace jsamhall\ShipEngine\Carriers\UPS;

use jsamhall\ShipEngine\Carriers;

/**
 * Class ServiceCode
 *
 * Named constructors for UPS service codes in ShipEngine
 *
 * @package jsamhall\ShipEngine\Carriers\UPS
 */
class ServiceCode extends Carriers\ServiceCode
{
    private static $GROUND = 'ups_ground';
    private static $GROUND_INTERNATIONAL = 'ups_ground_international';
    private static $STANDARD_INTERNATIONAL = 'ups_standard_international';
    private static $WORLDWIDE_EXPRESS = 'ups_worldwide_express';
    private static $WORLDWIDE_EXPRESS_PLUS = 'ups_worldwide_express_plus';
    private static $WORLDWIDE_EXPEDITED = 'ups_worldwide_expedited';
    private static $WORLDWIDE_SAVER = 'ups_worldwide_saver';
    private static $NEXT_DAY_AIR = 'ups_next_day_air';
    private static $NEXT_DAY_AIR_EARLY_AM = 'ups_next_day_air_early_am';
    private static $NEXT_DAY_AIR_SAVER = 'ups_next_day_air_saver';
    private static $NEXT_DAY_AIR_INTERNATIONAL = 'ups_next_day_air_international';
    private static $SECOND_DAY_AIR = 'ups_2nd_day_air';
    private static $SECOND_DAY_AIR_AM = 'ups_2nd_day_air_am';
    private static $SECOND_DAY_AIR_INTERNATIONAL = 'ups_2nd_day_air_international';
    private static $THREE_DAY_SELECT = 'ups_3_day_select';

    public static function ground()
    {
        return new static(self::$GROUND);
    }

    public static function groundInternational()
    {
        return new static(self::$GROUND_INTERNATIONAL);
    }

    public static function standardInternational()
    {
        return new static(self::$STANDARD_INTERNATIONAL);
    }

    public static function worldwideExpress()
    {
        return new static(self::$WORLDWIDE_EXPRESS);
    }

    public static function worldwideExpressPlus()
    {
        return new static(self::$WORLDWIDE_EXPRESS_PLUS);
    }

    public static function worldwideExpedited()
    {
        return new static(self::$WORLDWIDE_EXPEDITED);
    }

    public static function worldwideSaver()
    {
        return new static(self::$WORLDWIDE_SAVER);
    }

    public static function nextDayAir()
    {
        return new static(self::$NEXT_DAY_AIR);
    }

    public static function nextDayAirEarlyAm()
    {
        return new static(self::$NEXT_DAY_AIR_EARLY_AM);
    }

    public static function nextDayAirSaver()
    {
        return new static(self::$NEXT_DAY_AIR_SAVER);
    }

    public static function nextDayAirInternational()
    {
        return new static(self::$NEXT_DAY_AIR_INTERNATIONAL);
    }

    public static function secondDayAir()
    {
        return new static(self::$SECOND_DAY_AIR);
    }

    public static function secondDayAirAm()
    {
        return new static(self::$SECOND_DAY_AIR_AM);
    }

    public static function secondDayAirInternational()
    {
        return new static(self::$SECOND_DAY_AIR_INTERNATIONAL);
    }

    public static function threeDaySelect()
    {
        return new static(self::$THREE_DAY_SELECT);
    }
}