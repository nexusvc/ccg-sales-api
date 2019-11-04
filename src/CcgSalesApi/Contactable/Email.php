<?php

namespace Nexusvc\CcgSalesApi\Contactable;

class Email extends Contactable {

    protected $username;
    protected $domain;
    protected $symbol = '@';

    public function __construct($location) {
        
        $location = strtolower($location);

        $location = str_replace(' at ', $this->symbol, $location);
        $location = str_replace('[at]', $this->symbol, $location);
        
        $this->location = strtolower($location);
        
        $this->type  = self::class;

        $this->username = explode('@', $location)[0];
        $this->domain = explode('@', $location)[1];
    }

}
