# interswitch-api-client-php

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/interswitch-api-client-php)](https://github.com/brokeyourbike/interswitch-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/interswitch-api-client/downloads)](https://packagist.org/packages/brokeyourbike/interswitch-api-client)
[![Maintainability](https://api.codeclimate.com/v1/badges/dcc8ebb690c612c6452e/maintainability)](https://codeclimate.com/github/brokeyourbike/interswitch-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/dcc8ebb690c612c6452e/test_coverage)](https://codeclimate.com/github/brokeyourbike/interswitch-api-client-php/test_coverage)

Interswitch API Client for PHP

## Installation

```bash
composer require brokeyourbike/interswitch-api-client
```

## Usage

```php
use BrokeYourBike\Interswitch\Client;
use BrokeYourBike\Interswitch\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);
assert($psrCache instanceof \Psr\SimpleCache\CacheInterface);

$apiClient = new Client($config, $httpClient, $psrCache);
$apiClient->getAuthToken();
```

## Authors
- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[BSD-3-Clause License](https://github.com/brokeyourbike/interswitch-api-client-php/blob/main/LICENSE)
