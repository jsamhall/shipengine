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

/**
 * Class StampsDotCom
 * @package jsamhall\ShipEngine\Carriers\USPS
 */
class StampsDotCom
{
    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $nickname
     * @return $this
     */
    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    protected function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    protected function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    protected function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'nickname' => $this->getNickname(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }
}
