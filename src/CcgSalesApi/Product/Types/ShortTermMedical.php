<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class ShortTermMedical extends GenericProduct {

    protected $uri;

    protected static $params = [
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
