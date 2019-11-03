<?php

namespace Nexusvc\CcgSalesApi\Verification\Types;

use Nexusvc\CcgSalesApi\Verification\Verification;

class Voice extends Verification {

    protected $uri = "verification.voice.script";

    protected static $params = [
        'brandName',
        'planID',
        'addOnPlanIDs',
        'payType',
        'state',
        'coverageType'
    ];

    protected $required = [
        'brandName',
        'planID',
        'payType',
        'state',
        'coverageType'
    ];

}
