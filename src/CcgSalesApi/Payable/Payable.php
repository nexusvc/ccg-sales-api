<?php

namespace Nexusvc\CcgSalesApi\Payable;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Nexusvc\CcgSalesApi\Exceptions\InvalidPayable;

class Payable {

    use Jsonable;

    protected $clean = [
        'account'
    ];

    protected $cleanProperties = [];

    protected $crypt;

    protected $type;

    protected $token;

    public $account;

    public function __construct($props = []) {

        $this->crypt = new Crypt;
        
        if(is_object($props)) $props = $props->toArray();

        $this->setCleanProperties();

        $this->cleanAttribute($props);

        foreach($props as $key => $value) {
            if($key != 'cleanProperties' || $key != 'clean')
                $this->$key = $value;
        }

        $this->setType();
    }

    public function get() {
        $class = $this->type;
        $instance = new $class($this);

        $instance->validate();

        return $instance;
    }

    protected function setCleanProperties() {
        $clean = $this->clean;
        $tmp = [];
        foreach($clean as $cleanIndex) {
            array_set($tmp, $cleanIndex, false);
        }
        $this->cleanProperties = $tmp;
        return $this;      
    }

    protected function cleanAttribute(&$value, $key = null) {

        if(is_array($value)) {
            $tmp = [];
            $value = array_dot($value);
            foreach($value as $k => $v) {
                array_set($tmp, $k, $this->cleanAttribute($v, $k));
            }
            return $value = $tmp;
        }
        
        if(!is_null($key) && array_has($this->cleanProperties, $key)) return $value = preg_replace('/[^0-9]/', '', $value);
        
        return $value;
    }

    protected function setType() {

        if(property_exists($this, 'routing')) {
            $type = Types\BankAccount::class;
        } else {
            $type = Types\Card::class;
        }

        return $this->type = $type;
    }

    public function getToken() {
        return $this->token;
    }

    protected function tokenize($props = []) {
        $tokenize = array_dot($this->toArray());
        return $this->token = new Token($this->crypt->encrypt($tokenize));
    }

    public function validate() {
        return $this->tokenize();
    }

    public function getPayType() {
        return $this->payType;
    }

}
