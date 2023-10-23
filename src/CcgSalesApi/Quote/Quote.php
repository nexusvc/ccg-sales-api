<?php

namespace Nexusvc\CcgSalesApi\Quote;

use Nexusvc\CcgSalesApi\Auth\Authentication;
use Nexusvc\CcgSalesApi\CCG;
use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Product\GenericProduct;
use Nexusvc\CcgSalesApi\Product\Types\LimitedMedical;
use Nexusvc\CcgSalesApi\Product\Types\AddOn;
use Nexusvc\CcgSalesApi\Product\Types\Rate;
use Nexusvc\CcgSalesApi\Product\Types\ShortTermMedical;
use Nexusvc\CcgSalesApi\Product\Types\EnrollmentPlan;
use Nexusvc\CcgSalesApi\Product\Types\ProductBenefits;
use Nexusvc\CcgSalesApi\Product\Types\BundleEnrollmentFee;
use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Nexusvc\CcgSalesApi\Verification\Verification;

class Quote {
    
    use Jsonable;

    protected static $ccg;

    protected $url;

    protected $attributes = [];

    protected $uri = 'Quote';

    protected $product;

    protected static $auth;

    protected static $params = [];

    protected $required = [];

    public function __construct(CCG &$ccg, array $params = []) {
        self::$ccg  = $ccg;
        self::$auth = $ccg->auth;
        self::$params = $params;

        $this->setEndPoint();
        $this->setRequiredAttributes();
    }

    protected function setResponse($response) {
        $objects = collect([]);
        if($type = self::$params['type']) {
            foreach($response as $product) {
                $objects->push( (new $type(self::$ccg, self::$params, $product))->appendParams(self::$params) );
            }
        }
        $this->resources = $objects;
        return $this;
    }

    public function createVerification($verification) {
        $type = "\\Nexusvc\\CcgSalesApi\\Verification\\Types\\{$verification['type']}";
        
        $verification = collect($verification)->camelCaseKeys();
        $verification = $verification->toArray();

        return (new $type(self::$ccg, self::$params, $verification))->appendParams($verification);
    }

    public function createProduct($product) {
        $type = "\\Nexusvc\\CcgSalesApi\\Product\\Types\\{$product['type']}";
        
        $product = collect($product)->camelCaseKeys();
        $product = $product->toArray();

        return (new $type(self::$ccg, self::$params, $product))->appendParams($product);
    }
    
    public function fetch() {
        //
        $token         = self::$auth->accessToken;
        $params        = self::$params;
        $params['npn'] = self::$auth->npn;

        $client = new Client($token);

        $this->attributes = array_merge($this->attributes, $params);

        $this->attributes = $this->reformatAttributes($this->attributes);

        if(array_key_exists('planId', $this->attributes) && array_key_exists('planID', $this->attributes)) {
            $this->attributes['planID'] = $this->attributes['planId'];
        }

        if(array_key_exists('groupId', $this->attributes) && array_key_exists('groupID', $this->attributes)) {
            $this->attributes['groupID'] = $this->attributes['groupId'];
        }

        if(array_key_exists('state', $this->attributes)) $this->attributes['state'] = formatState($this->attributes['state']);

        // if(str_contains($this->url, 'GetVoiceVerificationScript'))  dd(response()->json($this->attributes));

        return $this->setResponse($client->request('POST', $this->url, [
            'form_params' => $this->attributes
        ]));
    }

    protected function reformatAttributes($attributes) {
        $formatted = [];

        foreach($attributes as $key => $value) {
            if(ends_with($key, 'Id')) {
                $key = str_replace('Id', 'ID', $key);
            }

            $formatted[$key] = $value;
        }
        return $formatted;
    }

    public static function recommendedProducts($products, $state) {

        return $products->first();

    }

    protected function setRequiredAttributes() {
        foreach ($this->required as $required) {
            $this->attributes[$required] = false;
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
        self::$params = array_merge(self::$params, $params);
        return $this;
    }

    public static function products() {
        self::setProductTypeClass();
        return (self::$params['type'])->fetch()->resources;
    }

    public static function verifications($type = null) {
        if(is_null($type)) return Verification::listVerificationTypes();

        return Verification::byType($type);
    }

    public static function setProductTypeClass() {
        try {
            $type = self::$params['type'];
        } catch(\Exception $e) {
            self::$params['type'] = 'Rate';
            return self::setProductTypeClass();
        }

        $type = "\\Nexusvc\\CcgSalesApi\\Product\\Types\\{$type}";
        self::$params['type'] = new $type(self::$ccg, self::$params);
    }

    public static function setVerificationTypeClass() {
        try {
            $type = self::$params['type'];
        } catch(\Exception $e) {
            self::$params['type'] = 'Esign';
            return self::setVerificationTypeClass();
        }

        $type = "\\Nexusvc\\CcgSalesApi\\Verification\\Types\\{$type}";
        self::$params['type'] = new $type(self::$ccg, self::$params);
    }

    public static function categories() {
        return GenericProduct::listProductTypes();
    }

    protected function setProduct(GenericProduct $product) {
        $this->product = $product;
        return $this;
    }

    protected function setVerification(Verification $verification) {
        $this->verification = $verification;
        return $this;
    }

    public function benefits() {
        $benefits = new ProductBenefits(self::$ccg, $this->toArray());
        $this->benefits = $benefits->fetch();
        return $this;
    }

    public function getBundleEnrollmentFee() {
        // Find Bundle Enrollment Fee
        $fee = new BundleEnrollmentFee(self::$ccg, $this->toArray());
        $this->fee = $fee->fetch();

        // Attach as product
        // $this->fee->addToOrder();
        return $this->fee;
    }

}
