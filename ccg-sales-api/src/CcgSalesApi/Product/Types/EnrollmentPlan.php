<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class EnrollmentPlan extends GenericProduct {

    protected $uri;

    public $isOneTimeCharge = true;

    protected static $params = [];
    protected $required = [];

}
