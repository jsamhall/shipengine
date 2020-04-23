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

namespace jsamhall\ShipEngine\ValueObject;

class Id
{
    /**
     * @var string
     */
    protected $id;

    /**
     * Id constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function equals(Id $id)
    {
        return $id->__toString() === $this->__toString();
    }
}
