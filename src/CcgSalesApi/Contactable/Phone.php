<?php

namespace Nexusvc\CcgSalesApi\Contactable;

class Phone extends Contactable {

    protected $countryCode;
    protected $areaCode;
    protected $prefix;
    protected $lineNumber;

    public function __construct(string $location) {
        
        $this->location = $location = normalize_phone_to_E164($location);

        $this->lineNumber = substr($location, -4);
        $this->prefix = substr($location, -7, -4);
        $this->areaCode = substr($location, -10, 3);
        $this->countryCode = substr($location, 0, -10);

        parent::__construct();
    }

}
