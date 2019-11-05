# CCG Sales API

CCG Sales API is a PHP-Library to communicate to Cost Containment Group - Development and Production Endpoints. CCG Sales API is maintained by NexusVC, LLC. and is a stand-alone community contributed library.

## Documentation

Documentation is currently pending. Simple usage examples are provided below.

## Installation

You can install **ccg-sales-api** via composer or by downloading the source.

#### Via Composer:

**ccg-sales-api** is available on Packagist as the
[`nexusvc/ccg-sales-api`](https://packagist.org/packages/nexusvc/ccg-sales-api) package:

```
composer require nexusvc/ccg-sales-api
```

## Versions

`ccg-sales-api` uses a modified version of [Semantic Versioning](https://semver.org) for all changes. [See this document](VERSIONS.md) for details.

## Quickstart

### Authenticating
```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$username = 'FIRSTNAME.LASTNAME'; // Sales Agent Username from CCG
$password = 'XXXXXXXXXXXXXXXXXX'; // Sales Agent Password from CCG
$npn      = 'XXXXXXXX';           // Sales Agent NPN

// Authenticating and getting a token
$ccg = new CCG;

$ccg->auth()->login(
    $username,
    $password,
    $npn
);

return $ccg->auth();
```
