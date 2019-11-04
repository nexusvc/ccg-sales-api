<?php 

namespace Nexusvc\CcgSalesApi\Verification;

use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Quote\Quote;

class Verification extends Quote {

    protected $class;

    protected $type;

    protected $required = [];
    
    protected $uri;

    protected static $params = [];
    
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

        $payments = [
            [
                'type' => 'cc',
                'account' => '4833120068413351',
                'cvv' => 123,
                'expiration' => [
                    'month' => 03,
                    'year' => 24
                ]
            ],
            [
                'type' => 'ach',
                'account' => '000123456789',
                'routing' => '267083141'
            ]
        ];

        $crypt = new Crypt(
            $ccg::config('env.encryption.key'), 
            $ccg::config('env.encryption.cipher')
        );

        $verification['paymentMethod'] = $payment1 = $crypt->encrypt($payments[0]);
        $payment2 = $crypt->encrypt($payments[1]);

        // dd($payment1, $payment2, $crypt->decrypt('eyJpdiI6InRLSEpsNkdpSXVDbWhWcDFYK3NLUUE9PSIsInZhbHVlIjoiR2tudUNFUkJtQnFlK3hjeUpKc3RzTlVQaUtPNXhDSmJhRTVUTWcyZnVcL3FLRUZlNHJFa0JuWDRlc2JQZ0tvNk9lZ0hNSnVqTUNacDZZOEsyaG01cjd1UnBoSXFpc2E2VndqXC8yMUF1MXNtUFwvQkFYbFh4ZnJBVnZNTFAzSkRwRFZabXZsS1plVDNHekhTYkI5bXdIK1pXb2FoUE84U2RcLzREbG84ZTdlNWFnTzZDS25HOTBMdVNHYkJpSnJKNjZsSiIsIm1hYyI6IjZlYTkxZDkyY2I0ZGNlNDUyYWYyMzI1NzA4OWU2N2JmNmJjODRiZTRiYzZlOTI3MWQ4ODFjMjRkNzIzODNmNTYifQ==') );
        
        

        // dd($verification);

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
