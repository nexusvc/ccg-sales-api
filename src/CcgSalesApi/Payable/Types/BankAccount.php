<?php

namespace Nexusvc\CcgSalesApi\Payable\Types;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Payable\Payable;
use Nexusvc\CcgSalesApi\Payable\Token;

class BankAccount extends Payable {

    protected $cleanProperties = [];

    public $brand = 'ACH Account';

    protected $clean = [
        'account',
        'routing'
    ];

    public $routing;

    public static function validBankAccount($number, $routing, $type = null) {
        $ret = array(
            'valid' => true,
            'number' => $number,
            'routing' => $routing,
            'type' => 'ach',
        );

        return $ret;
    }

    protected function tokenize($props = []) {
        $tokenize = array_dot($this->toArray());

        try {
            $details = $this->validBankAccount($this->account, $this->routing);    
        } catch(\Exception $e) {
            $details = [];
        }

        return $this->token = new Token($this->ccg, $this->crypt->encrypt($tokenize), $details);
    }

    public function validate() {
        return $this->tokenize();
    }

    protected function setType() {
        parent::setType();
        $this->payType = 'ACH';
    }
    
}
