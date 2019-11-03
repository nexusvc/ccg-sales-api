<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Traits\Jsonable;

use Nexusvc\CcgSalesApi\Applicant\Applicant;
use Nexusvc\CcgSalesApi\Product\GenericProduct;

class Order {
    
    use Jsonable;

    public $applicants;
    public $products;
    public $paymentMethods;
    public $verification;

    protected $appends = ['total','deposit','recurring'];

    public function __construct() {
        $this->applicants = collect([]);
        $this->products = collect([]);
        $this->paymentMethods = collect([]);
        $this->verification = collect([]);
    }

    public function addApplicant(Applicant $applicant) {
        $this->applicants->push($applicant);
        return $this;
    }

    public function addProduct(GenericProduct $product) {
        $this->products->push($product);
        return $this;
    }

    public function addPaymentMethod( $paymentMethod ) {
        $this->paymentMethods->push($paymentMethod);
        return $this;
    }

    public function addVerification( $verification ) {
        $this->verification = $verification;
        return $this;
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
        
        return $this->recurring = (double) ($total);
    }

    public function setDepositAttribute() {
        $total = 0;

        foreach($this->products as $product) {
            if($product->isOneTimeCharge)
                $total += $product->retailAmount;
        }
        
        return $this->deposit = (double) ($total);
    }


}
