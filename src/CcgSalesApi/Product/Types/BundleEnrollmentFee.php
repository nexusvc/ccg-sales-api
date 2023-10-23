<?php

namespace Nexusvc\CcgSalesApi\Product\Types;

use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Product\Types\EnrollmentPlan;
use Nexusvc\CcgSalesApi\Product\Types\LimitedMedical;
use Nexusvc\CcgSalesApi\CCG;

class BundleEnrollmentFee extends EnrollmentPlan {

    protected $uri;

    protected static $params = [
        'groupID',
        'planID'
    ];

    protected $required = [
        'groupID',
        'planID'
    ];

    public function __construct(CCG &$ccg, $params, array $props = []) {
        self::$params = $params;
        // $this->setProduct($this);
        // $this->setType();

        foreach($props as $key => $value){
            $this->{$key} = $value;
        }

        $this->setEndPoint();
        $this->setRequiredAttributes();
        
        // parent::__construct($ccg, $params);
    }

    public function fetch() {
        //
        $token         = self::$auth->accessToken;
        $params        = self::$params;
        $params['npn'] = self::$auth->npn;

        $client = new Client($token);

        // SAMPLE HARD CODE
        // $params['groupId'] = $group_ids[0];
        // $params['planId'] = $plan_ids;

        // dd($this->attributes, $params, $this);
        $this->attributes = array_merge($this->attributes, $params);

        // $this->attributes = $this->reformatAttributes($this->attributes);

        if(array_key_exists('planId', $this->attributes) && array_key_exists('planID', $this->attributes)) {
            $this->attributes['planID'] = $this->attributes['planId'];
        }

        if(array_key_exists('groupId', $this->attributes) && array_key_exists('groupID', $this->attributes)) {
            $this->attributes['groupID'] = $this->attributes['groupId'];
        }

        // $this->url = $this->url . '?groupID=' . $params['groupId'] . '&planID=' . $params['planId'];

        $fee = $client->request('POST', $this->url, [
            'form_params' => $this->attributes
        ]);

        // parent::$ccg->order->addProduct(new \Nexusvc\CcgSalesApi\Product\Types\EnrollmentPlan(parent::$ccg, [], $fee));

        return $fee;
    }

}
