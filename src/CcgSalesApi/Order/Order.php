<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Applicant\Applicant;
use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Payable\Payable;
use Nexusvc\CcgSalesApi\Payable\Token;
use Nexusvc\CcgSalesApi\Product\GenericProduct;
use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Nexusvc\CcgSalesApi\Validator\SchemaValidator;
use Nexusvc\CcgSalesApi\Exceptions\InvalidPayable;

class Order {
    
    use Jsonable;

    public $applicants;
    public $payable;
    public $products;
    public $verification;

    protected $appends = [
        'total',
        'deposit',
        'recurring'
    ];

    protected $rules = [
        'required' => [
            'payable.account',
            'products.0',
            'verification.caseId',
            'applicants.0'
        ]
    ];

    public function __construct() {
        $this->applicants   = collect([]);
        $this->products     = collect([]);
        $this->verification = collect([]);
    }

    public function addApplicant( Applicant $applicant ) {
        $this->applicants->push($applicant);
        return $this;
    }

    public function addProduct( GenericProduct $product ) {
        $this->products->push($product);
        return $this;
    }

    public function addPayable( Payable $payable ) {
        $this->payable = $payable->get();

        if(property_exists($this->payable, 'token'))  $this->payable = $this->payable->getToken();
        return $this;
    }

    public function addVerification( $verification ) {
        $this->verification = $verification;
        return $this;
    }

    public function charge($debug = false) {
        return (new ChargeOrder($this))->charge($debug);
    }

    public function setTotalAttribute() {
        $total = 0;

        foreach($this->products as $product) {
            $total += $product->retailAmount;
        }
        
        return $this->total = (double) number_format((double) ($total), 2);
    }

    public function setRecurringAttribute() {
        $total = 0;

        foreach($this->products as $product) {
            if(!$product->isOneTimeCharge)
                $total += $product->retailAmount;
        }
        
        return $this->recurring = (double) number_format((double) ($total), 2);
    }

    public function setDepositAttribute() {
        $total = 0;

        foreach($this->products as $product) {
            if($product->isOneTimeCharge)
                $total += $product->retailAmount;
        }
        
        return $this->deposit = (double) number_format((double) ($total), 2);
    }

    public function validate() {
        if(property_exists($this, 'rules')) {
            $validator = new SchemaValidator($this->rules, $this->toArray());
            $validator->validate();
        }
    }

    /**
     *
     * @todo: Temporary Public Decrypt
     * used for schema formatter
     *
     */
    public function detokenize() {
        $crypt = new Crypt;
        
        if(!$this->payable) return [];

        if(!$this->payable->account) throw new InvalidPayable('There is no Payable object attached to this order.');
        if(!$this->payable instanceof Token) return $this->payable->account;
        
        $tmp = [];
        
        foreach($crypt->decrypt($this->payable->account) as $key => $value) {
            array_set($tmp, $key, $value);
        }

        $payment = [
            'payType' => $tmp['payType'],
        ];

        if($tmp['payType'] == 'CC') {
            $payment['ccExpMonth'] = array_get($tmp, 'expiration.month');
            $payment['ccExpYear'] = array_get($tmp, 'expiration.year');
            $payment['ccNumber'] = array_get($tmp, 'account');
            $payment['cvv'] = array_get($tmp, 'cvc');
        }

        if($tmp['payType'] == 'ACH') {
            $payment['accountNumber'] = array_get($tmp, 'account');
            $payment['routingNumber'] = array_get($tmp, 'routing');
        }

        return $payment;
    }


}
