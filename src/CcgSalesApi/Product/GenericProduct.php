<?php

namespace Nexusvc\CcgSalesApi\Product;

use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\CCG;

class GenericProduct extends Quote {

    protected $class;

    protected $required = [];

    public $type;

    protected $uri = 'products';

    protected static $params = [];

    public function __construct(CCG &$ccg, $params, array $props = []) {

        $this->setProduct($this);
        $this->setType();

        foreach($props as $key => $value){
            $this->{$key} = $value;
        }
        
        $this->npn = $ccg->auth->npn;
        parent::__construct($ccg, $params);
    }

    public static function listProductTypes() {

        $products = [];
        $dir = new \DirectoryIterator(dirname(__FILE__).'/Types');
        
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $class_name = str_replace('.php','',$fileinfo->getFilename());
                $class = '\\Nexusvc\\CcgSalesApi\\Product\\Types\\' . $class_name;
                $product = new $class(self::$ccg, self::$params);
                array_push($products, $product);
            }
        }

        return $products;
    }

    public function addToOrder() {
        parent::$ccg->order->addProduct($this);
        return $this;
    }

    protected function setType() {
        $this->type = (new \ReflectionClass($this))->getShortName();
        $this->class = static::class;
    }

}
