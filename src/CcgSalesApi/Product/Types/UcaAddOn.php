<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\LimitedMedical;

class UcaAddOn extends LimitedMedical {

    protected $uri;

    protected static $params = [
        'npn',
        'state',
        'effectiveDate',
        'dateOfBirth',
        'coverageType',
        'minPrice',
        'maxPrice',
        'age',
        'isUCAAddOn'
    ];

    protected $required = [
        'npn',
        'state'
    ];

}
