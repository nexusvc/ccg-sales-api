<?php

namespace Nexusvc\CcgSalesApi\Traits;

use Nexusvc\CcgSalesApi\Contactable\Contactable as Contact;

trait Contactable {

    public function addContactable(Contact $contactable) {
        if(!property_exists($this, 'contactable')) $this->contactable = collect([])->keyBy('type');
        $this->contactable->push($contactable);
        $this->contactable = $this->contactable->keyBy('type');
        return $this;
    }

}
