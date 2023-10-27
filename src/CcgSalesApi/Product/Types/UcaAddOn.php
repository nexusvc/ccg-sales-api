<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Product\GenericProduct;

class UcaAddOn extends GenericProduct {

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
        'state',
        'isUCAAddOn'
    ];

    public function addToOrder() {

        parent::$ccg->order->addProduct($this);

        // // Adds EnrollmentFee
        // if($this->enrollmentPlans) {
        //     parent::$ccg->order->addProduct(new \Nexusvc\CcgSalesApi\Product\Types\EnrollmentPlan(parent::$ccg, self::$params, $this->enrollmentPlans[0]));
        // }

        return $this;
    }

}
