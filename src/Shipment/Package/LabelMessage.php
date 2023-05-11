<?php

namespace jsamhall\ShipEngine\Shipment\Package;

use InvalidArgumentException;

class LabelMessage
{
    private string $label;

    private string $message;

    /**
     * @param string $label
     * @param string $message
     * @throws InvalidArgumentException if $message exceeds 35 characters
     */
    public function __construct(string $label, string $message)
    {
        $this->label = $label;
        if (strlen($message) > 35) {
            throw new InvalidArgumentException('Label Message cannot exceed 35 characters');
        }

        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return false|string
     */
    public function getMessage()
    {
        return $this->message;
    }
}