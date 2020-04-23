<?php
/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Carriers\USPS;

class Factory
{
    /**
     * @param string $nickname
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public static function factory(string $nickname, string $username, string $password)
    {
        return (new StampsDotCom())
            ->setNickname($nickname)
            ->setUsername($username)
            ->setPassword($password);
    }
}
