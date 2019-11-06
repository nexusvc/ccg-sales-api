<?php

namespace Nexusvc\CcgSalesApi\Schema;

use Carbon\Carbon;
use GoetasWebservices\XML\XSDReader\SchemaReader;

class Enrollment extends Schema {

    protected $schemaVersion = 1.0;

    protected $excludes = [
    ];

    protected $keys = [
    ];

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

        $this->formatted = ['Enrollment' => $this->formatted];

        $array = (new static($this->formatted))->setFormatted($this->formatted)->toArray();

        //set up the service client using WSDL
        $url         = "http://enrollment.mymemberinfo.com/EnrollmentInsert";
        $client     = new \SoapClient($url, array("trace" => 1, "exception" => 0));

        dd($client);

    }

    protected function setMember() {
        
        array_set($this->formatted, 'Member', [
            'GroupId' => false,
            'FirstName' => false,
            'LastName' => false,
            'AgentId' => false,
            'DateOfBirth' => false,
            'StartDate' => false,
            'Telephone1' => false,
            'TerminateDate' => false,
            'EffectiveDate' => false,
            'Gender' => false,
            'EnrollmentStatus' => false,
            'Email' => false,
            'CoverageType' => false,
            'MaritalStatus' => false,
            'Address1' => false,
            'Address2' => false,
            'City' => false,
            'State' => false,
            'Zip' => false,
            'prevIns' => false,
            'VerificationMethod' => false,
            'ESignIPaddress' => false,
            'ESignDateTimeStamp' => false,
            'ESignSMSRecipient' => false,
            'ESignUserDevice' => false,
            'ExternalUniqueID' => false,
        ]);
    }

    protected function setAccount() {
        
        array_set($this->formatted, 'Account', [
            'AccountType' => false,
            'AccountFirstName' => false,
            'AccountLastName' => false,
            'IsPayrollDeduct' => false,
            'Address1' => false,
            'Address2' => false,
            'City' => false,
            'State' => false,
            'Zip' => false,
            'CheckingAccountNumber' => false,
            'CheckingRoutingNumber' => false,
        ]);
    }

    protected function setPackages() {
        
        array_set($this->formatted, 'Package', [[
            'PlanId' => false,
            'CoverageType' => false,
            'IsOneTimeCharge' => false,
        ]]);
    }

    protected function setDependents() {
        
        array_set($this->formatted, 'Dependent', []);
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

    // function definition to convert array to xml
    protected function arrayToXml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
         }
    }
    
}
