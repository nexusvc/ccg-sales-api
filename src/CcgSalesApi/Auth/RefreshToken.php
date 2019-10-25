<?php

namespace Nexusvc\CcgSalesApi\Auth;

class RefreshToken extends Token {

    /**
     * Default session lifetime in seconds
     *
     * @var int
     */
    protected $lifetime = 2700;
    
}
