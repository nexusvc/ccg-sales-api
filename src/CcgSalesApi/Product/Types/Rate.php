<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class Rate extends GenericProduct {

    protected $uri;

    protected $params = [
        'npn',
        'state',
        'zipCode',
        'effectiveDate',
        'dateOfBirth',
        'age',
        'gender',
        'coverageType',
        'minPrice',
        'maxPrice'
    ];

    protected $required = [
        'npn',
        'state',
        'zipCode',
        'effectiveDate',
        'dateOfBirth',
        'gender',
        'coverageType'
    ];

}
