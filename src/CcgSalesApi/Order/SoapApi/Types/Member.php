<?php

namespace Nexusvc\CcgSalesApi\Order\SoapApi\Types;

use Nexusvc\CcgSalesApi\Order\SoapApi\EnrollmentService;
use Carbon\Carbon;

class Member extends EnrollmentService {

    protected $memberId;
    protected $firstName;
    protected $lastName;
    protected $mi;
    protected $agentId;
    protected $telephone1;
    protected $telephone2;
    protected $ssn;
    protected $address1;
    protected $address2;
    protected $city;
    protected $state;
    protected $zip;
    protected $verificationCode;
    protected $prevInsCompany;
    protected $eSignIPadress;
    protected $eSignSMSRecipient;
    protected $eSignUserDevice;
    protected $prevInsPolicyId;
    protected $externalSource;
    protected $groupId;
    protected $prevIns;
    protected $verificationMethod;
    protected $externalUniqueId;
    protected $dateOfBirth;
    protected $startDate;
    protected $terminateDate;
    protected $effectiveDate;
    protected $gender;
    protected $enrollmentStatus;
    protected $email;
    protected $coverageType;
    protected $maritalStatus;
    protected $eSignDateTimeStamp;

    protected function setMemberIdAttribute(string $memberId) {
        $this->memberId = $memberId;
    }

    protected function setFirstNameAttribute(string $firstName) {
        $this->firstName = $firstName;
    }

    protected function setLastNameAttribute(string $lastName) {
        $this->lastName = $lastName;
    }

    protected function setMiAttribute(string $mi) {
        $this->mi = $mi;
    }

    protected function setAgentIdAttribute(string $agentId) {
        $this->agentId = $agentId;
    }

    protected function setTelephone1Attribute(string $telephone1) {
        $this->telephone1 = $telephone1;
    }

    protected function setTelephone2Attribute(string $telephone2) {
        $this->telephone2 = $telephone2;
    }

    protected function setSsnAttribute(string $ssn) {
        $this->ssn = $ssn;
    }

    protected function setAddress1Attribute(string $address1) {
        $this->address1 = $address1;
    }

    protected function setAddress2Attribute(string $address2 = null) {
        $this->address2 = $address2;
    }

    protected function setCityAttribute(string $city) {
        $this->city = $city;
    }

    protected function setStateAttribute(string $state) {
        $this->state = $state;
    }

    protected function setZipAttribute(string $zip) {
        $this->zip = $zip;
    }

    protected function setVerificationCodeAttribute(string $verificationCode = null) {
        $this->verificationCode = $verificationCode;
    }

    protected function setPrevInsCompanyAttribute(string $prevInsCompany) {
        $this->prevInsCompany = $prevInsCompany;
    }

    protected function setESignIPadressAttribute(string $eSignIPadress = null) {
        $this->eSignIPadress = $eSignIPadress;
    }

    protected function setESignSMSRecipientAttribute(string $eSignSMSRecipient = null) {
        $this->eSignSMSRecipient = $eSignSMSRecipient;
    }

    protected function setESignUserDeviceAttribute(string $eSignUserDevice = null) {
        $this->eSignUserDevice = $eSignUserDevice;
    }

    protected function setPrevInsPolicyIdAttribute(string $prevInsPolicyId = null) {
        $this->prevInsPolicyId = $prevInsPolicyId;
    }

    protected function setExternalSourceAttribute(string $externalSource = null) {
        $this->externalSource = $externalSource;
    }

    protected function setGroupIdAttribute(int $groupId) {
        $this->groupId = $groupId;
    }

    protected function setPrevInsAttribute(int $prevIns) {
        $this->prevIns = $prevIns;
    }

    protected function setVerificationMethodAttribute(int $verificationMethod) {
        $this->verificationMethod = $verificationMethod;
    }

    protected function setExternalUniqueIdAttribute(int $externalUniqueId) {
        $this->externalUniqueId = $externalUniqueId;
    }

    protected function setDateOfBirthAttribute($dateOfBirth) {
        $this->dateOfBirth = Carbon::parse($dateOfBirth);
    }

    protected function setStartDateAttribute($startDate) {
        $this->startDate = Carbon::parse($startDate);
    }

    protected function setTerminateDateAttribute($terminateDate) {
        $this->terminateDate = Carbon::parse($terminateDate);
    }

    protected function setEffectiveDateAttribute($effectiveDate) {
        $this->effectiveDate = Carbon::parse($effectiveDate);
    }

    protected function setGenderAttribute($gender) {
        $this->gender = $gender;
    }

    protected function setEnrollmentStatusAttribute($enrollmentStatus) {
        $this->enrollmentStatus = $enrollmentStatus;
    }

    protected function setEmailAttribute(string $email) {
        $this->email = $email;
    }

    protected function setCoverageTypeAttribute($coverageType) {
        $this->coverageType = $coverageType;
    }

    protected function setMaritalStatusAttribute($maritalStatus) {
        $this->maritalStatus = $maritalStatus;
    }

    protected function setESignDateTimeStampAttribute($eSignDateTimeStamp) {
        $this->eSignDateTimeStamp = Carbon::parse($eSignDateTimeStamp);
    }

}
