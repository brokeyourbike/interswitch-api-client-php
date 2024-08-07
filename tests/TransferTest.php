<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Interswitch\Responses\TransferResponse;
use BrokeYourBike\Interswitch\Interfaces\TransactionInterface;
use BrokeYourBike\Interswitch\Interfaces\ConfigInterface;
use BrokeYourBike\Interswitch\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class TransferTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getTerminalId')->willReturn('terminal123');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "MAC":"*",
                "TransactionDate": "03/Feb/2023 03:47:064",
                "TransactionReference": "PBL|LOC|CA|ABP|AC",
                "Pin": null,
                "TransferCode": null,
                "ResponseCode": "90000",
                "ResponseCodeGrouping": "SUCCESSFUL"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $mockedCache->method('has')->willReturn(true);
        $mockedCache->method('get')->willReturn('secure-token');

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $requestResult = $api->transfer($transaction);
        $this->assertInstanceOf(TransferResponse::class, $requestResult);
        $this->assertEquals('90000', $requestResult->code);
    }
}