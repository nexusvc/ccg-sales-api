<?php

namespace Nexusvc\CcgSalesApi\Verification\Types;

use Nexusvc\CcgSalesApi\Verification\Verification;
use Nexusvc\CcgSalesApi\Order\Order;

class Esign extends Verification {

    protected $uri = "verification.esign.invite";

    protected static $params = [
        'caseID',
        'groupID',
        'effectiveDate',
        'dateOfBirth',
        'firstName',
        'lastName',
        'gender',
        'email',
        'telephone',
        'city',
        'state',
        'zip',
        'address1',
        'address2',
        'agentID',
        'esignRecipient',
        'plans',
        'plans.groupID',
        'plans.planID',
        'plans.amount',
        'plans.planType',
        'dependents',
        'dependents.firstName',
        'dependents.lastName',
        'dependents.dateOfBirth',
        'dependents.dependentType',
        'paymentInfo',
        'paymentInfo.payType',
        'paymentInfo.ccNumber',
        'paymentInfo.ccExpMonth',
        'paymentInfo.ccExpYear',
        'paymentInfo.cvv',
        'paymentInfo.routingNumber',
        'paymentInfo.accountNumber'
    ];

    protected $required = [
        'groupID',
        'effectiveDate',
        'dateOfBirth',
        'firstName',
        'lastName',
        'gender',
        'email',
        'telephone',
        'city',
        'state',
        'zip',
        'address1',
        'agentID',
        'esignRecipient',
        'plans',
        'plans.groupID',
        'plans.planID',
        'plans.amount',
        'plans.planType',
        'paymentInfo',
        'paymentInfo.payType'
    ];

}
