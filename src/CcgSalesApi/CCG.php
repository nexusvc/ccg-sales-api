<?php

namespace Nexusvc\CcgSalesApi;

use Nexusvc\CcgSalesApi\Traits\Configurable;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class CCG {

    use Jsonable;
    use Configurable;

    protected $env;

    protected $crypt    = Crypt\Crypt::class;

    public $applicant   = Applicant\Applicant::class;

    public $auth        = Auth\Authentication::class;

    public $client      = Client\Client::class;

    public $order       = Order\Order::class;

    public $payable     = Payable\Payable::class;

    public $phone       = Contactable\Phone::class;

    public $email       = Contactable\Email::class;

    public $address     = Contactable\Address::class;

    public $quote       = Quote\Quote::class;

    public function __construct() {

        $this->loadConfigs();
        
        $this->auth();
        
        $this->order = new Order\Order;
        $this->crypt = new Crypt\Crypt;
        
    }

    public static function __callStatic($method, $parameters) {
        if (! property_exists(get_called_class(), $method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        return static::${$method};
    }

    public function auth() {
        return $this->auth = ( $this->auth instanceof Auth\Authentication ) ? 
            $this->auth : 
            new Auth\Authentication;
    }

    public function crypt() {
        return $this->crypt = ( $this->crypt instanceof Crypt ) ? 
            $this->crypt : 
            new Crypt;
    }

    public function client() {
        return $this->client = ( $this->client instanceof Client\Client ) ? 
            $this->client : 
            new Client\Client;
    }

    public function decrypt($value) {
        return $this->crypt->decrypt($value);
    }

    public function encrypt($value) {
        return $this->crypt->encrypt($value);
    }

    public function quote(array $params = []) {
        return $this->quote = ( $this->quote instanceof Quote\Quote ) ? 
            new Quote\Quote($this->auth(), $params ) : 
            new Quote\Quote($this->auth(), $params );
    }

    public static function getInstance() {
        return new self();
    }

}
