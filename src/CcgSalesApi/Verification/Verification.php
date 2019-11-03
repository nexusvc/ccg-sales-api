<?php 

namespace Nexusvc\CcgSalesApi\Verification;

use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Client\Client;

class Verification extends Quote {

    protected $uri;

    protected static $params = [];

    protected $required = [];

    public $invited = false;

    public function __construct($auth, $params, array $props = []) {

        $this->setType();

        foreach($props as $key => $value){
            $this->{$key} = $value;
        }
        
        parent::__construct($auth, $params);
    }

    protected function setResponse($response) {

        return $response;
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

        // @todo: Filter Applicant for Primary
        // set verification like so

        $verification['caseID'] = 0;
        $verification['groupID'] = 12362;
        $verification['effectiveDate'] = "2019-12-01T16:14:53.2234098+05:30";
        $verification['dateOfBirth'] = "1987-12-01T16:14:53.2234098+05:30";
        $verification['firstName'] = "John Paul";
        $verification['lastName'] = "Medina";
        $verification['gender'] = "M";
        $verification['email'] = "jp@leadtrust.io";
        $verification['telephone'] = "3058049506";
        $verification['city'] = "Miami";
        $verification['state'] = "FL";
        $verification['zip'] = "33196";
        $verification['address1'] = "15173 SW 117TH TERR";
        $verification['agentID'] = 100038079;
        $verification['coverageType'] = 1;
        $verification['esignRecipient'] = "3058049506";
        $verification['plans'] = [
            [
                "groupID" => 12362,
                "planID" => 5,
                "amount" => "269.95",
                "planType" => 0
            ],
            [
                "planID" => 1,
                "amount" => "99.95",
                "planType" => 2
            ],
            [
                "groupID" => 12365,
                "planID" => 727,
                "amount" => "76.90",
                "planType" => 1
            ]
        ];
        $verification['paymentInfo'] = [
            "payType" => 0,
            "ccNumber" => "4833120068413351",
            "ccExpMonth" => "03",
            "ccExpYear" => "24",
            "cvv" => "003",
            "routingNumber" => "",
            "accountNumber" => "",
        ];

        // $verification['brandName'] = "Health Shield";
        // $verification['planID'] = '5';
        // // $verification['addOnPlanIds'] = 727;
        // $verification['payType'] = 'CC';
        // $verification['state'] = 'FL';
        // $verification['coverageType'] = 1;

        // dd($order, $this->attributes, $params);
        $response = $this->setResponse($client->request('POST', $this->url, [
            'form_params' => $verification
        ]));
        
        $this->invited = true;

        foreach($response as $key => $value) {
            $ccg->order->verification->$key = $value;
        }

        return $ccg;
    }

    protected function setType() {
        $this->type = (new \ReflectionClass($this))->getShortName();
        $this->class = static::class;
    }

    public static function byType($type) {
        $types = collect(static::listVerificationTypes());
        return $verification = $types->filter(function($verification) use ($type) {
            return $verification->type == studly_case($type);
        })->first();
    }

    public static function listVerificationTypes() {
        $verifications = [];
        $dir = new \DirectoryIterator(dirname(__FILE__).'/Types');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $class_name = str_replace('.php','',$fileinfo->getFilename());
                $class = '\\Nexusvc\\CcgSalesApi\\Verification\\Types\\' . $class_name;
                $verification = new $class(self::$auth, self::$params);
                array_push($verifications, $verification);
            }
        }

        return $verifications;
    }

    protected function setEndPoint() {
        if(isset($this->uri)) return $this->url = ccg_url($this->uri);

        $this->uri = $uri = 'verification.' . strtolower(snake_case((new \ReflectionClass($this))->getShortName()));
        return $this->url = ccg_url($uri);
    }

    public function addToOrder(Order &$order) {
        $order->addVerification($this);
        return $this;
    }


}
