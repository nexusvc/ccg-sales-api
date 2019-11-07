<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;

class Receipt extends EnrollmentService {

    protected $receiptId;
    protected $receiptDate;
    protected $billDate;
    protected $amount;
    protected $credit;
    protected $resultDetail;
    protected $packageBill;

    protected function setReceiptIdAttribute(int $receiptID) {
        $this->receiptID = $receiptID;
    }

    protected function setReceiptDateAttribute($receiptDate) {
        $this->receiptDate = $receiptDate;
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

    protected function setResultDetailAttribute(string $resultDetail) {
        $this->resultDetail = $resultDetail;
    }

    protected function setPackageBillAttribute(PackageBill $packageBill) {
        $this->packageBill = $packageBill;
    }

}
