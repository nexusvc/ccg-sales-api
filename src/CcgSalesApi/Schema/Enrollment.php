<?php

namespace Nexusvc\CcgSalesApi\Schema;

use Carbon\Carbon;
use GoetasWebservices\XML\XSDReader\SchemaReader;

class Enrollment extends Schema {

    protected $schemaVersion = 1.0;

    public $Member;

    public $Account;

    public $Package = [];

    public $Dependent = [];

    public $Charge = [];

    public $DiscountCouponCode;

    protected $excludes = [
    ];

    protected $keys = [
    ];

    protected function soapify(array $data) {
        foreach ($data as &$value) {
                if (is_array($value)) {
                        $value = $this->soapify($value);
                }
        }

        return new \SoapVar($data, SOAP_ENC_OBJECT);
    }

    public function format() {
        $this->setMember();
        $this->setAccount();
        $this->setPackages();
        $this->setDependents();
        $this->setCharge();
        $this->setPaymentInfo();
        $this->setDiscountCouponCode();

        $array = (new static($this->formatted))->setFormatted($this->formatted)->toArray();
        $array['Package'] = [];
        foreach($this->payload['products'] as $product) {
            $product = $this->getPackage($product);
            $array['Package'][] = $product;
        }
        
        foreach($array as $key => $value) {
            $this->$key = $value;
        }

        return $this->toArray();
    }

    protected function setEffectiveDate() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('effectiveDate', $product) && array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                return $product['effectiveDate'];
            } else if(array_key_exists('effectiveOn', $product) && array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                return $product['effectiveOn'];
            }
        }
    }

    protected function setPaymentInfo() {
        $pay = [];
        $payable = $this->instance->detokenize();
        if($payable['payType'] == 'CC') {
            $pay['CreditCardNumber'] = $payable['ccNumber'];
            $pay['Ccv'] = $payable['cvv'];
            $pay['CreditCardExpirationMonth'] = $payable['ccExpMonth'];
            $pay['CreditCardExpirationYear'] = $payable['ccExpYear'];
            $pay['AccountType'] = 'CreditCard';
            $pay['AccountFirstName'] = $this->getPrimaryApplicant('firstName');
            $pay['AccountLastName'] = $this->getPrimaryApplicant('lastName');
            $pay['IsPayrollDeduct'] = false;
            $pay['Address1'] = $this->getPrimaryApplicant('contactable.address.street1');
            $pay['Address2'] = $this->getPrimaryApplicant('contactable.address.street2');
            $pay['City'] = $this->getPrimaryApplicant('contactable.address.city');
            $pay['State'] = formatState($this->getPrimaryApplicant('contactable.address.state'));
            $pay['Zip'] = $this->getPrimaryApplicant('contactable.address.zip');
            $pay['CheckingAccountNumber'] = false;
            $pay['CheckingRoutingNumber'] = false;
        }
        
        array_set($this->formatted, 'Account', $pay);
    }

    protected function setMember() {

        $phone = strip_country_prefix(preg_replace("/[^0-9]/", "", $this->getPrimaryApplicant('contactable.phone.phone')));
        $esignRecipient = strip_country_prefix(preg_replace("/[^0-9]/", "", array_get($this->payload, 'verification.esignRecipient')));

        $member = [
            'GroupId' => $this->getGroupId(),
            'FirstName' => $this->getPrimaryApplicant('firstName'),
            'LastName' =>  $this->getPrimaryApplicant('lastName'),
            'AgentId' => $this->getAgentId(),
            'DateOfBirth' =>  \Carbon::parse($this->getPrimaryApplicant('dob'))->format('Y-m-d'),
            'StartDate' => \Carbon::parse(array_get($this->payload, 'products.0.chargeOn'))->format('Y-m-d'),
            'Telephone1' => $phone,
            'TerminateDate' => null,
            'EffectiveDate' => \Carbon::parse(array_get($this->payload, 'products.0.effectiveOn'))->format('Y-m-d'),
            'Gender' =>  $this->setGender($this->getPrimaryApplicant('gender')),
            'EnrollmentStatus' => 'NotEnrolled',
            'Email' => $this->getPrimaryApplicant('contactable.email.email'),
            'CoverageType' => $this->getCoverageType(),
            'MaritalStatus' => 'NotSpecified',
            'Address1' => $this->getPrimaryApplicant('contactable.address.street1'),
            'Address2' => $this->getPrimaryApplicant('contactable.address.street2'),
            'City' => $this->getPrimaryApplicant('contactable.address.city'),
            'State' => formatState($this->getPrimaryApplicant('contactable.address.state')),
            'Zip' => $this->getPrimaryApplicant('contactable.address.zip'),
            'prevIns' => 0,
            'VerificationMethod' => $this->setVerificationMethod()
        ];

        if($member['VerificationMethod'] == 2) {
            $member['ESignIPaddress'] = array_get($this->payload, 'verification.esignIPAddress');
            $member['ESignDateTimeStamp'] = array_get($this->payload, 'verification.esignAcceptedDate');
            $member['ESignSMSRecipient'] =  $esignRecipient;
            $member['ESignUserDevice'] =  array_get($this->payload, 'verification.esignUserDevice');
            $member['ExternalUniqueID'] = array_get($this->payload, 'applicants.0.id');
        }

        array_set($this->formatted, 'Member', $member);
    }

    protected function setVerificationMethod() {
        try {
            switch($this->instance->verification->type) {
                case 'Esign':
                    $type = 2;
                    break;
                case 'Voice':
                    $type = 1;
            }
        } catch(\Exception $e) {
            $type = 1;
        }

        return $type;
    }

    protected function getCoverageType() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                switch ($product['coverageType']) {
                    case 1:
                        return 'Single';
                        break;
                    case 2:
                        return 'Couple';
                        break;
                    case 3:
                        return 'Family';
                        break;
                    case 4:
                        return 'IndividualPlusDependent';
                        break;
                    default:
                        return;
                        break;
                }
            }
        }
    }

    protected function getAgentId() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                return $product['agentId'];
            }
        }
    }

    protected function setGender($gender) {
        return strtoupper(substr($gender, 0, 1));
    }

    protected function getPrimaryApplicant($attribute) {
        foreach($this->payload['applicants'] as $applicant) {
            if($applicant['relation'] == 'primary') {
                return array_get($applicant, $attribute);
            }
        }
    }

    protected function getGroupId() {
        foreach($this->payload['products'] as $product) {
            if(array_key_exists('quoteType', $product) && $product['quoteType'] == 'LM') {
                return $product['groupId'];
            }
        }
    }

    protected function setAccount() {
        
        array_set($this->formatted, 'Account', [
            'AccountType' => false,
            'AccountFirstName' => $this->getPrimaryApplicant('firstName'),
            'AccountLastName' => $this->getPrimaryApplicant('lastName'),
            'IsPayrollDeduct' => false,
            'Address1' => $this->getPrimaryApplicant('contactable.address.street1'),
            'Address2' => $this->getPrimaryApplicant('contactable.address.street2'),
            'City' => $this->getPrimaryApplicant('contactable.address.city'),
            'State' => $this->getPrimaryApplicant('contactable.address.state'),
            'Zip' => $this->getPrimaryApplicant('contactable.address.zip'),
            'CheckingAccountNumber' => false,
            'CheckingRoutingNumber' => false,
        ]);
    }

    protected function getPackage($product) {
        return [
            'PlanId' => array_get($product, 'planId'),
            'CoverageType' => $this->getCoverageType(),
            'IsOneTimeCharge' => array_get($product, 'isOneTimeCharge') ?: 0
        ];
    }

    protected function setPackages() {
        // $this->formatted['Package'] = [];
        // foreach($this->payload['products'] as $product) {
        //     array_push($this->formatted['Package'],);
        // }
        // dd($this->formatted);
    }

    protected function setDependents() {
        
       if(count($this->payload['applicants']) === 1) return;
               
       array_set($this->formatted, 'Dependent', collect([]));

       foreach($this->payload['applicants'] as $applicant) {
           if($applicant['relation'] != 'primary' && $applicant['relation'] != 'self') {
               $tmp = [];

               $tmp['FirstName'] = $applicant['firstName'];
               $tmp['LastName'] = $applicant['lastName'];
               $tmp['DateOfBirth'] = \Carbon::parse($applicant['dob'])->format('Y-m-d');
               $tmp['DependentType'] = ucfirst($applicant['relation']);
               $tmp['Gender'] = $this->setGender($applicant['gender']);

               $this->formatted['Dependent']->push($tmp);
           }
       }
    }

    protected function setCharge() {
        
        array_set($this->formatted, 'Charge', [
            'BillDate' => false,
            'ReceiptDate' => false,
            'TRXTYPE' => false
        ]);
    }

    protected function setDiscountCouponCode() {
        array_set($this->formatted, 'DiscountCouponCode', null);
    }
    
}
