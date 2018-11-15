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

class CustomItem
{
    /**
     * @var string
     */
    protected $itemId;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $value;

    /**
     * @var string
     */
    protected $tariffCode;

    /**
     * @var string
     */
    protected $countryOfOrigin;

    /**
     * CustomItem constructor.
     * @param string $description
     * @param int $quantity
     * @param float $value
     * @param string $countryOfOrigin
     * @param null|string $tariffCode
     */
    public function __construct(string $description, int $quantity, float $value, string $countryOfOrigin, ?string $tariffCode)
    {
        $this
            ->setDescription($description)
            ->setQuantity($quantity)
            ->setValue($value)
            ->setCountryOfOrigin($countryOfOrigin)
            ->setTariffCode($tariffCode);
    }

    /**
     * @param string $itemId
     * @return CustomItem
     */
    public function setItemId(string $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @param string $description
     * @return CustomItem
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param int $quantity
     * @return CustomItem
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param float $value
     * @return CustomItem
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $country
     * @return CustomItem
     */
    public function setCountryOfOrigin(string $country): self
    {
        $this->countryOfOrigin = $country;

        return $this;
    }

    /**
     * @param null|string $code
     * @return CustomItem
     */
    public function setTariffCode(?string $code): self
    {
        $this->tariffCode = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCountryOfOrigin(): string
    {
        return $this->countryOfOrigin;
    }

    /**
     * @return null|string
     */
    public function getTariffCode(): ?string
    {
        return $this->tariffCode;
    }
}