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

namespace jsamhall\ShipEngine\Labels;

use jsamhall\ShipEngine;

class RateLabel
{
    const LABEL_FORMAT_PDF = 'pdf';
    const LABEL_FORMAT_PNG = 'png';
    const LABEL_FORMAT_ZPL = 'zpl';

    const LABEL_LAYOUT_4x6 = '4x6';
    const LABEL_LAYOUT_LETTER = 'letter';

    const ADDRESS_VALIDATE_NONE = 'noValidation';
    const ADDRESS_VALIDATE_ONLY = 'validateOnly';
    const ADDRESS_VALIDATE_CLEAN = 'validateAndClean';

    /**
     * @var ShipEngine\Rating\RateId
     */
    protected $rateId;

    /**
     * Valid values are listed above with ADDRESS_VALIDATE_*
     *
     * @var string
     */
    protected $validateAddress;

    /**
     * Valid values are listed above with LABEL_LAYOUT_*
     *
     * @var string
     */
    protected $labelLayout;

    /**
     * Valid values are listed above with LABEL_FORMAT_*
     *
     * @var string
     */
    protected $labelFormat;

    /**
     * RateLabel constructor.
     * @param string $rateId
     * @param string $validateAddress
     * @param string $labelLayout
     * @param string $labelFormat
     */
    public function __construct(
        string $rateId,
        string $validateAddress = self::ADDRESS_VALIDATE_CLEAN,
        string $labelLayout = self::LABEL_LAYOUT_4x6,
        string $labelFormat = self::LABEL_FORMAT_PDF
    ) {
        $this->rateId = new ShipEngine\Rating\RateId($rateId);
        $this->setLabelFormat($labelFormat);
        $this->setLabelLayout($labelLayout);
        $this->setValidateAddress($validateAddress);
    }

    protected function setLabelFormat(string $format): void
    {
        if (! in_array($format, [self::LABEL_FORMAT_ZPL, self::LABEL_FORMAT_PNG, self::LABEL_FORMAT_PDF])) {
            throw new \InvalidArgumentException($format . ' value is not supported for RateLabel (label_format)');
        }
        $this->labelFormat = $format;
    }

    protected function setLabelLayout(string $size): void
    {
        if (! in_array($size, [self::LABEL_LAYOUT_4x6 , self::LABEL_LAYOUT_LETTER])) {
            throw new \InvalidArgumentException($size . ' value is not supported for RateLabel (label_layout)');
        }
        $this->labelLayout = $size;
    }

    protected function setValidateAddress(string $flag): void
    {
        if (! in_array($flag, [self::ADDRESS_VALIDATE_CLEAN, self::ADDRESS_VALIDATE_NONE, self::ADDRESS_VALIDATE_ONLY])) {
            throw new \InvalidArgumentException($flag . ' value is not supported for RateLabel (validate_address)');
        }
        $this->validateAddress = $flag;
    }

    public function getLabelFormat(): string
    {
        return $this->labelFormat;
    }

    public function getLabelLayout(): string
    {
        return $this->labelLayout;
    }

    public function getAddressValidation(): string
    {
        return $this->validateAddress;
    }

    public function getRateId(): ShipEngine\Rating\RateId
    {
        return $this->rateId;
    }
}