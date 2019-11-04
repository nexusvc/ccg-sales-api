<?php

namespace Nexusvc\CcgSalesApi\Auth;

use Nexusvc\CcgSalesApi\Client\Client;
use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Nexusvc\CcgSalesApi\Exceptions\InvalidGrantType;
use Nexusvc\CcgSalesApi\Exceptions\InvalidCredentials;

class Authentication {

    use Jsonable;

    protected $grantType = 'password';

    protected $username;

    protected $token = Token::class;

    protected $password;

    public function __construct($instance = null)
    {
        if($instance && is_object($instance)) {
            foreach($instance as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function Token() {
        return $this->token = ($this->token instanceof Token) ? $this->token : new Token($this);
    }

    public function login($username, $password, $npn = null) {
        
        $this->setCredentials($username, $password, $npn);

        if(!$this->username || !$this->password) 
            throw new InvalidCredentials('Can not login because no credentials have been set.');
        
        if($token = $this->token()->getBearerToken()) {
            foreach($token as $key => $value) {
                $this->$key = $value;
            }

            $this->clearCredentials();
        }
        
        return $this;
    }

    public function clearCredentials() {
        unset($this->username);
        unset($this->password);
        unset($this->grantType);

        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setNpn($npn) {
        $this->npn = $npn;
        return $this;
    }

    public function setGrantType($grantType) {
        if(!in_array(['password'], $this->grantTypes)) 
            throw new InvalidGrantType('Grant type is currently unsupported.');

        $this->grantType = $grantType;
        return $this;
    }

    public function setCredentials($username, $password, $npn = null, $grantType = null) {
        $this->setUsername($username);
        $this->setPassword($password);

        if(!is_null($npn)) $this->setNpn($npn);

        if(!is_null($grantType)) $this->setGrantType($grantType);

        return $this;
    }

    protected function getCredentials() {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'grant_type' => $this->grantType
        ];
    }

}
