<?php

namespace Nexusvc\CcgSalesApi\Traits;

use Nexusvc\CcgSalesApi\Contactable\Contactable as Contact;

trait Contactable {

    public function addContactable(Contact $contactable) {
        if(!property_exists($this, 'contactables')) $this->contactables = collect([]);
        $this->contactables->push($contactable);

        return $this;
    }

}
