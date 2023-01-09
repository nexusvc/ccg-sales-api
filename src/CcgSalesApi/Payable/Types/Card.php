<?php

namespace Nexusvc\CcgSalesApi\Payable\Types;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Exceptions\InvalidCreditCard as Exception;

class Card extends CreditCard {

    protected $cleanProperties = [];
    
    protected $clean = [
        'account',
        'cvc',
        'expiration.month',
        'expiration.year',
        'billingAddress.street1',
        'billingAddress.street2',
        'billingAddress.city',
        'billingAddress.state',
        'billingAddress.zip'
    ];

    public $cvc;

    public $expiration = [];
    
    public function validate() {
        if(!self::isValidCard($this->account, $this->cvc, $this->expiration)) 
            throw new Exception('Credit/Debit cards must have a valid 16 digit account number, CVC, and expiration date.');
        return parent::validate();
    }

    protected function setType() {
        parent::setType();
        $this->payType = 'CC';
    }
}
