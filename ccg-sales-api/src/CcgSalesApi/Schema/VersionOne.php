<?php

namespace Nexusvc\CcgSalesApi\Schema;

use Carbon\Carbon;

class VersionOne extends Schema {

    protected $schemaVersion = 1.0;

    protected $excludes = ['contactable','type','id','address','relation'];

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

        $this->setPrimaryApplicant();
        $this->setPrimaryApplicantAddress();
        $this->setPrimaryTelephone();
        $this->setPrimaryEmail();

        $this->setEsignRecipient();

        $this->setCaseId();
        $this->setCoverageType();
        $this->setDependents();
        $this->setGender();
        $this->setGroupId();
        $this->setAgentId();
        $this->setPaymentInfo();
        $this->setPlans();
        $this->setEffectiveDate();

        $this->formatKeys();
        // Must be after formatKeys
        $this->formatDateOfBirth();
        
        return (new static($this->formatted))->setFormatted($this->formatted)->toArray();
    }

    protected function formatDateOfBirth() {
        return array_set($this->formatted, 'dateOfBirth', Carbon::parse($this->formatted['dateOfBirth'])->toW3cString());
    }

    protected function setEffectiveDate() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('effectiveDate', $product) && array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                array_set($this->formatted, 'effectiveDate', $product['effectiveDate']);
            }
        }

        if(!array_key_exists('effectiveDate', $this->formatted)) {
            $now = Carbon::now();
            
            $until15 = $now->diffInDays(Carbon::now()->firstOfMonth()->addDays(14));
            
            if($until15 > 0 && $until15 < 14) {
                return array_set($this->formatted, 'effectiveDate', Carbon::now()->firstOfMonth()->addDays(14)->toW3cString());
            }

            return array_set($this->formatted, 'effectiveDate', Carbon::now()->firstOfMonth()->addMonth()->toW3cString());
        }
    }

    protected function formatKeys() {
        $tmp = [];
        $array = $this->getDotArray();
        foreach($array as $key => $value) {
            if(array_key_exists($key, $this->keys)) $key = $this->keys[$key];
            array_set($tmp, $key, $value);
        }

        $this->formatted = $tmp;
    }

    protected function setPrimaryApplicant() {
        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] == 'primary') {
                foreach($applicant as $key => $value) {
                    if(!in_array($key, $this->excludes)) array_set($this->formatted, $key, $value);
                }
            }
        }
    }

    protected function setDependents() {
        if(count($this->payload['applicants']) === 1) return;
        
        array_set($this->formatted, 'dependents', collect([]));

        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] != 'primary') {
                $tmp = [];
                foreach($applicant as $key => $value) {
                    if(!in_array($key, $this->excludes)) array_set($tmp, $key, $value);
                }
                if($applicant['relation'] == 'spouse') $tmp['dependentType'] = 0;
                if($applicant['relation'] == 'child') $tmp['dependentType'] = 1;

                $this->formatted['dependents']->push($tmp);
            }
        }
    }

    protected function setPrimaryApplicantAddress() {
        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] == 'primary') {
                foreach($applicant['contactable'] as $key => $value) {
                    if($key == 'address') {
                        foreach($value as $key => $value) {
                            if(!in_array($key, $this->excludes)) array_set($this->formatted, $key, $value);
                        }
                    }
                }
            }
        }
    }

    protected function setPrimaryTelephone() {
        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] == 'primary') {
                foreach($applicant['contactable'] as $key => $value) {
                    if($key == 'phone') {
                        if(!in_array($key, $this->excludes)) array_set($this->formatted, 'telephone', $value['phone']);
                    }
                }
            }
        }
    }

    protected function setEsignRecipient() {
        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] == 'primary') {
                foreach($applicant['contactable'] as $key => $value) {
                    if($key == 'phone') {
                        if(!in_array($key, $this->excludes)) array_set($this->formatted, 'esignRecipient', $value['phone']);
                    }
                }
            }
        }
    }

    protected function setPrimaryEmail() {
        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] == 'primary') {
                foreach($applicant['contactable'] as $key => $value) {
                    if($key == 'email') {
                        if(!in_array($key, $this->excludes)) array_set($this->formatted, 'email', $value['email']);
                    }
                }
            }
        }
    }

    protected function setCaseId() {
        array_set($this->formatted, 'caseId', 0);
    }

    protected function setGroupId() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                array_set($this->formatted, 'groupId', $product['groupId']);
            }
        }
    }

    protected function setAgentId() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                array_set($this->formatted, 'agentId', $product['agentId']);
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

    protected function setGender() {
        switch( strtolower($this->formatted['gender']) ) {
            case 'male':
                $gender = 'M';
                break;
            case 'female':
                $gender = 'F';
                break;
        }

        array_set($this->formatted, 'gender', $gender);
    }

    protected function setPlanType($product) {
        if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
            return 0;
        } 

        if(array_key_exists('retailAmount', $product) && $product['retailAmount'] == 99.95) {
            return 2;
        }

        if(array_key_exists('addOnType', $product)) return 1;
    }

    protected function setPlans() {
        array_set($this->formatted, 'plans', collect([]));
        foreach($this->payload['products'] as $product) {
            $tmp = [];
            if(array_key_exists('groupId', $product) && $product['groupId']) {
                $tmp['groupID'] = $product['groupId'];
            }

            if(array_key_exists('planId', $product) && $product['planId']) {
                $tmp['planID'] = $product['planId'];
            }

            if(array_key_exists('retailAmount', $product) && $product['retailAmount']) {
                $tmp['amount'] = $product['retailAmount'];
            }

            if(array_key_exists('isOneTimeCharge', $product) && $product['isOneTimeCharge']) {
                $tmp['isOneTimeCharge'] = $product['isOneTimeCharge'];
            }

            $tmp['planType'] = $this->setPlanType($product);

            $this->formatted['plans']->push($tmp);
        }
    }

    protected function setPaymentInfo() {
        $payable = $this->instance->detokenize();
        if($payable['payType'] == 'CC') {
            $payable['payType'] = 0;
            $payable['accountNumber'] = "";
            $payable['routingNumber'] = "";
        }
        
        array_set($this->formatted, 'paymentInfo', $payable);
    }
    
}
