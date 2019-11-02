<?php

namespace Nexusvc\CcgSalesApi\Applicant;

use Nexusvc\CcgSalesApi\Quote\Quote;

use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Nexusvc\CcgSalesApi\Traits\Contactable as ContactableTrait;
use Nexusvc\CcgSalesApi\Contactable\Contactable;

class Applicant {

    use Jsonable;
    use ContactableTrait;
    
    public $firstName;
    public $middleName;
    public $lastName;
    public $dob;
    public $gender;
    public $email;

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

}
