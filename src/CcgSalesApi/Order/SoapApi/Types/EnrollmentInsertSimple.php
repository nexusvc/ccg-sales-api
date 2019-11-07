<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Nexusvc\CcgSalesApi\Order\SoapApi\Traits\Authenticatable;

class EnrollmentInsertSimple extends EnrollmentService {
    
    use Authenticatable;

    protected $xmlString;

    public function __construct($params) {
        $this->xmlString = $params;
    }

}
