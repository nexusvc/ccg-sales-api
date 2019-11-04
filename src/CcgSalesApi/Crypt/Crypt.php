<?php

namespace Nexusvc\CcgSalesApi\Crypt;

use Illuminate\Encryption\Encrypter;
use Tightenco\Collect\Support\Collection;
use Tightenco\Collect\Support\Arr;

class Crypt extends Encrypter {

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

    protected function loadConfigs() {
        $this->config = new Collection;
        $dir = new \DirectoryIterator(dirname(__FILE__).'/../Config');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $this->mergeConfigFrom(
                    __DIR__.'/../Config/'.$fileinfo->getFilename(), 
                    str_replace('.php','',$fileinfo->getFilename())
                );
            }
        }

        // Filter Config to Encrpytion
        // $cryptConfigs = collect([]);
        try {
            $this->key = $this->config['env']['encryption']['key'];
            $this->cipher = $this->config['env']['encryption']['cipher'];
        } catch (\Exception $e) {

        }
        
        return $this;
    }

    public static function config($key = null, $dot = true) {
        $ccg = static::getInstance();
        
        $dot_config = Arr::dot($ccg->config);

        if(!$dot) return (is_null($key) ? $ccg->config : (Arr::has($ccg->config, $key) ? $ccg->config[$key] : null));

        return (is_null($key) ? $dot_config : (Arr::has($dot_config, $key) ? $dot_config[$key] : (Arr::has($ccg->config, $key) ? $ccg->config[$key] : null ) ) );
    }

    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->config->get($key, []);
        $this->config->put($key, array_merge(require $path, $config));
    }

}
