<?php

namespace Nexusvc\CcgSalesApi\Product;

use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\Order\Order;

class GenericProduct extends Quote {

    public $isOneTimeCharge = false;

    public function __construct($auth, $params, array $props = []) {

        $this->setProduct($this);
        $this->setType();

        foreach($props as $key => $value){
            $this->{$key} = $value;
        }
        
        parent::__construct($auth, $params);
    }

    protected $uri = 'products';

    protected static $params = [];

    protected $required = [];

    protected function setType() {
        $this->type = (new \ReflectionClass($this))->getShortName();
        $this->class = static::class;
    }

    public static function listProductTypes() {
        $products = [];
        $dir = new \DirectoryIterator(dirname(__FILE__).'/Types');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $class_name = str_replace('.php','',$fileinfo->getFilename());
                $class = '\\Nexusvc\\CcgSalesApi\\Product\\Types\\' . $class_name;
                $product = new $class(self::$auth, self::$params);
                array_push($products, $product);
            }
        }

        return $products;
    }

    public function addToOrder(Order &$order) {
        $order->addProduct($this);
        return $this;
    }

}
