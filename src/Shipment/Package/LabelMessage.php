<?php

namespace jsamhall\ShipEngine\Shipment\Package;

class LabelMessage
{
    private string $label;

    private string $message;

    /**
     * @param string $label
     * @param string $message
     */
    public function __construct(string $label, string $message)
    {
        $this->label = $label;
        $this->message = substr($message, 0, 60);
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