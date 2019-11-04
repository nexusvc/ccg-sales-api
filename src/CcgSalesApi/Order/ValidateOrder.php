<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Traits\Jsonable;

use Nexusvc\CcgSalesApi\Crypt\Crypt;

class ValidateOrder {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function validate() {
        
    }

}
