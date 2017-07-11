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

namespace jsamhall\ShipEngine\Carriers\FedEx;

use jsamhall\ShipEngine\Carriers;

/**
 * Class ServiceCode
 *
 * Named constructors for FedEx service codes in ShipEngine
 *
 * @package jsamhall\ShipEngine\Carriers\FedEx
 */
class ServiceCode extends Carriers\ServiceCode
{
    private static $GROUND = 'fedex_ground';
    private static $HOME_DELIVERY = 'fedex_home_delivery';
    private static $TWO_DAY = 'fedex_2day';
    private static $TWO_DAY_AM = 'fedex_2day_am';
    private static $EXPRESS_SAVER = 'fedex_express_saver';
    private static $STANDARD_OVERNIGHT = 'fedex_standard_overnight';
    private static $PRIORITY_OVERNIGHT = 'fedex_priority_overnight';
    private static $FIRST_OVERNIGHT = 'fedex_first_overnight';

    private static $INTERNATIONAL_GROUND = 'fedex_ground_international';
    private static $INTERNATIONAL_ECONOMY = 'fedex_international_economy';
    private static $INTERNATIONAL_PRIORITY = 'fedex_international_priority';
    private static $INTERNATIONAL_FIRST = 'fedex_international_first';

    private static $FREIGHT_ONE_DAY = 'fedex_1_day_freight';
    private static $FREIGHT_TWO_DAY = 'fedex_2_day_freight';
    private static $FREIGHT_THREE_DAY = 'fedex_3_day_freight';
    private static $FREIGHT_FIRST_OVERNIGHT = 'fedex_first_overnight_freight';
    private static $FREIGHT_INTERNATIONAL_ECONOMY = 'fedex_international_economy_freight';
    private static $FREIGHT_INTERNATIONAL_PRIORITY = 'fedex_international_priority_freight';

    public static function ground()
    {
        return new static(self::$GROUND);
    }

    public static function homeDelivery()
    {
        return new static(self::$HOME_DELIVERY);
    }

    public static function twoDay()
    {
        return new static(self::$TWO_DAY);
    }

    public static function twoDayAm()
    {
        return new static(self::$TWO_DAY_AM);
    }

    public static function expressSaver()
    {
        return new static(self::$EXPRESS_SAVER);
    }

    public static function standardOvernight()
    {
        return new static(self::$STANDARD_OVERNIGHT);
    }

    public static function priorityOvernight()
    {
        return new static(self::$PRIORITY_OVERNIGHT);
    }

    public static function firstOvernight()
    {
        return new static(self::$FIRST_OVERNIGHT);
    }

    public static function internationalGround()
    {
        return new static(self::$INTERNATIONAL_GROUND);
    }

    public static function internationalEconomy()
    {
        return new static(self::$INTERNATIONAL_ECONOMY);
    }

    public static function internationalPriority()
    {
        return new static(self::$INTERNATIONAL_PRIORITY);
    }

    public static function internationalFirst()
    {
        return new static(self::$INTERNATIONAL_FIRST);
    }

    public static function freightOneDay()
    {
        return new static(self::$FREIGHT_ONE_DAY);
    }

    public static function freightTwoDay()
    {
        return new static(self::$FREIGHT_TWO_DAY);
    }

    public static function freightThreeDay()
    {
        return new static(self::$FREIGHT_THREE_DAY);
    }

    public static function freightFirstOvernight()
    {
        return new static(self::$FREIGHT_FIRST_OVERNIGHT);
    }

    public static function freightInternationalEconomy()
    {
        return new static(self::$FREIGHT_INTERNATIONAL_ECONOMY);
    }

    public static function freightInternationalPriority()
    {
        return new static(self::$FREIGHT_INTERNATIONAL_PRIORITY);
    }
}