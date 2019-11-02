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
    public $verifications;

    public function __construct() {
        $this->applicants = collect([]);
        $this->products = collect([]);
        $this->paymentMethods = collect([]);
        $this->verifications = collect([]);
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
        $this->verifications->push($verification);
        return $this;
    }

}
