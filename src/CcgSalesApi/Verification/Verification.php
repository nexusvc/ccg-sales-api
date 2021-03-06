<?php 

namespace Nexusvc\CcgSalesApi\Verification;

use Nexusvc\CcgSalesApi\CCG;
use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Order\Order;
use Nexusvc\CcgSalesApi\Quote\Quote;
use Nexusvc\CcgSalesApi\Traits\Jsonable;

class Verification extends Quote {

    use Jsonable;

    protected $class;

    public $type;

    protected $required = [];
    
    protected $uri;

    protected static $params = [];
    
    protected $invited = false;

    public function __construct(CCG &$ccg, $params, array $props = []) {

        $this->setType();

        foreach($props as $key => $value){
            $this->{$key} = $value;
        }
        
        parent::__construct($ccg, $params);
    }

    protected function setResponse($response) {
        return $response;
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
                $verification = new $class(self::$ccg, self::$params);
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

    public function addToOrder() {
        parent::$ccg->order->addVerification($this);
        return $this;
    }


}
