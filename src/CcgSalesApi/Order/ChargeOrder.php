<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class ChargeOrder {

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

}
