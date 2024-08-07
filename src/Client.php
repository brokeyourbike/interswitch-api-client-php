<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch;

use Psr\SimpleCache\CacheInterface;
use GuzzleHttp\ClientInterface;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\Interswitch\Responses\TransferResponse;
use BrokeYourBike\Interswitch\Responses\TokenResponse;
use BrokeYourBike\Interswitch\Interfaces\TransactionInterface;
use BrokeYourBike\Interswitch\Interfaces\ConfigInterface;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\HasSourceModel\HasSourceModelTrait;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;
    use HasSourceModelTrait;

    private ConfigInterface $config;
    private CacheInterface $cache;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient, CacheInterface $cache)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    public function authTokenCacheKey(): string
    {
        return get_class($this) . ':authToken:';
    }

    public function getAuthToken(): string
    {
        if ($this->cache->has($this->authTokenCacheKey())) {
            $cachedToken = $this->cache->get($this->authTokenCacheKey());
            if (is_string($cachedToken)) {
                return $cachedToken;
            }
        }

        $response = $this->fetchAuthTokenRaw();

        $this->cache->set(
            $this->authTokenCacheKey(),
            $response->access_token,
            (int) $response->expires_in / 2
        );

        return (string) $response->access_token;
    }

    public function fetchAuthTokenRaw(): TokenResponse
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            \GuzzleHttp\RequestOptions::AUTH => [
                $this->config->getUsername(),
                $this->config->getPassword(),
            ],
            \GuzzleHttp\RequestOptions::BODY => [
                'grant_type' => 'client_credentials',
                'scope' => 'profile',
            ],
        ];

        $response = $this->httpClient->request(HttpMethodEnum::POST->value, $this->config->getAuthUrl(), $options);
        return new TokenResponse($response);
    }

    /**
     * @link https://docs.interswitchgroup.com/docs/single-transfer
     */
    public function transfer(TransactionInterface $transaction): TransferResponse
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
                'TerminalId' => $this->config->getTerminalId(),
            ],
            \GuzzleHttp\RequestOptions::JSON => [
                'transferCode' => $transaction->getReference(),
                'mac' => Mac::generate($transaction),
                'initiatingEntityCode' => 'PBL',
                'initiation' => [
                    'amount' => (string) $transaction->getCents(),
                    'currencyCode' => (string) $transaction->getCurrency(),
                    'paymentMethodCode' => 'CA',
                    'channel' => '7',
                ],
                'termination' => [
                    'amount' => (string) $transaction->getCents(),
                    'currencyCode' => (string) $transaction->getCurrency(),
                    'countryCode' => $transaction->getRecipientCountry(),
                    'entityCode' => $transaction->getRecipientBankCode(),
                    'accountReceivable' => [
                        'accountNumber' => $transaction->getRecipientAccountNumber(),
                        'accountType' => '00',
                    ],
                    'paymentMethodCode' => 'AC',
                ],
                'sender' => [
                    'phone' => $transaction->getSenderPhone(),
                    'email' => $transaction->getSenderEmail(),
                    'lastname' => $transaction->getSenderLastName(),
                    'othernames' => $transaction->getSenderFirstName(),
                ],
                'beneficiary' => [
                    'lastname' => $transaction->getRecipientLastName(),
                    'othernames' => $transaction->getRecipientFirstName(),
                ],
            ],
        ];

        if ($transaction instanceof SourceModelInterface){
            $options[\BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL] = $transaction;
        }

        $response = $this->httpClient->request(
            HttpMethodEnum::POST->value,
            (string) $this->resolveUriFor($this->config->getUrl(), 'v5/transactions/TransferFunds'),
            $options
        );

        return new TransferResponse($response);
    }
}
