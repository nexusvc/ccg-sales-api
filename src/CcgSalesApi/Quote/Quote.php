<?php

namespace Nexusvc\CcgSalesApi\Quote;

use Nexusvc\CcgSalesApi\Product\GenericProduct;
use Nexusvc\CcgSalesApi\Client\Client;

class Quote {

    public $url;

    public $attributes = [];

    protected $uri = 'Quote';

    protected $product;

    protected $params = [];

    protected $required = [];

    public function __construct() {
        $this->setEndPoint();
        $this->setRequiredAttributes();
    }

    protected function setResponse($response) {
        $this->resources = $response;
        return $this;
    }
    
    public function fetch($token, $params = []) {
        $client = new Client($token);
        $this->attributes = array_merge($this->attributes, $params);

        return $this->setResponse($client->request('POST', $this->url, [
            'form_params' => $this->attributes
        ]));
    }


    protected $coverageTypes = [
        'individual' => 1,
        'couple' => 2,
        'family' => 3,
        'dependents' => 4
    ];

    protected function setRequiredAttributes() {
        foreach ($this->required as $required) {
            $this->attributes[$required] = null;
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

    public static function listProductTypes() {
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
