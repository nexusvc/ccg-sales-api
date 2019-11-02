<?php

namespace Nexusvc\CcgSalesApi\Applicant;

use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Nexusvc\CcgSalesApi\Traits\Contactable as ContactableTrait;
use Nexusvc\CcgSalesApi\Contactable\Contactable;

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

    public $contactMethods;

    public function __construct(array $params = []) {
        
        $this->contactMethods = new Contactable;

        foreach($params as $key => $value) {
            if(property_exists($this, camel_case($key))) {
                $key = camel_case($key);
                $this->$key = $value;
            }
        }
    }

    public function addToOrder(Order &$order) {
        $order->addApplicant($this);
        return $this;
    }

}
