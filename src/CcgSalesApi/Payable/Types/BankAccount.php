<?php

namespace Nexusvc\CcgSalesApi\Payable\Types;

use Nexusvc\CcgSalesApi\Payable\Payable;
use Nexusvc\CcgSalesApi\Crypt\Crypt;

class Card extends Payable {

    protected $cleanProperties = [];

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
