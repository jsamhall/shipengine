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

namespace jsamhall\ShipEngine\Shipment;


use BadMethodCallException;
use Exception;
use InvalidArgumentException;
use jsamhall\ShipEngine\Shipment\Package\Dimensions;
use jsamhall\ShipEngine\Shipment\Package\InsuredValue;
use jsamhall\ShipEngine\Shipment\Package\LabelMessage;
use jsamhall\ShipEngine\Shipment\Package\Weight;

class Package
{
    /**
     * @var Weight
     */
    private $weight;

    /**
     * @var Dimensions
     */
    private $dimensions;

    private ?InsuredValue $insuredValue;

    /**
     * @var LabelMessage[]
     */
    private array $messages = [];

    /**
     * @param Weight $weight
     * @param Dimensions $dimensions
     */
    public function __construct(Weight $weight, Dimensions $dimensions, ?InsuredValue $insuredValue = null)
    {
        $this->weight = $weight;
        $this->dimensions = $dimensions;
        $this->insuredValue = $insuredValue;
    }

    /**
     * Label Messages are rendered on Shipping Labels in the footer.
     * Up to (3) messages can be added, after which an error will be thrown.
     * @param string $message The message to display. If the message exceeds 35 characters, it will be rejected.
     */
    public function addLabelMessage(string $message)
    {
        $messageCount = count($this->messages);
        if (count($this->messages) === 3) {
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

    /**
     * @return bool
     */
    public function hasLabelMessages(): bool
    {
        return count($this->messages) > 0;
    }

    /**
     * @return Weight
     */
    public function getWeight(): Weight
    {
        return $this->weight;
    }

    /**
     * @return Dimensions
     */
    public function getDimensions(): Dimensions
    {
        return $this->dimensions;
    }

    /**
     * @return float
     */
    public function getWeightAmount()
    {
        return $this->weight->getValue();
    }

    /**
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weight->getUnit();
    }

    public function hasInsuredValue(): bool
    {
        return !is_null($this->insuredValue);
    }

    public function getInsuredValue(): ?InsuredValue
    {
        return $this->insuredValue;
    }
}