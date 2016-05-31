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

namespace Altapay\Api\Traits;

use Altapay\Api\Response\Transaction;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Transaction resolver trait
 */
trait TransactionsTrait
{

    /**
     * The id of a specific payment.
     *
     * @param string $transactionId
     * @return $this
     */
    public function setTransactionId($transactionId)
    {
        $this->unresolvedOptions['transaction_id'] = $transactionId;
        return $this;
    }

    /**
     * The transaction of a specific payment.
     *
     * @param Transaction $transaction
     * @return $this
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->unresolvedOptions['transaction_id'] = $transaction;
        return $this;
    }

    /**
     * Resolve transaction option
     *
     * @param OptionsResolver $resolver
     */
    protected function setTransactionResolver(OptionsResolver $resolver)
    {
        $resolver->setRequired(['transaction_id']);
        $resolver->addAllowedTypes('transaction_id', ['string', 'int', Transaction::class]);
        $resolver->setNormalizer('transaction_id', function (Options $options, $value) {
            if ($value instanceof Transaction) {
                return $value->TransactionId;
            }
            return $value;
        });
    }
}