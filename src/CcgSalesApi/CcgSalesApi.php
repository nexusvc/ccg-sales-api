<?php

namespace Nexusvc\CcgSalesApi;

use Tightenco\Collect\Support\Collection;
use Tightenco\Collect\Support\Arr;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class CcgSalesApi {

    use Jsonable;

    protected $env = 'development';

    protected $config;

    public $auth = Auth\Authentication::class;

    public $client = Client\Client::class;

    public $quote = Quote\Quote::class;

    public $applicant = Applicant\Applicant::class;

    public $order = Order\Order::class;

    public function __construct()
    {
        static::boot();
        $this->order = new Order\Order;
    }

    public function auth() {
        return $this->auth = ($this->auth instanceof Auth\Authentication) ? $this->auth : new Auth\Authentication;
    }

    public function client() {
        return $this->client = ($this->client instanceof Client\Client) ? $this->client : new Client\Client;
    }

    public function quote(array $params = []) {
        return $this->quote = ($this->quote instanceof Quote\Quote) ? $this->quote : new Quote\Quote($this->auth(), $params);
    }

    public function boot()
    {
        $this->loadConfigs();
        $this->auth();

    }

    public static function getInstance() {
        return new self();
    }

    public static function url($endpoint = null) {
        $ccg = static::getInstance();
        $endpoints = Arr::dot($ccg->config['endpoints']);
        $suffix = Arr::has($endpoints, $endpoint) ? $endpoints[$endpoint] : $endpoint;

        return $endpoints['base.'.$ccg->env] . $suffix;
    }

    protected function loadConfigs() {
        $this->config = new Collection;
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
        
        $dot_config = Arr::dot($ccg->config);

        if(!$dot) return (is_null($key) ? $ccg->config : (Arr::has($ccg->config, $key) ? $ccg->config[$key] : null));

        return (is_null($key) ? $dot_config : (Arr::has($dot_config, $key) ? $dot_config[$key] : (Arr::has($ccg->config, $key) ? $ccg->config[$key] : null ) ) );
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->config->get($key, []);
        $this->config->put($key, array_merge(require $path, $config));
    }

    public static function __callStatic($method, $parameters)
    {
        if (! property_exists(get_called_class(), $method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        return static::${$method};
    }
}
