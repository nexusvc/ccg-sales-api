<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Schema\Schema;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class ChargeOrder {

    use Jsonable;

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function charge() {
        $schema = new Schema($this->order);
        return $schema->load('enrollment')->format();
    }

    // public static function charge(Order $order) {

    //     $charge = new self($order);

    //     $schema = new Schema($charge);
    //     return $schema->load('enrollment')->format();
    // }

}
