<?php

namespace Nexusvc\CcgSalesApi\Order;

use Nexusvc\CcgSalesApi\Crypt\Crypt;
use Nexusvc\CcgSalesApi\Schema\Schema;
use Nexusvc\CcgSalesApi\Traits\Jsonable;
use Zend\Soap\Client;
use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpdatedErnollmentSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpdatedEnrollmentSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfEnrollment;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsertSimple;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsertSimpleResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsert;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentInsertResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSavedFromCancelling;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSavedFromCancellingResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberCancelled;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberCancelledResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ReferToAOR;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ReferToAORResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpgradePlan;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\UpgradePlanResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DowngradePlan;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DowngradePlanResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByName;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByNameResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfMember;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByGroup;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MemberSelectByGroupResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateMemberExists;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateMemberExistsResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateEmailExists;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DuplicateEmailExistsResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentLogSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentLogSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfFulfillmentLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ServiceLogSelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ServiceLogSelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfServiceLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\BillingHistorySelect;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\BillingHistorySelectResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfReceipt;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetSTMQuotes;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMPlanContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfSTMPersonContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMPersonContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfSTMKnockoutQuestions;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMKnockoutQuestions;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetSTMQuotesResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfSTMQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\STMQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTM;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTMResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTMSimple;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollSTMSimpleResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetAgeBandedPlanQuotes;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\AgeBandedPlanContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\GetAgeBandedPlanQuotesResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ArrayOfAgeBandedPlanQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\AgeBandedPlanQuoteContract;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollAgeBandedPlans;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollAgeBandedPlansResponse;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Enrollment;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Member;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Gender;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\EnrollmentStatus;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CoverageType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\MaritalStatus;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Account;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\AccountType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CreditCardAccountType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CheckingAccountType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Package;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Dependent;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\DependentType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Beneficiary;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\BeneficiaryType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\TrxType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Receipt;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\PackageBill;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\FulfillmentType;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\ServiceLog;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\CoverageTypeNew;
use Nexusvc\CcgSalesApi\Order\SoapApi\Types\Guid;

class ChargeOrder {

    use Jsonable;

    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    private function getXmlString($data) {
        $xml = "<?xml version='1.0'?><Enrollment xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns='http://enrollment.mymemberinfo.com/Enroll.xsd'><Member><GroupId>{$data['Member']['GroupId']}</GroupId><FirstName>{$data['Member']['FirstName']}</FirstName><LastName>{$data['Member']['LastName']}</LastName><AgentId>{$data['Member']['AgentId']}</AgentId><DateOfBirth>{$data['Member']['DateOfBirth']}</DateOfBirth><StartDate>{$data['Member']['StartDate']}</StartDate><Telephone1>{$data['Member']['Telephone1']}</Telephone1><EffectiveDate>{$data['Member']['EffectiveDate']}</EffectiveDate><Gender>{$data['Member']['Gender']}</Gender><EnrollmentStatus>NotEnrolled</EnrollmentStatus><Email>{$data['Member']['Email']}</Email><CoverageType>{$data['Member']['CoverageType']}</CoverageType><MaritalStatus>Unspecified</MaritalStatus><Address1>{$data['Member']['Address1']}</Address1><Address2>{$data['Member']['Address2']}</Address2><City>{$data['Member']['City']}</City><State>{$data['Member']['State']}</State><Zip>{$data['Member']['Zip']}</Zip><prevIns>0</prevIns>";

        if($data['Member']['VerificationMethod']==2) {
            $xml .= "<VerificationMethod>{$data['Member']['VerificationMethod']}</VerificationMethod><ESignIPaddress>{$data['Member']['ESignIPaddress']}</ESignIPaddress><ESignDateTimeStamp>{$data['Member']['ESignDateTimeStamp']}</ESignDateTimeStamp><ESignSMSRecipient>{$data['Member']['ESignSMSRecipient']}</ESignSMSRecipient><ESignUserDevice>{$data['Member']['ESignUserDevice']}</ESignUserDevice><ExternalUniqueID></ExternalUniqueID>";
        }
        
        $xml .= "</Member><Account>";
        $xml .= "<AccountType>{$data['Account']['AccountType']}</AccountType><AccountFirstName>{$data['Account']['AccountFirstName']}</AccountFirstName><AccountLastName>{$data['Account']['AccountLastName']}</AccountLastName><IsPayrollDeduct>false</IsPayrollDeduct><Address1>{$data['Account']['Address1']}</Address1><Address2>{$data['Account']['Address2']}</Address2><City>{$data['Account']['City']}</City><State>{$data['Account']['State']}</State><Zip>{$data['Account']['Zip']}</Zip><CreditCardNumber>{$data['Account']['CreditCardNumber']}</CreditCardNumber><Ccv>{$data['Account']['Ccv']}</Ccv><CreditCardExpirationMonth>{$data['Account']['CreditCardExpirationMonth']}</CreditCardExpirationMonth><CreditCardExpirationYear>{$data['Account']['CreditCardExpirationYear']}</CreditCardExpirationYear></Account>";

        foreach ($data['Package'] as $key => $package) {
            $xml .= "<Package><PlanId>{$package['PlanId']}</PlanId><CoverageType>{$package['CoverageType']}</CoverageType><IsOneTimeCharge>{$package['IsOneTimeCharge']}</IsOneTimeCharge></Package>";
        }
        
        if(count($data['Dependent'])) {
            foreach ($data['Dependent'] as $key => $dependent) {
                $xml .= "<Dependent><DependentId>0</DependentId><FirstName>{$dependent['FirstName']}</FirstName><LastName>{$dependent['LastName']}</LastName><DateOfBirth>{$dependent['DateOfBirth']}</DateOfBirth><DependentType>{$dependent['DependentType']}</DependentType><Gender>{$dependent['Gender']}</Gender><IsStudent>false</IsStudent><EffectiveDate>{$data['Member']['EffectiveDate']}</EffectiveDate></Dependent>";
            }
        }
        
        $xml .= "<Charge><BillDate>{$data['Member']['StartDate']}</BillDate><ReceiptDate>{$data['Member']['StartDate']}</ReceiptDate><TRXTYPE>S</TRXTYPE></Charge></Enrollment>";

        return $xml;
    }


    public function charge() {
        $enrollment = false;

        $enroll = new EnrollmentService();
        $schema = new Schema($this->order);
        $schema = $schema->load('enrollment')->format();
        $xml = $this->getXmlString($schema);

        try {
            $enrollment = $enroll->enroll( $xml );
        } catch (\Exception $e) {
            \Log::debug($xml);
        }

        return ['enrollment' => $enrollment];
    }

}
