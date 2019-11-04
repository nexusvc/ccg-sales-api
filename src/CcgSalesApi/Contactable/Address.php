<?php

namespace Nexusvc\CcgSalesApi\Contactable;

class Address extends Contactable {

    public $street1;
    public $street2;
    public $city;
    public $state;
    public $zip;

    public function __construct(array $params = []) {
        foreach($params as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $this->location = "{$this->street1}";
        
        if($this->street2) $this->location .= " {$this->street2}";

        $this->location .= " {$this->city}, {$this->state} {$this->zip}";

        parent::__construct();
    }

}
