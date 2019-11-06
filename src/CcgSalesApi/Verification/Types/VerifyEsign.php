<?php

namespace Nexusvc\CcgSalesApi\Verification\Types;

use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Verification\Verification;

class VerifyEsign extends Verification {

    protected $uri = "verification.esign.verify";

    protected static $params = [
        'tokenID'
    ];

    protected $required = [
        'tokenID'
    ];

    public function byToken($tokenId = null) {
        
        $token = self::$auth->accessToken;
        
        $params = self::$params;

        $client = new Client($token);

        $this->attributes = array_merge($this->attributes, $params);

        $verification = [];

        foreach($this->attributes as $attribute => $value) {
            array_set($verification, $attribute, $value);
        }

        $verification['tokenID'] = !is_null($tokenId) ? $tokenId : parent::$ccg->order->verification->tokenID;
        
        $this->url = strtr($this->url, $verification);

        $response = $this->setResponse($client->request('GET', $this->url, [
            'form_params' => $verification
        ]));
        
        foreach($response as $key => $value) {
            parent::$ccg->order->verification->$key = $value;
        }
        
        return parent::$ccg->order->verification;
    }

}
