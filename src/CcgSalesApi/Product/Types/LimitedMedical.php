<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class LimitedMedical extends GenericProduct {

    protected $uri;

    protected $params = [
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
