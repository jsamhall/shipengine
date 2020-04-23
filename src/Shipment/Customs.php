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

class Customs
{
    const CONTENT_GIFT = 'gift';
    const CONTENT_MERCHANDISE = 'merchandise';
    const CONTENT_RETURNED_GOODS = 'returned_goods';
    const CONTENT_DOCUMENTS = 'documents';
    const CONTENT_SAMPLE = 'sample';

    const NON_DELIVERY_ABANDONED = 'treat_as_abandoned';
    const NON_DELIVERY_SENDER = 'return_to_sender';

    /**
     * @var string
     */
    protected $contents;

    /**
     * @var string
     */
    protected $nonDelivery;

    /**
     * @var CustomItem[]
     */
    protected $customItems;

    /**
     * Customs constructor.
     * @param string $contents
     * @param string $nonDelivery
     * @param array $items
     */
    public function __construct(string $contents, string $nonDelivery, array $items)
    {
        $this
            ->setContents($contents)
            ->setNonDelivery($nonDelivery)
            ->setCustomItems($items);
    }

    /**
     * @param string $contents
     * @return Customs
     */
    public function setContents(string $contents): self
    {
        $allowed = [
            self::CONTENT_GIFT, self::CONTENT_MERCHANDISE,
            self::CONTENT_RETURNED_GOODS, self::CONTENT_DOCUMENTS, self::CONTENT_SAMPLE
        ];

        if (! in_array($contents, $allowed)) {
            throw new \InvalidArgumentException($contents . ' is not a valid "contents" property. See the constants "CONTENT_" in this class for allowed options.');
        }

        $this->contents = $contents;
        return $this;
    }

    /**
     * @param string $nonDelivery
     * @return Customs
     */
    public function setNonDelivery(string $nonDelivery): self
    {
        $allowed = [
            self::NON_DELIVERY_ABANDONED, self::NON_DELIVERY_SENDER
        ];

        if (! in_array($nonDelivery, $allowed)) {
            throw new \InvalidArgumentException($nonDelivery . ' is not a valid "non_delivery" property. See the constants "NON_DELIVERY" in this class for allowed options.');
        }

        $this->nonDelivery = $nonDelivery;
        return $this;
    }

    /**
     * @param array $items
     * @return Customs
     */
    public function setCustomItems(array $items): self
    {
        $this->customItems = $items;
        return $this;
    }

    /**
     * @param array $items
     * @return Customs
     */
    public function addCustomItems(array $items): self
    {
        $this->customItems = array_merge($this->customItems, $items);
        return $this;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @return string
     */
    public function getNonDelivery(): string
    {
        return $this->nonDelivery;
    }

    /**
     * @return CustomItem[]
     */
    public function getCustomItems(): array
    {
        return $this->customItems;
    }
}
