<?php

namespace Nexusvc\CcgSalesApi\Auth;

use Nexusvc\CcgSalesApi\Client\Client;

class Authentication {

    protected $grant_type = 'password';

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
            throw new \Exception('Can not login because no credentials have been set.');
        
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
        unset($this->grant_type);

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

    public function setGrantType($grant_type) {
        if(!in_array(['password'], $this->grant_types)) 
            throw new \Exception('Grant type is currently unsupported.');

        $this->grant_type = $grant_type;
        return $this;
    }

    public function setCredentials($username, $password, $npn = null, $grant_type = null) {
        $this->setUsername($username);
        $this->setPassword($password);

        if(!is_null($npn)) $this->setNpn($npn);

        if(!is_null($grant_type)) $this->setGrantType($grant_type);

        return $this;
    }

    protected function getCredentials() {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'grant_type' => $this->grant_type
        ];
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true); 
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __toString()
    {
        return $this->toJson();
    }

}
