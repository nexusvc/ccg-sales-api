<?php

namespace Nexusvc\CcgSalesApi;

use Nexusvc\CcgSalesApi\Traits\Jsonable;

class CcgSalesApi {

    use Jsonable;

    protected $config;

    protected $crypt = Crypt\Crypt::class;

    protected $env = 'development';

    public $applicant   = Applicant\Applicant::class;
    public $auth        = Auth\Authentication::class;
    public $client      = Client\Client::class;
    public $order       = Order\Order::class;
    public $payable     = Payable\Payable::class;
    public $quote       = Quote\Quote::class;

    public function __construct() {
        static::boot();
        $this->order = new Order\Order;
        $this->crypt = new Crypt\Crypt;
    }

    public static function __callStatic($method, $parameters) {
        if (! property_exists(get_called_class(), $method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        return static::${$method};
    }

    public function boot() {
        $this->loadConfigs();
        $this->auth();
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

    public static function url($endpoint = null) {
        $ccg = static::getInstance();
        $endpoints = array_dot($ccg->config['endpoints']);
        $suffix = array_has($endpoints, $endpoint) ? $endpoints[$endpoint] : $endpoint;

        return $endpoints['base.'.$ccg->env] . $suffix;
    }

    protected function loadConfigs() {
        $this->config = collect([]);
        $dir = new \DirectoryIterator(dirname(__FILE__).'/Config');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $this->mergeConfigFrom(
                    __DIR__.'/Config/'.$fileinfo->getFilename(), 
                    str_replace('.php','',$fileinfo->getFilename())
                );
            }
        }

        return $this;
    }

    public static function config($key = null, $dot = true) {
        $ccg = static::getInstance();
        
        $dot_config = array_dot($ccg->config);

        if(!$dot) {
            return (is_null($key) ? 
                $ccg->config : 
                ( array_has($ccg->config, $key) ? 
                    $ccg->config[$key] : 
                    null
                )
            );
        }

        return (is_null($key) ? 
            $dot_config : 
            ( array_has($dot_config, $key) ? 
                $dot_config[$key] : 
                ( array_has($ccg->config, $key) ? 
                    $ccg->config[$key] : 
                    null 
                ) 
            ) 
        );
    }

    protected function mergeConfigFrom($path, $key) {
        $config = $this->config->get($key, []);
        $this->config->put($key, array_merge(require $path, $config));
    }

}
