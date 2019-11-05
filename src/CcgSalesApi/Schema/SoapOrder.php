<?php

namespace Nexusvc\CcgSalesApi\Schema;

use Carbon\Carbon;
use GoetasWebservices\XML\XSDReader\SchemaReader;

class SoapOrder extends Schema {

    protected $schemaVersion = 0.9;

    protected $excludes = [
        'address',
        'contactable',
        'id',
        'relation',
        'type'
    ];

    protected $keys = [
        'caseId' => 'caseID',
        'groupId' => 'groupID',
        'dob' => 'dateOfBirth',
        'phone' => 'telephone',
        'street1' => 'address1',
        'street2' => 'address2',
        'agentId' => 'agentID'
    ];

    public function format() {
        
        $this->reader = new SchemaReader();
        $this->schema = $reader->readFile("https://enrollment.mymemberinfo.com/Enroll.xsd");

        dd($this->schema);
        // $this->setMember();
        // $this->setAccount();
        // $this->setPackages();
        // $this->setDependents();
        // $this->setCharge();
        // $this->setDiscountCouponCode();
        
        return (new static($this->formatted))->setFormatted($this->formatted)->toArray();
    }
    
}
