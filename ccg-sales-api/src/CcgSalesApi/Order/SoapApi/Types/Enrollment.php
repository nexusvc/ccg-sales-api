<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;

class Enrollment extends EnrollmentService {

    private $member;

    private $account;

    private $package = [];

    private $dependent = [];

    private $beneficiary;

    private $charge;

    private $discountCouponCode = null;

    private $reciept;

    protected function setMemberAttribute(Member $member) {
        $this->member = $member;
    }

    protected function setAccountAttribute(Account $account) {
        $this->account = $account;
    }

    protected function setPackageAttribute(Package $package) {
        array_push($this->package, $package);
    }

    protected function setDependentAttribute(Dependent $dependent) {
        array_push($this->dependent, $dependent);
    }

    protected function setBeneficiaryAttribute(Beneficiary $beneficiary) {
        $this->beneficiary = $beneficiary;
    }

    protected function setDiscountCouponCode(string $discountCouponCode = null) {
        $this->discountCouponCode = $discountCouponCode;
    }

    protected function setRecieptAttribute(Reciept $reciept) {
        $this->reciept = $reciept;
    }


}
