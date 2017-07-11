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

namespace jsamhall\ShipEngine\Carriers\USPS;

use jsamhall\ShipEngine\Carriers;

/**
 * Class ServiceCode
 *
 * Named constructors for USPS service codes in ShipEngine
 * 
 * @package jsamhall\ShipEngine\Carriers\USPS
 */
class ServiceCode extends Carriers\ServiceCode
{
    private static $MEDIA_MAIL = 'usps_media_mail';
    private static $PARCEL_SELECT = 'usps_parcel_select';
    private static $FIRST_CLASS_MAIL = 'usps_first_class_mail';
    private static $FIRST_CLASS_MAIL_INTERNATIONAL = 'usps_first_class_mail_international';
    private static $PRIORITY_MAIL = 'usps_priority_mail';
    private static $PRIORITY_MAIL_INTERNATIONAL = 'usps_priority_mail_international';
    private static $PRIORITY_MAIL_EXPRESS = 'usps_priority_mail_express';
    private static $PRIORITY_MAIL_EXPRESS_INTERNATIONAL = 'usps_priority_mail_express_international';

    public static function mediaMail()
    {
        return new static(self::$MEDIA_MAIL);
    }

    public static function parcelSelect()
    {
        return new static(self::$PARCEL_SELECT);
    }

    public static function firstClassMail()
    {
        return new static(self::$FIRST_CLASS_MAIL);
    }

    public static function firstClassMailInternational()
    {
        return new static(self::$FIRST_CLASS_MAIL_INTERNATIONAL);
    }

    public static function priorityMail()
    {
        return new static(self::$PRIORITY_MAIL);
    }

    public static function priorityMailInternational()
    {
        return new static(self::$PRIORITY_MAIL_INTERNATIONAL);
    }

    public static function priorityMailExpress()
    {
        return new static(self::$PRIORITY_MAIL_EXPRESS);
    }

    public static function priorityMailExpressInternational()
    {
        return new static(self::$PRIORITY_MAIL_EXPRESS_INTERNATIONAL);
    }
}