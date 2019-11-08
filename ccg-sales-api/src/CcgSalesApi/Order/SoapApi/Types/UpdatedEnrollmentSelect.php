<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Carbon\Carbon;
use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Nexusvc\CcgSalesApi\Order\SoapApi\Traits\Authenticatable;

class UpdatedEnrollmentSelect extends EnrollmentService {
        
        use Authenticatable;

        protected $startDate;

        protected $endDate;

        protected function setStartDateAttribute($startDate) {
            $this->startDate = Carbon::parse($startDate)->toW3cString();
        }

        protected function setEndDateAttribute($endDate) {
            $this->endDate = Carbon::parse($endDate)->toW3cString();
        }

}
