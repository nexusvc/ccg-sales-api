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

    public function addToOrder() {
        $this->ccg->order->addProduct($this);
        
        if($this->enrollmentPlans) {
            $this->ccg->order->addProduct(new \Nexusvc\CcgSalesApi\Product\Types\EnrollmentPlan($this->ccg, self::$params, $this->enrollmentPlans[0]));
        }

        // if(property_exists($this->product, 'agentId')) $order->agentId = $this->agentId; 

        return $this;
    }

}
