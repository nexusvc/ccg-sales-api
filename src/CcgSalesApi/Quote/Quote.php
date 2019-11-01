<?php

namespace Nexusvc\CcgSalesApi\Quote;

use Nexusvc\CcgSalesApi\Auth\Authentication;
use Nexusvc\CcgSalesApi\Product\GenericProduct;
use Nexusvc\CcgSalesApi\Client\Client;

class Quote {

    public $url;

    public $attributes = [];

    protected $uri = 'Quote';

    protected $product;

    protected static $auth;

    protected static $params = [];

    protected $required = [];

    public function __construct(Authentication $auth, array $params = []) {
        self::$auth = $auth;
        self::$params = $params;
        
        $this->setEndPoint();
        $this->setRequiredAttributes();
    }

    protected function setResponse($response) {
        $this->resources = $response;
        return $this;
    }
    
    public function fetch() {
        
        $token = self::$auth->access_token;
        
        $params = self::$params;
        $params['npn'] = self::$auth->npn;



        $client = new Client($token);
        $this->attributes = array_merge($this->attributes, $params);
        
        return $this->setResponse($client->request('POST', $this->url, [
            'form_params' => $this->attributes
        ]));
    }


    public $coverageTypes = [
        'individual' => 1,
        'couple' => 2,
        'family' => 3,
        'dependents' => 4
    ];

    protected function setRequiredAttributes() {
        foreach ($this->required as $required) {
            $this->attributes[$required] = 0;
        }
        return $this;
    }

    protected function setEndPoint() {
        if(isset($this->uri)) return $this->url = ccg_url($this->uri);

        $this->uri = $uri = 'quote.' . strtolower(snake_case((new \ReflectionClass($this))->getShortName()));
        return $this->url = ccg_url($uri);
    }

    protected function appendRequirements(array $required = []) {
        $this->required = array_merge($this->required, $required);
        return $this;
    }

    protected function appendParams(array $params = []) {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public static function products() {
        self::setProductTypeClass();
        return (self::$params['type'])->fetch()->resources;
    }

    public static function setProductTypeClass() {
        try {
            $type = self::$params['type'];
        } catch(\Exception $e) {
            self::$params['type'] = 'Rate';
            return self::setProductTypeClass();
        }

        $type = "\\Nexusvc\\CcgSalesApi\\Product\\Types\\{$type}";
        self::$params['type'] = new $type(self::$auth, self::$params);
    }

    public static function categories() {
        return GenericProduct::listProductTypes();
    }

    protected function setProduct(GenericProduct $product) {
        $this->product = $product;
        return $this;
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true); 
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __toString()
    {
        return $this->toJson();
    }

}
