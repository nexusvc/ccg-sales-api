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

### List Product Verification Types
Will list the accepted verification types. This method will be used for future changes including capturing modified voice scripts based on the `order` object.
```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

return $ccg->quote()->verifications();
```

### Create Payable Token - Payment Method
Payable is a payment method that can either be a debit/credit card and/or a bank account. The library will determine the type based on the params passed when instantiating a new payable. It will also validate against common formats. Once a payable is attached to the `Order` object it will automatically be encrypted and will always return a tokenized format.

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
Products can be attached to the order object similar to a payable. You can filter products by passing params as shown in section List Products in Category but you may also filter those returned items using a collection and then using the `addToOrder($ccg->order)` method on the product will push that product into the order object passed by reference. You may also use `addProduct($product)` method on the order object to directly push a product.

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

### Creating an Applicant
When building the order you must attach 1 or more applicants. First, create a new applicant and push that applicant to the order similar to pushing a product.  You may use the `addToOrder($ccg->order)` method on the applicant directly or the `addApplicant($applicant)` method on the order object. You may also attach contactable objects to an applicant using the `addContactable($contact)` method on the applicant. Currently supported contact methods are `phone`,`email`,`address`.

Contactable object(s) will validate against common formats and USPS Address System.

```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

// Create applicant
$applicant = new $ccg->applicant([
    'firstName' => 'John', 
    'lastName'  => 'Doe',
    'dob'       => 'YYYY-MM-DD',
    'gender'    => 'Male',
    'relation'  => 'primary'
]);

// Phone
$phone = new $ccg->phone('XXXXXXXXXX');
// Email
$email = new $ccg->email('XXXXXXXXXX');

// Address
$addressParams = [
    'street1' => '1 GENERAL WAY',
    'street2' => 'APT 100',
    'city'    => 'Popular City',
    'state'   => 'FL',
    'zip'     => 'XXXXX'
];

$address = new $ccg->address($addressParams);

// You may daisy chain addContactable method
$applicant->addContactable($phone)
          ->addContactable($email)
          ->addContactable($address);

// Finally addApplicant($applicant) to Order
$applicant->addToOrder($ccg->order);

// Alternative: Adding Applicant
$ccg->order->addApplicant($applicant);
```

### Esign Verification
Adding an esign verification to the order

```php
$ccg = new CCG;

// Authenticate
// See Authenticating

// Add Product to Order
// Add Applicant(s) to Order
// Add Payable to Order

// Request Esign
$esign = $ccg->quote()->verifications('esign');

// Adding esign to order
$esign->addToOrder($ccg->order);

// Alternative: Adding esign to Order
$ccg->order->addVerification($esign);
```

### Esign Verification Invite
Once you are ready to request an esign invite you may do so using the verification type `esign` and the `invite($ccg)` method.

```php
$ccg = new CCG;

// Authenticate
// See Authenticating

// Add Product to Order
// Add Applicant(s) to Order
// Add Payable to Order

// Request Esign
$esign = $ccg->quote()->verifications('esign');

// Adding esign to order
$esign->addToOrder($ccg->order);

// Alternative: Adding esign to Order
$ccg->order->addVerification($esign);

// Sending Invitation
// Currently you must pass the main ccg object as reference
$esign->invite($ccg);
```

### Esign Verification Status
You may check a status of an `esign` verification by using the `verify-esign` type and the `byCaseId($ccg)` method.

```php
$ccg = new CCG;

// Authenticate
// See Authenticating

// Add Product to Order
// Add Applicant(s) to Order
// Add Payable to Order

// Request Esign
$esign = $ccg->quote()->verifications('esign');

// Adding esign to order
$esign->addToOrder($ccg->order);

// Alternative: Adding esign to Order
$ccg->order->addVerification($esign);

// Sending Invitation
// Currently you must pass the main ccg object as reference
$esign->invite($ccg);

// The above will return a caseID
// Using that caseId
$verify = $ccg->quote()->verifications('verify-esign');

return $verify->byCaseId($ccg, $esign->caseId);
```

### Order
The order object on the main class can be accessed via `$ccg->order`. The order object will contain the `applicants`,`products`,`verifications`,`payable` objects. You may need to make reference to the order object as shown above with methods like `addToOrder($ccg->order)` on specific objects. You may also output the entire order via the `toArray()` method. Here is an example of an order returned as an array.

```php
// Include the Library
use Nexusvc\CcgSalesApi\CCG;

$ccg = new CCG;

// Authenticate
// See Authenticating

// Add Product to Order
// Add Applicant(s) to Order
// Add Payable to Order
// Add Verification to Order

return json_encode($ccg->order->toArray());
```

Example output array as JSON:
```javascript
{
  "applicants": [
    {
      "id": null,
      "firstName": "JOHN",
      "lastName": "DOE",
      "dob": "YYYY-MM-DD",
      "gender": "Male",
      "relation": "primary",
      "contactable": {
        "address": {
          "street1": "1 GENERAL WAY",
          "street2": 'APT 100',
          "city": "Popular City",
          "state": "FL",
          "zip": "33196",
          "type": "address",
          "address": "1 GENERAL WAY APT 100 Popular City, FL 33196"
        },
        "phone": {
          "type": "phone",
          "phone": "+1XXXXXXXXXX"
        },
        "email": {
          "type": "email",
          "email": "XXXX@XXXX.COM"
        }
      }
    }
  ],
  "payable": {
    "account": "eyJpdiI6IjBDd0dlUjVjamlPWXQ1TmhabzZ0QVE9PSIsInZhbHVlIjoiVmxJZ1A3clpLNTc0YVBaUzNoNFc5Z21OaVRnT1wvWFwvaEhXY1VlTkxXTjNQMHRYZ2NmOXNQZlZPZUxBOFZSYjhYY2lZSkRZUEUyamVMVGNzbGhTTmtXTUJxR252aDBMaWNGZGppcU54T1ZWY1hYMktiOUtBcnRZR0w4N1dMSVdxQ0FkazNwT003SkJ1U1p1dEwxXC81U0ZneGMzSkF2d2hjbFwvMHhiM0FNSDJaZmY4V0tkWXpiSlNyRlZRT3B5WU4zN0NRMm9ydEtVWGhTZzliYkdwQW9Ka2c9PSIsIm1hYyI6ImIyMGE3MzJmMGMwMjdmNjM4Y2U0YjYzOTBmNjZkMGFjYmE0NDAzMjc0Nzc0NTYxYTg1MjkyYWUyYmNjMjJjNzcifQ=="
  },
  "products": [
    {
      "groupId": 12362,
      "brandName": "Health Shield",
      "planId": 5,
      "planName": "Choice",
      "coverageTypeName": "Individual",
      "coverageType": 1,
      "retailAmount": 269.95,
      "carrierId": 293,
      "carrierName": "Crum & Forster",
      "quoteType": "LM",
      "brandLogo": "https://www.mymemberinfo.com/brand/12362/HS-UCA.gif",
      "agentId": XXXXXXXXX,
      "associationName": "Unified Caring Association",
      "enrollmentPlans": [
        {
          "planId": 1,
          "planName": "Lifetime Association Fee",
          "retailAmount": 99.95
        }
      ]
    },
    {
      "isOneTimeCharge": true,
      "planId": 1,
      "planName": "Lifetime Association Fee",
      "retailAmount": 99.95
    },
    {
      "planId": 727,
      "planName": "Legacy 200",
      "groupId": 12365,
      "brandName": "UCA Add-Ons",
      "retailAmount": 76.9,
      "coverageName": "Individual",
      "coverageType": 1,
      "addOnType": "Accidental Death"
    }
  ],
  "verification": {
    "caseId": XXXX,
    "esignIPAddress": "XXX.XXX.XXX.XXX",
    "esignRecipient": "+1XXXXXXXXXX",
    "esignUserDevice": "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1",
    "firstName": "JOHN",
    "lastName": "DOE",
    "verificationStatus": "Completed",
    "eSignAcceptedDate": "2019-11-03T01:20:03.64-05:00"
  },
  "total": 446.8,
  "deposit": 99.95,
  "recurring": 346.85
}
```

### Schema Validation & Formatting
Built into the library is a schema validator and schema formatter. This is usually handled behind the scenes but can be access if necessary. You would use these to validate required structure(s) and/or output the `order` in a `current` or `deprecated` format for CCG endpoints. Currently a real life example would be choosing `version1` schema when `POST` to the esign invite. It will recreate the `order` object.

```php
// Pass the $ccg->order
$schema = new \Nexusvc\CcgSalesApi\Schema\Schema($ccg->order);

return $schema->load('version-one')->format();
```

#### Schema: Version 1
You will notice some of the `key` name(s) change from the above `order` output and the payable token is now decrypted since currently CCG does not accept the encrypted token. This is the currently accepted `order` schema.
```javascript
{
  "firstName": "JOHN",
  "lastName": "DOE",
  "dateOfBirth": "YYYY-MM-DDT00:00:00-04:00",
  "gender": "M",
  "address1": "1 GENERAL WAY",
  "address2": "APT 100",
  "city": "Popular City",
  "state": "FL",
  "zip": "33196",
  "telephone": "+1XXXXXXXXXX",
  "email": "XXXX@XXXX.COM",
  "esignRecipient": "+1XXXXXXXXXX",
  "caseID": 0,
  "coverageType": 1,
  "groupID": 12362,
  "agentID": XXXXXXXXX,
  "paymentInfo": {
    "payType": 0,
    "ccExpMonth": "XX",
    "ccExpYear": "XXXX",
    "ccNumber": "XXXXXXXXXXXXXXXX",
    "cvv": "XXX",
    "accountNumber": "",
    "routingNumber": ""
  },
  "plans": [
    {
      "groupID": 12362,
      "planID": 5,
      "amount": 269.95,
      "planType": 0
    },
    {
      "planID": 1,
      "amount": 99.95,
      "isOneTimeCharge": true,
      "planType": 2
    },
    {
      "groupID": 12365,
      "planID": 727,
      "amount": 76.9,
      "planType": 1
    }
  ],
  "effectiveDate": "YYYY-MM-DDT00:00:00-05:00"
}
```

