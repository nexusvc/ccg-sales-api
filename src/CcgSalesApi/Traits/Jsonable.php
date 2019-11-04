<?php

namespace Nexusvc\CcgSalesApi\Traits;

trait Jsonable {
    
    public function toArray() {

        if(property_exists($this, 'appends')) {
            foreach($this->appends as $attribute) {
                $attribute = studly_case($attribute);
                $method = "set{$attribute}Attribute";
                if(method_exists($this, $method)) $this->$method();
            }
        }
        
        $array = json_decode(json_encode($this), true);

        return $array;
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

    public function __get($attribute) {
        if(!property_exists($this, $attribute)) {
            if(property_exists($this, 'appends')) {
                $attributeFormatted = studly_case($attribute);
                $method = "set{$attributeFormatted}Attribute";
                if(method_exists($this, $method)) $this->$method();

                if(property_exists($this, $attribute)) return $this->$attribute;
            }    
        }
        
    }
    
}
