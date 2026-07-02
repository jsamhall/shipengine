<?php

declare(strict_types=1);

namespace jsamhall\ShipEngine\Shipment\Package;

use InvalidArgumentException;

class LabelMessage
{
    private readonly string $label;

    private readonly string $message;

    /**
     * @throws InvalidArgumentException if $message exceeds 35 characters
     */
    public function __construct(string $label, string $message)
    {
        if (strlen($message) > 35) {
            throw new InvalidArgumentException('Label Message cannot exceed 35 characters');
        }

        $this->label = $label;
        $this->message = $message;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
