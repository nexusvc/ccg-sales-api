<?php

namespace Nexusvc\CcgSalesApi\Product;

use Nexusvc\CcgSalesApi\Quote\Quote;

class GenericProduct extends Quote {

    public function __construct() {
        $this->setProduct($this);
        $this->setType();
        parent::__construct();
    }

    protected $uri = 'products';

    protected $params = [
        // 'npn',
        // 'state',
        // 'zipCode',
        // 'effectiveDate',
        // 'dateOfBirth',
        // 'age',
        // 'gender',
        // 'coverageType',
        // 'minPrice',
        // 'maxPrice'
    ];

    protected $required = [
        // 'npn',
        // 'state',
        // 'zipCode',
        // 'effectiveDate',
        // 'dateOfBirth',
        // 'gender',
        // 'coverageType'
    ];

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
                $product = new $class;
                array_push($products, $product);
            }
        }
        
        // array_push($products, new static);

        return $products;
    }

}
