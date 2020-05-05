<?php
declare(strict_types = 1);

namespace jsamhall\ShipEngine;

use GuzzleHttp\Client;

/**
 * Class HttpClient
 * @package jsamhall\ShipEngine
 */
class HttpClient extends Client
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
}
