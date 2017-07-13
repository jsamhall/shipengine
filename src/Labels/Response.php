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

class Response extends ShipEngine\Api\Response
{
    /**
     * The ID of this Label within the ShipEngine application
     * This should be used when making queries about this label
     *
     * @var LabelId
     */
    protected $id;

    /**
     * The status of the Label
     *
     * @var string
     */
    protected $status;

    /**
     * The ID of the Shipment within ShipEngine that this Label is for
     *
     * @var ShipEngine\Shipment\ShipmentId
     */
    protected $shipmentId;

    /**
     * The Date on which the Shipment is to be picked up by the Carrier
     *
     * @var \DateTime
     */
    protected $shipDate;

    /**
     * The Date on which this Label was created
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The cost of the Shipment
     *
     * @var ShipEngine\ValueObject\Amount
     */
    protected $shipmentCost;

    /**
     * The cost of Insurance added to this Shipment
     *
     * @var ShipEngine\ValueObject\Amount
     */
    protected $insuranceCost;

    /**
     * The Tracking Number for this Label
     *
     * @var string
     */
    protected $trackingNumber;

    /**
     * The URL from which this Label can be downloaded
     *
     * @var string
     */
    protected $labelDownloadUrl;

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

        // @todo good cases for ValueObjects?
        $this->status = $labelResponse['status'];
        $this->trackingNumber = $labelResponse['tracking_number'];
        $this->labelDownloadUrl = $labelResponse['label_download']['href'];
    }

    /**
     * @return LabelId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return ShipEngine\Shipment\ShipmentId
     */
    public function getShipmentId()
    {
        return $this->shipmentId;
    }

    /**
     * @return \DateTime
     */
    public function getShipDate()
    {
        return $this->shipDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return ShipEngine\ValueObject\Amount
     */
    public function getShipmentCost()
    {
        return $this->shipmentCost;
    }

    /**
     * @return ShipEngine\ValueObject\Amount
     */
    public function getInsuranceCost()
    {
        return $this->insuranceCost;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * @return string
     */
    public function getLabelDownloadUrl()
    {
        return $this->labelDownloadUrl;
    }
}