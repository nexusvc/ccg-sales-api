<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi;

use Zend\Soap\Client;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Account;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\AccountType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\AgeBandedPlanContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\AgeBandedPlanQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfAgeBandedPlanQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfEnrollment;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfFulfillmentLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfMember;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfReceipt;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfServiceLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfSTMKnockoutQuestions;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfSTMPersonContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfSTMQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Beneficiary;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\BeneficiaryType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\BillingHistorySelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\BillingHistorySelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CheckingAccountType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CoverageType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CoverageTypeNew;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CreditCardAccountType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Dependent;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DependentType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DowngradePlan;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DowngradePlanResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateEmailExists;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateEmailExistsResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateMemberExists;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateMemberExistsResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollAgeBandedPlans;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollAgeBandedPlansResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Enrollment;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsert;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsertResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsertSimple;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsertSimpleResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentStatus;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTM;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTMResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTMSimple;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTMSimpleResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentLogSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentLogSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Gender;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetAgeBandedPlanQuotes;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetAgeBandedPlanQuotesResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetSTMQuotes;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetSTMQuotesResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Guid;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MaritalStatus;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Member;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberCancelled;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberCancelledResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSavedFromCancelling;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSavedFromCancellingResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByGroup;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByGroupResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByName;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByNameResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Package;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\PackageBill;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Receipt;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ReferToAOR;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ReferToAORResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ServiceLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ServiceLogSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ServiceLogSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMKnockoutQuestions;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMPersonContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMPlanContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\TrxType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpdatedEnrollmentSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpdatedEnrollmentSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpgradePlan;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpgradePlanResponse;

class EnrollmentService {

    protected $classmap = [];
    protected $config;
    protected $client;
    protected $wsdl = 'https://enrollment.mymemberinfo.com/EnrollmentService.asmx?WSDL';

    public function __construct($params = []) {

        $this->config   = [
            'classmap' => $this->classmap
        ];
        
        $this->client   = new Client($this->wsdl, $this->config);

        foreach($params as $key => $value) {
            $key = camel_case($key);
            if (property_exists($this, $key)) {
              $this->setPropertyValue($key, $value);
            }
        }
    }

    public function __get($property) {
      if (property_exists($this, $property)) {
        return $this->$property;
      }
    }

    public function __set($property, $value) {
      if (property_exists($this, $property)) {
        $this->setPropertyValue($property, $value);
      }

      return $this;
    }

    public function __toString() {
        return json_encode($this->toArray(), true);
    }

    public function enroll($xmlString) {
        $enroll = $this->client->EnrollmentInsertSimple(new EnrollmentInsertSimple($xmlString));
        return $this->response($enroll);
    }

    public function getBillingHistory(string $memberId, int $groupId) {
        return $this->response(($this->client->BillingHistorySelect(new BillingHistorySelect([
            'memberId' => $memberId, 
            'groupId' => $groupId
        ]))));
    }

    public function getFulfillmentHistory(string $memberId, int $groupId) {
        return $this->response(($this->client->FulfillmentLogSelect(new FulfillmentLogSelect([
            'memberId' => $memberId, 
            'groupId' => $groupId
        ]))));
    }

    public function serviceLogHistory(string $memberId, int $groupId) {
        return $this->response(($this->client->ServiceLogSelect(new ServiceLogSelect([
            'memberId' => $memberId, 'groupId' => $groupId
        ]))));
    }

    public function toArray() {
        $array = [];
        foreach($this->getPrivateProperties() as $key => $value) {
            $property = $value->name;
            array_set($array, studly_case($property), $this->$property);
        }

        return $array;
    }

    protected function response($response) {
        return $this->formatResponse((array) $response);
    }

    protected function formatResponse($json) {
        $formatted = [];

        foreach ($json as $key => $value) {
            if(str_contains($key, 'ID')) {
                $key = explode('ID', $key);
                $key = $key[0].'_id';
            }
            if(is_array($value) || is_object($value)) $value = $this->format($value);
            $formatted[camel_case($key)] = $value;
        }

        return $formatted;
    }

    protected function setPropertyValue($property, $value) {
        $property = studly_case($property);

        $method = "set{$property}Attribute";

        if(method_exists($this, $method)) {
            $this->$method($value);
        } else {
            dd($method);
            throw new \Exception('Invalid SOAP Element');
        }
    }

    protected function getPrivateProperties() {
        $reflection = new \ReflectionClass($this);
        return $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
    }
    
}
