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

    public static function charge(Order $order) {
        $charge = new self($order);

        $schema = new Schema($charge);
        $format = $schema->load('soap-order')->format();

        dd($format);

        dd($charge);
    }

}
