<?php
/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */

namespace jsamhall\ShipEngine\Carriers\UPS;

/**
 * Class Invoice
 *
 * Only required if the account has received an invoice in the last 90 days.
 *
 * @package jsamhall\ShipEngine\Carriers\USPS
 */
class Invoice
{
    /**
     * @var \DateTime
     */
    protected $invoice_date;

    /**
     * @var string
     */
    protected $invoice_number;

    /**
     * @var string
     */
    protected $control_id;

    /**
     * @var double
     */
    protected $invoice_amount;

    /**
     * @param \DateTime $dateTime
     * @return Invoice
     */
    public function setInvoiceDate(\DateTime $dateTime): self
    {
        $this->invoice_date = $dateTime;
        return $this;
    }

    /**
     * @param string $number
     * @return Invoice
     */
    public function setInvoiceNumber(string $number): self
    {
        $this->invoice_number = $number;
        return $this;
    }

    /**
     * @param string $controlId
     * @return Invoice
     */
    public function setControlId(string $controlId): self
    {
        $this->control_id = $controlId;
        return $this;
    }

    /**
     * @param float $amount
     * @return Invoice
     */
    public function setInvoiceAmount(float $amount): self
    {
        $this->invoice_amount = $amount;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getInvoiceDate(): \DateTime
    {
        return $this->invoice_date;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber(): string
    {
        return $this->invoice_number;
    }

    /**
     * @return string
     */
    public function getControlId(): string
    {
        return $this->control_id;
    }

    /**
     * @return float
     */
    public function getInvoiceAmount(): float
    {
        return $this->invoice_amount;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'invoice_date'   => $this->getInvoiceDate(),
            'invoice_number' => $this->getInvoiceNumber(),
            'control_id'     => $this->getControlId(),
            'invoice_amount' => $this->getInvoiceAmount(),
        ];
    }
}