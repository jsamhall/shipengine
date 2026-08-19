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

namespace jsamhall\ShipEngine\Labels\Void;

class Response
{
    private bool $approved;

    private string $message;

    public function __construct(array $voidResponse)
    {
        $this->approved = (bool) ($voidResponse['approved'] ?? false);
        $this->message = (string) ($voidResponse['message'] ?? 'No message found in Void Response');
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
