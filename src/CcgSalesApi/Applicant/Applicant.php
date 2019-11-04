<?php

namespace Nexusvc\CcgSalesApi\Applicant;

use Nexusvc\CcgSalesApi\Contactable\Contactable;
use Nexusvc\CcgSalesApi\Exceptions\InvalidApplicantCoverageType as Exception;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\Traits\Contactable as ContactableTrait;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class Applicant {

    use Jsonable;
    use ContactableTrait;
    
    public $id;
    public $firstName;
    public $middleName;
    public $lastName;
    public $dob;
    public $gender;
    public $relation;

    public function __construct(array $params = []) {
        foreach($params as $key => $value) {
            if(property_exists($this, camel_case($key))) {
                $key = camel_case($key);
                $this->$key = $value;
            }
        }
    }

    public function addToOrder(Order &$order) {
        
        if(count($order->applicants) && count($order->products)) {
            $coverageType = 0;

            foreach ($order->products as $product) {
                if(property_exists($product, 'coverageType')) {        
                    if($product->coverageType) $coverageType = $product->coverageType;
                }
            }

            if($coverageType == 1 ) {
                throw new Exception('Selected product(s) can not add additional applicant(s)');
            }

            if($coverageType == 2 && $this->relation != 'spouse' ) {
                throw new Exception('Selected product(s) only support a spouse');
            }

            if($coverageType == 3 && count($order->applicants) < 3 ) {
                throw new Exception('Selected product(s) are for a family of applicant(s)');
            }

            if($coverageType == 4 && $this->relation != 'child' ) {
                throw new Exception('Selected product(s) only support additional children');
            }
        }

        $order->addApplicant($this);
        return $this;
    }

}
