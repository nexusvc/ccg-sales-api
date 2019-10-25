<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class AddOn extends GenericProduct {

    protected $uri;

    protected $params = [
        'groupID',
        'npn',
        'state',
        'coverageType'
    ];

    protected $required = [
        'groupID',
        'npn',
        'state'
    ];

}
