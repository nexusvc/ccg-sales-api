<?php

namespace Nexusvc\CcgSalesApi\Verification\Types;

use Nexusvc\CcgSalesApi\Verification\Verification;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Client\Client;

use Tightenco\Collect\Support\Collection;

class VerifyEsign extends Verification {

    protected $uri = "verification.esign.verify";

    protected static $params = [
        'caseID'
    ];

    protected $required = [
        'caseID'
    ];

    public function byCaseId(&$ccg, $caseId) {
        
        $token = self::$auth->accessToken;
        
        $params = self::$params;

        $client = new Client($token);

        $this->attributes = array_merge($this->attributes, $params);

        $verification = [];

        foreach($this->attributes as $attribute => $value) {
            array_set($verification, $attribute, $value);
        }
        
        $verification['caseID'] = $caseId;

        $this->url = strtr($this->url, $verification);

        $response = $this->setResponse($client->request('GET', $this->url, [
            'form_params' => $verification
        ]));
        
        $ccg->order->verification = $ccg->order->verification->toArray();
        
        foreach($response as $key => $value) {
            array_set($ccg->order->verification, $key, $value);
        }
        
        return $ccg;
    }

    public function invite(&$ccg) {

        $token = self::$auth->accessToken;
        
        $params = self::$params;

        $client = new Client($token);

        $this->attributes = array_merge($this->attributes, $params);

        $verification = [];

        foreach($this->attributes as $attribute => $value) {
            array_set($verification, $attribute, $value);
        }
        
        $verification['caseID'] = $ccg->order->verification->caseId;

        $this->url = strtr($this->url, $verification);

        $response = $this->setResponse($client->request('GET', $this->url, [
            'form_params' => $verification
        ]));

        foreach($response as $key => $value) {
            $ccg->order->verification->$key = $value;
        }
        
        return $ccg;
    }

}
