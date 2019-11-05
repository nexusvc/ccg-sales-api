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

### List Product Categories
```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

return $ccg->quote()->categories();
```

### List Products in Category
```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

// Set Product Params
$params = [
    'type' => $CategoryType, // Set the Type returned from categories
];

return $ccg->quote($params)->products();
```

### Create Payable Token - Payment Method
Payable is a payment method that can either be a debit/credit card and/or a bank account. The library will determine the type based on the params passed when instantiating a new payable. It will also validate against common formats. Once the payable it attached to the order object it will automatically be encrypted and will always return a tokenized format.

```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

// Credit or Debit
$payable = new $ccg->payable([
    'account' => 'XXXXXXXXXXXXXXXX',
    'cvc'     => 'XXX',
    'expiration' => [
        'month' => 'XX',
        'year'  => 'XXXX'
    ],
]);

// BankAccount
$payable = new $ccg->payable([
    'account' => 'XXXXXXXXXXXX',
    'routing' => 'XXXXXXXXXXXX'
]);

// Attach Payable to Order
$ccg->order->addPayable($payable);
```

### Selecting Products
Products can be attached to the order object similar to a payable. You can filter products by passing params as shown in section `List Products in Category` but you may also filter those returned items using a collection and then using the `addToOrder($ccg->order)` method will push that product into the order object. You may also use `addProduct($product)` method on the order object.

```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

$params = [
    'state'        => 'FL', 
    'type'         => 'LimitedMedical',
    'coverageType' => 1
];

// Example filter by groupId
$product = $ccg->quote($params)->products()->filter(function($item) {
    return $item->groupId === 12362;
})->first();

// Attach Product to Order
// You must reference the $ccg-order
$product->addToOrder($ccg->order);

// Alternative: Attach Product to Order
$ccg->order->addProduct($product);
```


