<?php

namespace Nexusvc\CcgSalesApi\Verification\Types;

use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Verification\Verification;

class Esign extends Verification {

    protected $uri = "verification.esign.invite";

    protected $phone = false;

    protected static $params = [
        'caseID',
        'groupID',
        'effectiveDate',
        'dateOfBirth',
        'firstName',
        'lastName',
        'gender',
        'email',
        'telephone',
        'city',
        'state',
        'zip',
        'address1',
        'address2',
        'agentID',
        'esignRecipient',
        'plans',
        'plans.groupID',
        'plans.planID',
        'plans.amount',
        'plans.planType',
        'dependents',
        'dependents.firstName',
        'dependents.lastName',
        'dependents.dateOfBirth',
        'dependents.dependentType',
        'paymentInfo',
        'paymentInfo.payType',
        'paymentInfo.ccNumber',
        'paymentInfo.ccExpMonth',
        'paymentInfo.ccExpYear',
        'paymentInfo.cvv',
        'paymentInfo.routingNumber',
        'paymentInfo.accountNumber',
        'scheduleDate'
    ];

    protected $required = [
        'groupID',
        'effectiveDate',
        'dateOfBirth',
        'firstName',
        'lastName',
        'gender',
        'email',
        'telephone',
        'city',
        'state',
        'zip',
        'address1',
        'agentID',
        'esignRecipient',
        'plans',
        'plans.groupID',
        'plans.planID',
        'plans.amount',
        'plans.planType',
        'paymentInfo',
        'paymentInfo.payType'
    ];

    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    public function status() {
        $verify = new VerifyEsign(parent::$ccg, $this->toArray());
        return $verify->byToken($this->token)->toArray();
    }

    public function usingPhoneNumber($phone = null) {
        if($phone) $this->phone = $phone;
        return $this;
    }

    public function invite($callbackUrl = null) {
        
        $token = self::$auth->accessToken;
        
        $params = self::$params;

        $client = new Client($token);

        $this->attributes = array_merge($this->attributes, $params);

        $verification = [];

        foreach($this->attributes as $attribute => $value) {
            array_set($verification, $attribute, $value);
        }

        $schema = new \Nexusvc\CcgSalesApi\Schema\Schema(parent::$ccg->order);
        $verification = $schema->load('version-one')->format();

        if(!is_null($callbackUrl)) $this->esignCallbackUrl = $verification['esignCallbackUrl'] = $callbackUrl;

        if(array_key_exists('state', $verification)) $verification['state'] = formatState($verification['state']);

        if($this->phone) $verification['esignRecipient'] = $this->phone;

        try {
            $response = $this->setResponse($client->request('POST', $this->url, [
                'form_params' => $verification
            ]));
        } catch(\Expiration $e) {
            return $e->getMessage();
        }
        
        
        $this->invited = true;

        foreach($response as $key => $value) {
            $this->$key = $value;
        }

        parent::$ccg->order->addVerification($this);

        return $this;
    }

}


