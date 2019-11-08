<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Nexusvc\CcgSalesApi\Order\SoapApi\Traits\Authenticatable;

class ServiceLogSelect extends EnrollmentService {
        
        use Authenticatable;

        protected $memberId;

        protected $groupId;

        protected function setMemberIdAttribute($memberId) {
            $this->memberId = $memberId;
        }

        protected function setGroupIdAttribute($groupId) {
            $this->groupId = $groupId;
        }

}
