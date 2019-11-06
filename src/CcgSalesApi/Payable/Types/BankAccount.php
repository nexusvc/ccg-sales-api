<?php

namespace Nexusvc\CcgSalesApi\Payable\Types;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Payable\Payable;

class BankAccount extends Payable {

    protected $cleanProperties = [];

    public $brand = 'ACH Account';

    protected $clean = [
        'account',
        'routing'
    ];

    public $routing;

    public function validate() {
        dd('Validating bank account');
    }

    protected function setType() {
        parent::setType();
        $this->payType = 'ACH';
    }
    
}
