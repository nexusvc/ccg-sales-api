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
        
        // $this->reader = new SchemaReader();
        // $this->schema = $reader->readFile("https://enrollment.mymemberinfo.com/Enroll.xsd");

        // dd($this->schema);
        
        $this->setMember();

        $this->setAccount();
        $this->setPackages();
        $this->setDependents();
        $this->setCharge();
        $this->setDiscountCouponCode();

        $array = (new static($this->formatted))->setFormatted($this->formatted)->toArray();
        $array['Package'] = [];
        foreach($this->payload['products'] as $product) {
            // $package = $xml->addChild('Package');
            $product = $this->getPackage($product);
            // foreach($product as $key => $value) {
                array_push($array['Package'], $product);
            // }
        }

        // $array = $this->soapify($array);
        
        
        //set up the service client using WSDL
        // $uri    = "https://enrollmentbeta.mymemberinfo.com/EnrollmentService.asmx?WSDL";
        // $client = new \SoapClient($uri);
        
        foreach($array as $key => $value) {
            $this->$key = $value;
        }

        return $this->toArray();

        // // dd($array);
        // // $response = $client->__soapCall("EnrollmentInsert", $array);
        // dd($client->__getTypes());

        // $result = $client->EnrollmentInsert(new \SoapParam($array, 'Enrollment'));
        // dd($result);

        // $params = new \SoapVar($xml->asXML(), XSD_ANYXML);
        // $result = $client->Echo($params);
        
        // dd($client, $params);
        

        return \Response::make($xml->asXML(), '200')->header('Content-Type', 'text/xml');
        

        return $xml->asXML();

    }

    protected function setMember() {
        // dd(array_dot($this->payload));
        array_set($this->formatted, 'Member', [
            'GroupId' => $this->getGroupId(),
            'FirstName' => $this->getPrimaryApplicant('firstName'),
            'LastName' =>  $this->getPrimaryApplicant('lastName'),
            'AgentId' => $this->getAgentId(),
            'DateOfBirth' =>  $this->getPrimaryApplicant('dob'),
            'StartDate' => false,
            'Telephone1' => $this->getPrimaryApplicant('contactable.phone.phone'),
            'TerminateDate' => null,
            'EffectiveDate' => false,
            'Gender' =>  $this->setGender($this->getPrimaryApplicant('gender')),
            'EnrollmentStatus' => 'NotEnrolled',
            'Email' => $this->getPrimaryApplicant('contactable.email.email'),
            'CoverageType' => $this->getCoverageType(),
            'MaritalStatus' => 'NotSpecified',
            'Address1' => $this->getPrimaryApplicant('contactable.address.street1'),
            'Address2' => $this->getPrimaryApplicant('contactable.address.street2'),
            'City' => $this->getPrimaryApplicant('contactable.address.city'),
            'State' => $this->getPrimaryApplicant('contactable.address.state'),
            'Zip' => $this->getPrimaryApplicant('contactable.address.zip'),
            'prevIns' => 0,
            'VerificationMethod' => $this->setVerificationMethod(),
            'ESignIPaddress' => array_get($this->payload, 'verification.esignIPAddress'),
            'ESignDateTimeStamp' => array_get($this->payload, 'verification.eSignAcceptedDate'),
            'ESignSMSRecipient' =>  array_get($this->payload, 'verification.esignRecipient'),
            'ESignUserDevice' =>  array_get($this->payload, 'verification.esignUserDevice'),
            'ExternalUniqueID' => array_get($this->payload, 'applicants.0.id'),
        ]);
    }

    protected function setVerificationMethod() {
        switch($this->instance->verification->type) {
            case 'Esign':
                return 2;
                break;
            case 'Voice':
                return 1;
        }
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
        
        // array_set($this->formatted, 'Dependent', []);
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
