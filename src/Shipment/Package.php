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

namespace jsamhall\ShipEngine\Shipment;

use BadMethodCallException;
use InvalidArgumentException;
use jsamhall\ShipEngine\Shipment\Package\Dimensions;
use jsamhall\ShipEngine\Shipment\Package\InsuredValue;
use jsamhall\ShipEngine\Shipment\Package\LabelMessage;
use jsamhall\ShipEngine\Shipment\Package\Weight;
use jsamhall\ShipEngine\Shipment\Package\WeightUnit;

class Package
{
    /**
     * @var LabelMessage[]
     */
    private array $messages = [];

    public function __construct(
        private readonly Weight $weight,
        private readonly Dimensions $dimensions,
        private readonly ?InsuredValue $insuredValue = null
    ) {
    }

    /**
     * Label Messages are rendered on Shipping Labels in the footer.
     * Up to (3) messages can be added, after which an error will be thrown.
     *
     * @param string $message The message to display. If the message exceeds 35 characters, it will be rejected.
     */
    public function addLabelMessage(string $message): void
    {
        $messageCount = count($this->messages);
        if ($messageCount === 3) {
            throw new BadMethodCallException('Cannot add label message; there are already 3 messages present');
        }

        $referenceNumber = $messageCount + 1; // e.g., 0 + 1 = 1, 1+1=2, 2+1 = 3, etc.
        $messageLabel = "reference" . $referenceNumber;

        try {
            $this->messages[] = new LabelMessage($messageLabel, $message);
        } catch (InvalidArgumentException $e) {
            // do nothing; message too long
        }
    }

    /**
     * @return LabelMessage[]
     */
    public function getLabelMessages(): array
    {
        return $this->messages;
    }

    public function hasLabelMessages(): bool
    {
        return count($this->messages) > 0;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }

    public function getDimensions(): Dimensions
    {
        return $this->dimensions;
    }

    public function getWeightAmount(): int|float
    {
        return $this->weight->getValue();
    }

    public function getWeightUnit(): WeightUnit
    {
        return $this->weight->getUnit();
    }

    public function hasInsuredValue(): bool
    {
        return ! is_null($this->insuredValue);
    }

    public function getInsuredValue(): ?InsuredValue
    {
        return $this->insuredValue;
    }
}
