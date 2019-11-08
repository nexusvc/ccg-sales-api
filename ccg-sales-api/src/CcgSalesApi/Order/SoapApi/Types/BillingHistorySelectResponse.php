<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;

class BillingHistorySelectResponse extends EnrollmentService {

    protected $billingHistorySelectResult = [];

    protected function setBillingHistorySelectResultAttribute(ArrayOfReceipt $arrayOfReceipt) {
        array_push($this->billingHistorySelectResult, $arrayOfReceipt);
    }

}
