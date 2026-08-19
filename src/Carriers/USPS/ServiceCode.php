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

namespace jsamhall\ShipEngine\Carriers\USPS;

use jsamhall\ShipEngine\Carriers\ServiceCode as ServiceCodeInterface;
use jsamhall\ShipEngine\Carriers\ServiceCodeComparison;

/**
 * USPS service codes in ShipEngine.
 */
enum ServiceCode: string implements ServiceCodeInterface
{
    use ServiceCodeComparison;

    case MediaMail = 'usps_media_mail';
    case ParcelSelect = 'usps_parcel_select';
    case FirstClassMail = 'usps_first_class_mail';
    case FirstClassMailInternational = 'usps_first_class_mail_international';
    case PriorityMail = 'usps_priority_mail';
    case PriorityMailInternational = 'usps_priority_mail_international';
    case PriorityMailExpress = 'usps_priority_mail_express';
    case PriorityMailExpressInternational = 'usps_priority_mail_express_international';

    public function code(): string
    {
        return $this->value;
    }
}
