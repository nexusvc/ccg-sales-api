<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class ProductBenefits extends GenericProduct {

    protected $uri;

    protected static $params = [
        'groupID',
        'planID'
    ];

    protected $required = [
        'groupID',
        'planID'
    ];

}
