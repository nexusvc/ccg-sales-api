<?php

namespace Nexusvc\CcgSalesApi\Client;

use \GuzzleHttp\Client as GuzzleClient;

class Client extends GuzzleClient
{

    protected $headers = [];

    public function __construct($token = null) {
        if(isset($token) && !is_null($token)) $this->setHeaders(['headers' => ['Authorization' => 'Bearer ' . $token]]);
        parent::__construct(['base_uri' => ccg_url()]);
    }

    protected function setHeaders($headers) {
        $this->headers = array_merge($this->headers, $headers);
    }

    public function request($method, $uri = '', array $options = []) {
        if(count($this->headers)) $options = array_merge($options, $this->headers);
        // if($uri != "https://apps-salesapi-beta.mymemberinfo.com/api/Token") dd($options);
        return $this->buildResponse(parent::request($method, $uri, $options));
    }

    public function json($response) {
        return json_decode($response);
    }

    public function buildResponse($response) {
        $content_type = $response->getHeader('Content-Type');
        $body = $response->getBody();

        if(is_array($content_type) && str_contains($content_type[0], 'json')) {
            return $this->json($body);
        }

        return $body;
    }

    /**
     * Dynamically proxy static method calls.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return void
     */
    public static function __callStatic($method, $parameters)
    {
        if (! property_exists(get_called_class(), $method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        return static::${$method};
    }

}
