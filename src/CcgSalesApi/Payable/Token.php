<?php

namespace Nexusvc\CcgSalesApi\Payable;

use Nexusvc\CcgSalesApi\Payable\Payable;
use Nexusvc\CcgSalesApi\Crypt\Crypt;

class Token extends Payable {
    
    public function __construct($token) {
        $this->account = $token;
    }

    public function __toString() {
        return $this->account;
    }
}
