<?php

namespace Nexusvc\CcgSalesApi\Crypt;

use Illuminate\Encryption\Encrypter;

class Crypt extends Encrypter {

    // Default EncryptionKey
    protected $key = "base64:c3SzeMQZZHPT+eLQH6BnpDhw/uKH2N5zgM2x2a8qpcA=";

    /**
     * Create a new encrypter instance.
     *
     * @param  string  $key
     * @param  string  $cipher
     * @return void
     *
     * @throws \RuntimeException
     */
    public function __construct($key = null, $cipher = 'AES-256-CBC')
    {
        if(is_null($key)) $key = $this->key;
        if(strpos($key, ':') !== false && explode(':', $key)[0] === 'base64') {
            $key = base64_decode(explode(':', $key)[1]);
        }
        parent::__construct($key, $cipher);
    }

}
