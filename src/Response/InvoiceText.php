<?php
/**
 * Copyright (c) 2016 Martin Aarhof
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Altapay\Api\Response;

use Altapay\Api\Response\Embeds\Address;
use Altapay\Api\Response\Embeds\TextInfo;

class InvoiceText extends AbstractResponse
{

    protected $childs = [
        'TextInfos' => [
            'class' => TextInfo::class,
            'array' => 'TextInfo'
        ],
        'Address' => [
            'class' => Address::class,
            'array' => false
        ]
    ];

    public $AccountOfferMinimumToPay;

    public $AccountOfferText;

    public $BankAccountNumber;

    public $LogonText;

    public $OcrNumber;

    public $MandatoryInvoiceText;

    public $InvoiceNumber;

    public $CustomerNumber;

    /**
     * @var \DateTime
     */
    public $InvoiceDate;

    /**
     * @var \DateTime
     */
    public $DueDate;

    /**
     * @var TextInfo[]
     */
    public $TextInfos;

    /**
     * @var Address
     */
    public $Address;

    /**
     * @param string $InvoiceDate
     * @return InvoiceText
     */
    public function setInvoiceDate($InvoiceDate)
    {
        $this->InvoiceDate = new \DateTime($InvoiceDate);
        return $this;
    }

    /**
     * @param string $DueDate
     * @return InvoiceText
     */
    public function setDueDate($DueDate)
    {
        $this->DueDate = new \DateTime($DueDate);
        return $this;
    }
}