<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class ValidateOrder {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function validate() {
        
    }

}
