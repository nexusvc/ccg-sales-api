<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;

class PackageBill extends EnrollmentService {

    protected $packageId;
    protected $planId;
    protected $planName;
    protected $billDate;
    protected $amount;
    protected $credit;


    protected function setPackageIdAttribute(int $packageId) {
        $this->packageId = $packageId;
    }

    protected function setPlanIdAttribute(int $planId) {
        $this->planId = $planId;
    }

    protected function setPlanNameAttribute(string $planName) {
        $this->planName = $planName;
    }

    protected function setBillDateAttribute($billDate) {
        $this->billDate = $billDate;
    }

    protected function setAmountAttribute(float $amount) {
        $this->amount = $amount;
    }

    protected function setCreditAttribute(bool $credit) {
        $this->credit = $credit;
    }

}
