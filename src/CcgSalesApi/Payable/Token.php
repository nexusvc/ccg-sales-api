<?php

namespace Nexusvc\CcgSalesApi\Payable;

use Nexusvc\CcgSalesApi\CCG;
use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Payable\Payable;

class Token extends Payable {
    
    public function __construct(CCG &$ccg, $token, $details = []) {
        
        $this->ccg = $ccg;
        
        $this->account = $token;

        if(count($details)) {
            if(array_key_exists('number', $details)) $this->setLast4($details['number']);
            if(array_key_exists('type', $details)) $this->setBrand($details['type']);
        }
    }

    public function __toString() {
        return $this->account;
    }

    protected function setLast4($number) {
        $this->last4 = substr($number, -4);
    }

    protected function setBrand($brand) {
        $this->brand = studly_case($brand);
    }

}
