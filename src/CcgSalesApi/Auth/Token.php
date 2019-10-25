<?php

namespace Nexusvc\CcgSalesApi\Auth;

use Nexusvc\CcgSalesApi\Contracts\Auth\Token as TokenContract;
use Nexusvc\CcgSalesApi\Client\Client;

class Token extends Authentication implements TokenContract {

    protected function setResponse($response) {
        $this->clearCredentials();
        foreach($response as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    
    public function getBearerToken() {
        $client = new Client;
        
        return $this->setResponse($client->request('POST', ccg_url('authentication.token'), [
            'form_params' => $this->getCredentials()
        ]));
    }

}
