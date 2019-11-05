<?php

namespace Nexusvc\CcgSalesApi\Traits;

trait Configurable {

    protected $config;
    
    public static function config($key = null, $dot = true) {
        $ccg = static::getInstance();
        
        $dot_config = array_dot($ccg->config);

        if(!$dot) {
            return (is_null($key) ? 
                $ccg->config : 
                ( array_has($ccg->config, $key) ? 
                    $ccg->config[$key] : 
                    null
                )
            );
        }

        return (is_null($key) ? 
            $dot_config : 
            ( array_has($dot_config, $key) ? 
                $dot_config[$key] : 
                ( array_has($ccg->config, $key) ? 
                    $ccg->config[$key] : 
                    null 
                ) 
            ) 
        );
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

        $this->setEnvironment();

        return $this;
    }

    protected function mergeConfigFrom($path, $key) {
        $config = $this->config->get($key, []);
        $this->config->put($key, array_merge(require $path, $config));
    }

    protected function setEnvironment() {
        $this->env = array_get($this->config, 'endpoints.env');
        return $this;
    }

    public static function url($endpoint = null) {
        $ccg = static::getInstance();
        $endpoints = array_dot($ccg->config['endpoints']);
        $suffix = array_has($endpoints, $endpoint) ? $endpoints[$endpoint] : $endpoint;

        return $endpoints['base.'.$ccg->env] . $suffix;
    }
    
}
