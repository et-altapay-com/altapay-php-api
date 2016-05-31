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

namespace Altapay\ApiTest\Api;

use Altapay\Api\Response\ChargeSubscription as ChargeSubscriptionDocument;
use Altapay\Api\Response\Transaction;
use Altapay\Api\Exceptions\ClientException;
use Altapay\Api\ChargeSubscription;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ChargeSubscriptionTest extends AbstractApiTest
{

    /**
     * @return ChargeSubscription
     */
    protected function getChargeSubscription()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/setupsubscription.xml'))
        ]));

        return (new ChargeSubscription())
            ->setClient($client)
            ->setAuthentication($this->getAuth())
        ;
    }

    public function test_charge_subscription()
    {
        $api = $this->getChargeSubscription();
        $api->setTransactionId(123);
        $this->assertInstanceOf(ChargeSubscriptionDocument::class, $api->call());
    }

    /**
     * @depends test_charge_subscription
     */
    public function test_charge_subscription_data()
    {
        $api = $this->getChargeSubscription();
        $api->setTransactionId(123);
        /** @var ChargeSubscriptionDocument $response */
        $response = $api->call();
        $this->assertEquals('Success', $response->Result);
        $this->assertCount(2, $response->Transactions);
    }

    public function test_charge_subscription_querypath()
    {
        $transaction = new Transaction();
        $transaction->TransactionId = 456;

        $api = $this->getChargeSubscription();
        $api->setTransaction($transaction);
        $api->call();
        $request = $api->getRawRequest();

        $this->assertEquals($this->getExceptedUri('chargeSubscription/'), $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals(456, $parts['transaction_id']);

        $api = $this->getChargeSubscription();
        $api->setTransactionId('helloworld');
        $api->setAmount(200.5);
        $api->setReconciliationIdentifier('my identifier');
        $api->call();
        $request = $api->getRawRequest();
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('helloworld', $parts['transaction_id']);
        $this->assertEquals(200.5, $parts['amount']);
        $this->assertEquals('my identifier', $parts['reconciliation_identifier']);

        $api = $this->getChargeSubscription();
        $api->setTransactionId('my trans id has spaces');
        $api->call();
        $request = $api->getRawRequest();
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('my trans id has spaces', $parts['transaction_id']);
    }

    public function test_charge_subscription_transaction_handleexception()
    {
        $this->setExpectedException(ClientException::class);

        $transaction = new Transaction();
        $transaction->TransactionId = 456;

        $client = $this->getClient($mock = new MockHandler([
            new Response(400, ['text-content' => 'application/xml'])
        ]));

        $api = (new ChargeSubscription())
            ->setClient($client)
            ->setAuthentication($this->getAuth())
            ->setTransactionId(123)
        ;
        $api->call();
    }

}