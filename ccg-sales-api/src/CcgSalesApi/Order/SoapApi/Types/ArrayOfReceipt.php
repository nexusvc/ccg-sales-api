<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;

class ArrayOfReceipt extends EnrollmentService {
    
    protected $receipt = [];

    protected function setReceiptAttribute(Receipt $receipt) {
        array_push($this->receipt, $receipt);
    }

}
