<?php

namespace Nexusvc\CcgSalesApi\Schema;

use Nexusvc\CcgSalesApi\Traits\Jsonable;

class Schema {

    use Jsonable;

    protected $instance;
    protected $payload;

    protected $formatted = [];

    protected $schemaVersion;
    
    public function __construct($payload = [], $instance = null) {

        if(is_null($instance)) $this->instance = $payload;
        if(!is_null($instance)) $this->instance = $instance;

        if(is_object($payload)) $payload = $payload->toArray();

        $this->payload = $payload;
    }

    public function load($schema = "version-two") {
        $schema = "\\Nexusvc\\CcgSalesApi\\Schema\\" . studly_case($schema);

        // $this->payload['schema'] = (new \ReflectionClass($this))->getShortName();
        return (new $schema($this->payload, $this->instance));
    }

    public function getFormatted() {
        return $this;
    }

    public function format() {
        foreach($this->payload as $key => $value) {
            array_set($this->formatted, $key, $value);
        }

        return (new static($this->formatted, $this->instance))->setFormatted($this->formatted)->toArray();
    }

    public function setFormatted($formatted) {
        $this->formatted = $formatted;
        foreach($this->formatted as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function getPayload() {
        return $this->payload;
    }

    public function getDotArray() {
        return array_dot((new static($this->formatted))->setFormatted($this->formatted)->toArray());
    }

}
