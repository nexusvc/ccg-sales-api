<?php

namespace Nexusvc\CcgSalesApi\Contactable;

use Nexusvc\CcgSalesApi\Traits\Contactable as ContactableTrait;

class Contactable {

    use ContactableTrait;

    protected $location;
    public $type;

    public function __construct() {
        $class = new \ReflectionClass($this);
        $this->type = $type = camel_case($class->getShortName());

        $this->$type = $this->location;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getType() {
        return $this->type;
    }

    // add remote validators

}
