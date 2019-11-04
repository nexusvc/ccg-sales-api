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
        $this->loadConfigs();
        if(is_null($key)) $key = $this->key;
        if(strpos($key, ':') !== false && explode(':', $key)[0] === 'base64') {
            $key = base64_decode(explode(':', $key)[1]);
        }
        parent::__construct($key, $cipher);
    }

    public static function config($key = null, $dot = true) {
        $ccg = static::getInstance();
        $dot_config = array_dot($ccg->config);
        if(!$dot) return (is_null($key) ? $ccg->config : (Arr::has($ccg->config, $key) ? $ccg->config[$key] : null));
        return (is_null($key) ? $dot_config : (Arr::has($dot_config, $key) ? $dot_config[$key] : (Arr::has($ccg->config, $key) ? $ccg->config[$key] : null ) ) );
    }

    protected function loadConfigs() {
        $this->config = collect([]);
        $dir = new \DirectoryIterator(dirname(__FILE__).'/../Config');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $this->mergeConfigFrom(
                    __DIR__.'/../Config/'.$fileinfo->getFilename(), 
                    str_replace('.php','',$fileinfo->getFilename())
                );
            }
        }

        try {
            $this->key = $this->config['env']['encryption']['key'];
            $this->cipher = $this->config['env']['encryption']['cipher'];
        } catch (\Exception $e) {

        }
        
        return $this;
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->config->get($key, []);
        $this->config->put($key, array_merge(require $path, $config));
    }

}
