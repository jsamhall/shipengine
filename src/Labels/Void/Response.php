<?php

namespace jsamhall\ShipEngine\Labels\Void;

class Response
{
    private $approved;

    private $message;

    public function __construct(array $voidResponse)
    {
        $approved = $voidResponse['approved'] ?? false;
        $message = $voidResponse['message'] ?? 'No message found in Void Response';

        $this->approved = $approved;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return (bool)$this->approved;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return (string)$this->message;
    }
}