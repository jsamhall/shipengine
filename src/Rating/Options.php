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

namespace jsamhall\ShipEngine\Rating;

use jsamhall\ShipEngine;

class Options implements \Countable, \IteratorAggregate
{
    /**
     * Array of CarrierIds
     *
     * @var string[]
     */
    protected $carriers = [];

    /**
     * Options constructor.
     *
     * @param mixed $carriers A carrierId or array of CarrierIds
     */
    public function __construct($carriers = [])
    {
        if (! is_array($carriers)) {
            $carriers = [$carriers];
        }

        array_map([$this, 'addCarrierId'], $carriers);
    }

    /**
     * Add a Carrier ID to retrieve Rates for
     *
     * @param string|ShipEngine\Carriers\CarrierId $carrierId
     * @return static $this
     */
    public function addCarrierId($carrierId)
    {
        $this->carriers[] = is_a($carrierId, ShipEngine\Carriers\CarrierId::class) 
            ? $carrierId->__toString() 
            : $carrierId;

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->carriers);
    }

    /**
     * @return \string[]
     */
    public function getIterator()
    {
        return $this->carriers;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'carrier_ids' => array_values($this->carriers)
        ];
    }
}