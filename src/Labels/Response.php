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
     * The ID of the Carrier within ShipEngine that produced this Label
     *
     * @var ShipEngine\Carriers\CarrierId
     */
    protected $carrierId;

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
     * The cost of the Shipment + Insurance
     *
     * @var ShipEngine\ValueObject\Amount
     */
    protected $totalCost;

    /**
     * The Tracking Number for this Label
     *
     * @var string|null
     */
    protected $trackingNumber;

    /**
     * The Carrier Code for this Label.
     *
     * @var ShipEngine\Carriers\CarrierCode
     */
    protected $carrierCode;

    /**
     * The Tracking Status for this Label.
     *
     * @var string|null
     */
    protected $trackingStatus;

    /**
     * The URL from which this Label can be downloaded
     *
     * @var string
     */
    protected $labelDownloadUrl;

    /**
     * The URL from which the required Form can be downloaded.
     *
     * @var string|null
     */
    protected $formDownloadUrl;

    /**
     * The URL from which the insurance claim form can be downloaded.
     *
     * @var string|null
     */
    protected $insuranceClaimUrl;

    /**
     * The Service Code for this label.
     *
     * @var ShipEngine\Carriers\ServiceCode
     */
    protected $serviceCode;

    /**
     * The Package Code for this label.
     *
     * @var ShipEngine\Carriers\PackageCode
     */
    protected $packageCode;

    /**
     * The flag for whether the Label is International.
     *
     * @var boolean
     */
    protected $isInternational;

    /**
     * The flag for whether the Label is a return Label.
     *
     * @var boolean
     */
    protected $isReturnLabel;

    /**
     * The flag for whether the Label is voided.
     * @var boolean
     */
    protected $isVoided;

    /**
     * The DateTime representing the time the Label was voided, defaulted to null.
     *
     * @var \DateTime|null
     */
    protected $voidedDate;

    public function __construct(array $labelResponse)
    {
        parent::__construct($labelResponse, 200);

        $this->id = new LabelId($labelResponse['label_id']);
        $this->shipmentId = new ShipEngine\Shipment\ShipmentId($labelResponse['shipment_id']);
        $this->carrierId = new ShipEngine\Carriers\CarrierId($labelResponse['carrier_id']);

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

        $this->totalCost = new ShipEngine\ValueObject\Amount(
            $labelResponse['shipment_cost']['amount'] + $labelResponse['insurance_cost']['amount'],
            $labelResponse['insurance_cost']['currency']
        );

        // @todo good cases for ValueObjects?
        $this->status = $labelResponse['status'];
        $this->trackingNumber = $labelResponse['tracking_number'];
        $this->trackingStatus = $labelResponse['tracking_status'];

        $this->labelDownloadUrl = $labelResponse['label_download']['href'];
        $this->formDownloadUrl = $labelResponse['form_download']['href'] ?? null;
        $this->insuranceClaimUrl = $labelResponse['insurance_claim']['href'] ?? null;

        $this->serviceCode = new ShipEngine\Carriers\ServiceCode($labelResponse['service_code']);
        $this->packageCode = new ShipEngine\Carriers\PackageCode($labelResponse['package_code']);
        $this->carrierCode = new ShipEngine\Carriers\CarrierCode($labelResponse['carrier_code']);

        $this->isInternational = (bool) $labelResponse['is_international'];
        $this->isReturnLabel = (bool) $labelResponse['is_return_label'];
        $this->isVoided = (bool) $labelResponse['voided'];

        $this->voidedDate = empty($labelResponse['voided_at']) ? null : new \DateTime($labelResponse['voided_at']);
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

    public function getCarrierId(): ShipEngine\Carriers\CarrierId
    {
        return $this->carrierId;
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

    public function getTotalCost(): ShipEngine\ValueObject\Amount
    {
        return $this->totalCost;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function getTrackingStatus(): ?string
    {
        return $this->trackingStatus;
    }

    public function getLabelDownloadUrl(): string
    {
        return $this->labelDownloadUrl;
    }

    public function getFormDownloadUrl(): ?string
    {
        return $this->formDownloadUrl;
    }

    public function getInsuranceClaimUrl(): ?string
    {
        return $this->insuranceClaimUrl;
    }

    public function getServiceCode(): string
    {
        return $this->serviceCode;
    }

    public function getPackageCode(): string
    {
        return $this->packageCode;
    }

    public function getCarrierCode(): ShipEngine\Carriers\CarrierCode
    {
        return $this->carrierCode;
    }

    public function isInternational(): bool
    {
        return $this->isInternational;
    }

    public function isReturnLabel(): bool
    {
        return $this->isReturnLabel;
    }

    public function isVoided(): bool
    {
        return $this->isVoided;
    }

    public function getVoidedDate(): ?\DateTime
    {
        return $this->voidedDate;
    }
}
