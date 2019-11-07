<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Nexusvc\CcgSalesApi\Order\SoapApi\Traits\Authenticatable;

class MemberSelectByGroup extends EnrollmentService {
    
    use Authenticatable;

    protected $groupId;

    protected function setGroupIdAttribute($groupId) {
        $this->groupId = $groupId;
    }

}
