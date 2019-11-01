<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class LimitedMedical extends GenericProduct {

    protected $uri;

    protected static $params = [
        'npn',
        'state',
        'effectiveDate',
        'dateOfBirth',
        'coverageType',
        'minPrice',
        'maxPrice',
        'age'
    ];

    protected $required = [
        'npn',
        'state'
    ];

}
