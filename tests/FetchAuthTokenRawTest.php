<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Interswitch\Responses\TokenResponse;
use BrokeYourBike\Interswitch\Interfaces\ConfigInterface;
use BrokeYourBike\Interswitch\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchAuthTokenRawTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getAuthUrl')->willReturn('https://auth.example/');
        $mockedConfig->method('getUsername')->willReturn('john');
        $mockedConfig->method('getPassword')->willReturn('p@ssword');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "access_token": "token123",
                "token_type": "bearer",
                "expires_in": 43199,
                "scope": "profile",
                "merchant_code": "*",
                "client_name": "*",
                "payable_id": "*",
                "jti": "*"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);
        $requestResult = $api->fetchAuthTokenRaw();

        $this->assertInstanceOf(TokenResponse::class, $requestResult);
        $this->assertEquals('token123', $requestResult->access_token);
        $this->assertEquals(43199, $requestResult->expires_in);
    }
}