<?php

namespace Nexusvc\CcgSalesApi\Payable;

use Nexusvc\CcgSalesApi\CCG;
use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Exceptions\InvalidPayable;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class Payable {

    use Jsonable;

    protected $clean = [
        'account'
    ];

    protected $ccg;

    protected $cleanProperties = [];

    protected $crypt;

    protected $type;

    protected $token;

    public $account;

    public function __construct(CCG &$ccg, $props = []) {

        $this->ccg = $ccg;

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
        $instance = new $class($this->ccg, $this);

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

        try {
            $details = $this->validCreditCard($this->account);    
        } catch(\Exception $e) {
            $details = [];
        }

        return $this->token = new Token($this->ccg, $this->crypt->encrypt($tokenize), $details);
    }

    protected function setLast4($number) {
        $this->last4 = substr($number, -4);
    }

    protected function setBrand($brand) {
        $this->brand = studly_case($brand);
    }

    public function validate() {
        return $this->tokenize();
    }

    public function getPayType() {
        return $this->payType;
    }

    public function forVerification() {

        $brand = $this->brand;
        $last4 = $this->last4;
        if(strtolower($brand) != 'ach') return "{$brand} ending in {$last4}";

        return "Checking Account ending in {$last4}";
    }

    public function addToOrder() {
        $this->ccg->order->addPayable($this);
        return $this;
    }

}
