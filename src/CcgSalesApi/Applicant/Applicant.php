<?php

namespace Nexusvc\CcgSalesApi\Applicant;

use Nexusvc\CcgSalesApi\CCG;
use Nexusvc\CcgSalesApi\Contactable\Contactable;
use Nexusvc\CcgSalesApi\Exceptions\InvalidApplicantCoverageType as Exception;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\Traits\Contactable as ContactableTrait;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class Applicant {

    use Jsonable;
    use ContactableTrait;
    
    protected $ccg;

    public $id;
    public $firstName;
    public $middleName;
    public $lastName;
    public $dob;
    public $gender;
    public $relation;

    public function __construct(CCG &$ccg, array $params = []) {
        
        $this->ccg = $ccg;

        foreach($params as $key => $value) {
            if(property_exists($this, camel_case($key))) {
                $key = camel_case($key);
                $this->$key = $value;
            }
        }
    }

    public function addToOrder() {
        
        if(count($this->ccg->order->applicants) && count($this->ccg->order->products)) {
            $coverageType = 0;

            foreach ($this->ccg->order->products as $product) {
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

            if($coverageType == 3 && count($this->ccg->order->applicants) < 3 ) {
                throw new Exception('Selected product(s) are for a family of applicant(s)');
            }

            if($coverageType == 4 && $this->relation != 'child' ) {
                throw new Exception('Selected product(s) only support additional children');
            }
        }

        $this->ccg->order->addApplicant($this);
        return $this;
    }

}
