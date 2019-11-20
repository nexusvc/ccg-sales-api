<?php

namespace Nexusvc\CcgSalesApi\Schema;

use Carbon\Carbon;
use GoetasWebservices\XML\XSDReader\SchemaReader;

class VoiceScript extends Schema {

    protected $schemaVersion = 1.0;

    protected $excludes = [];

    protected $keys = [
        'planId' => 'planID'
    ];

    public function format() {

        $this->setBrandName();
        $this->setCoverageType();
        $this->setPlanId();
        $this->setPayType();
        $this->setAddOnPlanIds();
        $this->setAbbrState();
        
        $this->formatKeys();
        
        return (new static($this->formatted))->setFormatted($this->formatted)->toArray();
    }

    protected function setBrandName() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                array_set($this->formatted, 'brandName', $product['brandName']);
            }
        }
    }

    protected function setPlanId() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                array_set($this->formatted, 'planId', $product['planId']);
            }
        }
    }

    protected function setCoverageType() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                array_set($this->formatted, 'coverageType', $product['coverageType']);
            }
        }
    }

    protected function setPayType() {
        if(!array_key_exists('routing', $this->payload['payable'])) array_set($this->formatted, 'payType', 'CC');
    }

    protected function setAddOnPlanIds() {
        $planIds = [];
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('addOnType', $product) && $product['addOnType']) {
                $planIds[] = $product['planId'];
            }
        }
        
        if(count($planIds)) array_set($this->formatted, 'addOnPlanIDs', implode(',', $planIds));
    }

    protected function setAbbrState() {
        if(count($this->payload['applicants'])) {
            foreach($this->payload['applicants'] as $applicant) {
                if(array_has($applicant, 'contactable.address.state')) {
                    $state = array_get($applicant, 'contactable.address.state');
                    array_set($this->formatted, 'state', $state);
                }
            }
        }
        
    }
    
}
