<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class AddOn extends GenericProduct {

    protected $uri;

    protected static $params = [
        'groupID',
        'npn',
        'state',
        'coverageType'
    ];

    protected $required = [
        'npn',
        'state'
    ];

}
