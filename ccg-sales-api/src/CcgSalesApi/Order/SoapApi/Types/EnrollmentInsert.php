<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Nexusvc\CcgSalesApi\Order\SoapApi\Traits\Authenticatable;

class EnrollmentInsert extends EnrollmentService {
    
    use Authenticatable;

    public $e;

    public function __construct(Enrollment $e) {
        $this->e = $e;
    }
}
