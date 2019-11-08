<?php

namespace Nexusvc\CcgSalesApi\Order;

require_once __DIR__ . '/../../../vendor/autoload.php';

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

    protected $classmap;

    protected $client;

    protected $config;

    protected $order;

    protected $wsdl;

    public function __construct(Order $order) {
        
        $this->order = $order;

        $this->classmap = [
            // 'Account' => Account::class,
            // 'AccountType' => AccountType::class,
            // 'AgeBandedPlanContract' => AgeBandedPlanContract::class,
            // 'AgeBandedPlanQuoteContract' => AgeBandedPlanQuoteContract::class,
            // 'ArrayOfAgeBandedPlanQuoteContract' => ArrayOfAgeBandedPlanQuoteContract::class,
            // 'ArrayOfEnrollment' => ArrayOfEnrollment::class,
            // 'ArrayOfFulfillmentLog' => ArrayOfFulfillmentLog::class,
            // 'ArrayOfMember' => ArrayOfMember::class,
            // 'ArrayOfReceipt' => ArrayOfReceipt::class,
            // 'ArrayOfServiceLog' => ArrayOfServiceLog::class,
            // 'ArrayOfSTMKnockoutQuestions' => ArrayOfSTMKnockoutQuestions::class,
            // 'ArrayOfSTMPersonContract' => ArrayOfSTMPersonContract::class,
            // 'ArrayOfSTMQuoteContract' => ArrayOfSTMQuoteContract::class,
            // 'Beneficiary' => Beneficiary::class,
            // 'BeneficiaryType' => BeneficiaryType::class,
            // 'BillingHistorySelect' => BillingHistorySelect::class,
            // 'BillingHistorySelectResponse' => BillingHistorySelectResponse::class,
            // 'CheckingAccountType' => CheckingAccountType::class,
            // 'CoverageType' => CoverageType::class,
            // 'CoverageTypeNew' => CoverageTypeNew::class,
            // 'CreditCardAccountType' => CreditCardAccountType::class,
            // 'Dependent' => Dependent::class,
            // 'DependentType' => DependentType::class,
            // 'DowngradePlan' => DowngradePlan::class,
            // 'DowngradePlanResponse' => DowngradePlanResponse::class,
            // 'DuplicateEmailExists' => DuplicateEmailExists::class,
            // 'DuplicateEmailExistsResponse' => DuplicateEmailExistsResponse::class,
            // 'DuplicateMemberExists' => DuplicateMemberExists::class,
            // 'DuplicateMemberExistsResponse' => DuplicateMemberExistsResponse::class,
            // 'EnrollAgeBandedPlans' => EnrollAgeBandedPlans::class,
            // 'EnrollAgeBandedPlansResponse' => EnrollAgeBandedPlansResponse::class,
            // 'Enrollment' => Enrollment::class,
            // 'EnrollmentInsert' => EnrollmentInsert::class,
            // 'EnrollmentInsertResponse' => EnrollmentInsertResponse::class,
            // 'EnrollmentInsertSimple' => EnrollmentInsertSimple::class,
            // 'EnrollmentInsertSimpleResponse' => EnrollmentInsertSimpleResponse::class,
            // 'EnrollmentSelect' => EnrollmentSelect::class,
            // 'EnrollmentSelectResponse' => EnrollmentSelectResponse::class,
            // 'EnrollmentStatus' => EnrollmentStatus::class,
            // 'EnrollSTM' => EnrollSTM::class,
            // 'EnrollSTMResponse' => EnrollSTMResponse::class,
            // 'EnrollSTMSimple' => EnrollSTMSimple::class,
            // 'EnrollSTMSimpleResponse' => EnrollSTMSimpleResponse::class,
            // 'FulfillmentLog' => FulfillmentLog::class,
            // 'FulfillmentLogSelect' => FulfillmentLogSelect::class,
            // 'FulfillmentLogSelectResponse' => FulfillmentLogSelectResponse::class,
            // 'FulfillmentType' => FulfillmentType::class,
            // 'Gender' => Gender::class,
            // 'GetAgeBandedPlanQuotes' => GetAgeBandedPlanQuotes::class,
            // 'GetAgeBandedPlanQuotesResponse' => GetAgeBandedPlanQuotesResponse::class,
            // 'GetSTMQuotes' => GetSTMQuotes::class,
            // 'GetSTMQuotesResponse' => GetSTMQuotesResponse::class,
            // 'guid' => Guid::class,
            // 'MaritalStatus' => MaritalStatus::class,
            // 'Member' => Member::class,
            // 'MemberCancelled' => MemberCancelled::class,
            // 'MemberCancelledResponse' => MemberCancelledResponse::class,
            // 'MemberSavedFromCancelling' => MemberSavedFromCancelling::class,
            // 'MemberSavedFromCancellingResponse' => MemberSavedFromCancellingResponse::class,
            // 'MemberSelectByGroup' => MemberSelectByGroup::class,
            // 'MemberSelectByGroupResponse' => MemberSelectByGroupResponse::class,
            // 'MemberSelectByName' => MemberSelectByName::class,
            // 'MemberSelectByNameResponse' => MemberSelectByNameResponse::class,
            // 'Package' => Package::class,
            // 'PackageBill' => PackageBill::class,
            // 'Receipt' => Receipt::class,
            // 'ReferToAOR' => ReferToAOR::class,
            // 'ReferToAORResponse' => ReferToAORResponse::class,
            // 'ServiceLog' => ServiceLog::class,
            // 'ServiceLogSelect' => ServiceLogSelect::class,
            // 'ServiceLogSelectResponse' => ServiceLogSelectResponse::class,
            // 'STMKnockoutQuestions' => STMKnockoutQuestions::class,
            // 'STMPersonContract' => STMPersonContract::class,
            // 'STMPlanContract' => STMPlanContract::class,
            // 'STMQuoteContract' => STMQuoteContract::class,
            // 'TRXTYPE' => TrxType::class,
            // 'UpdatedEnrollmentSelectResponse' => UpdatedEnrollmentSelectResponse::class,
            // 'UpdatedErnollmentSelect' => UpdatedErnollmentSelect::class,
            // 'UpgradePlan' => UpgradePlan::class,
            // 'UpgradePlanResponse' => UpgradePlanResponse::class,
        ];

        $this->config   = [
            'classmap' => $this->classmap
        ];

        $this->wsdl     = 'https://enrollment.mymemberinfo.com/EnrollmentService.asmx?WSDL';
        
        $this->client   = new Client($this->wsdl, $this->config);
    }

    public function charge() {
        $enroll = new EnrollmentService();
        return $enroll->getBillingHistory('CTC3014992', 12360);
        // $billingSelect = new BillingHistorySelect(['memberId' => 'CTC3014992', 'groupId' => '12360']);

        // $response = ($this->client->BillingHistorySelect($billingSelect));
        // return (array) $response;
        // dd($this->client->getTypes());
        
    }

    // public static function charge(Order $order) {

    //     $charge = new self($order);

    //     $schema = new Schema($charge);
    //     return $schema->load('enrollment')->format();
    // }

}
