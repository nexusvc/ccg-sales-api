<?php

namespace Nexusvc\CcgSalesApi\Payable;

use Nexusvc\CcgSalesApi\CCG;
use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Payable\Payable;

class Token extends Payable {
    
    public function __construct(CCG &$ccg, $token) {
        $this->ccg = $ccg;
        $this->account = $token;
    }

    public function __toString() {
        return $this->account;
    }
}
