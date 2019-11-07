<?php

namespace Nexusvc\CcgSalesApi\Facades;

use Illuminate\Support\Facades\Facade;

class CCG extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'ccg'; 
    }

}
