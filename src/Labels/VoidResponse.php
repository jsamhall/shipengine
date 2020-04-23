<?php
/**
 * Bag Riders Web Store
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 * @copyright Bag Riders LLC
 */

namespace jsamhall\ShipEngine\Labels;

use jsamhall\ShipEngine;

class VoidResponse extends ShipEngine\Api\Response
{
    /** @var boolean */
    protected $approved;

    /** @var string */
    protected $message;

    public function __construct(array $labelResponse)
    {
        parent::__construct($labelResponse, 200);

        $this->approved = $labelResponse['approved'];
        $this->message = $labelResponse['message'];
    }

    /**
     * @return bool
     */
    public function getApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
