<?php

declare(strict_types=1);

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

class Response extends ShipEngine\Api\Response
{
    /**
     * The ID of this Label within the ShipEngine application
     */
    protected LabelId $id;

    /**
     * The status of the Label
     */
    protected string $status;

    /**
     * The ID of the Shipment within ShipEngine that this Label is for
     */
    protected ShipEngine\Shipment\ShipmentId $shipmentId;

    /**
     * The Date on which the Shipment is to be picked up by the Carrier
     */
    protected \DateTime $shipDate;

    /**
     * The Date on which this Label was created
     */
    protected \DateTime $createdAt;

    /**
     * The cost of the Shipment
     */
    protected ShipEngine\ValueObject\Amount $shipmentCost;

    /**
     * The cost of Insurance added to this Shipment
     */
    protected ShipEngine\ValueObject\Amount $insuranceCost;

    /**
     * The Tracking Number for this Label
     */
    protected string $trackingNumber;

    /**
     * The URL from which this Label can be downloaded
     */
    protected string $labelDownloadUrl;

    /**
     * The Carrier Code for which this Label is handled (e.g., ups)
     */
    protected string $carrierCode;

    /**
     * The Carrier's Service Code for this Label (e.g., ups_ground)
     */
    protected string $serviceCode;

    public function __construct(array $labelResponse)
    {
        parent::__construct($labelResponse);

        $this->id = new LabelId($labelResponse['label_id']);
        $this->shipmentId = new ShipEngine\Shipment\ShipmentId($labelResponse['shipment_id']);

        $this->shipDate = new \DateTime($labelResponse['ship_date']);
        $this->createdAt = new \DateTime($labelResponse['created_at']);

        $this->shipmentCost = new ShipEngine\ValueObject\Amount(
            $labelResponse['shipment_cost']['amount'],
            $labelResponse['shipment_cost']['currency']
        );

        $this->insuranceCost = new ShipEngine\ValueObject\Amount(
            $labelResponse['insurance_cost']['amount'],
            $labelResponse['insurance_cost']['currency']
        );

        $this->carrierCode = $labelResponse['carrier_code'];
        $this->serviceCode = $labelResponse['service_code'];

        // @todo good cases for ValueObjects?
        $this->status = $labelResponse['status'];
        $this->trackingNumber = $labelResponse['tracking_number'];
        $this->labelDownloadUrl = $labelResponse['label_download']['href'];
    }

    public function getId(): LabelId
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getShipmentId(): ShipEngine\Shipment\ShipmentId
    {
        return $this->shipmentId;
    }

    public function getShipDate(): \DateTime
    {
        return $this->shipDate;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getShipmentCost(): ShipEngine\ValueObject\Amount
    {
        return $this->shipmentCost;
    }

    public function getInsuranceCost(): ShipEngine\ValueObject\Amount
    {
        return $this->insuranceCost;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function getLabelDownloadUrl(): string
    {
        return $this->labelDownloadUrl;
    }

    public function getCarrierCode(): string
    {
        return $this->carrierCode;
    }

    public function getServiceCode(): string
    {
        return $this->serviceCode;
    }
}
