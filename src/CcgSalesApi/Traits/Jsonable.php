<?php

namespace Nexusvc\CcgSalesApi\Traits;

trait Jsonable {
    
    public function toArray() {
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
