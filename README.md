# interswitch-api-client-php

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/interswitch-api-client-php)](https://github.com/brokeyourbike/interswitch-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/interswitch-api-client/downloads)](https://packagist.org/packages/brokeyourbike/interswitch-api-client)

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

$apiClient = new Client($config, $httpClient);
$apiClient->auth();
```

## Authors
- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[BSD-3-Clause License](https://github.com/brokeyourbike/interswitch-api-client-php/blob/main/LICENSE)
