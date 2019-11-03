<?php

return [
    'base' => [
        /*
        |--------------------------------------------------------------------------
        | Production Base Address
        |--------------------------------------------------------------------------
        |
        | This is the production base url as defined by CCG Documentation
        | Documentation => SalesAPI => 2.0
        |
        */
        'production' => 'https://apps-salesapi.mymemberinfo.com/api/',

        /*
        |--------------------------------------------------------------------------
        | QA Base Address
        |--------------------------------------------------------------------------
        |
        | This is the production base url as defined by CCG Documentation
        | Documentation => SalesAPI => 2.0
        |
        */
        'development' => 'https://apps-salesapi-beta.mymemberinfo.com/api/',
    ],

    'authentication' => [
        'token' => 'Token'
    ],

    'quote' => [
        'rate' => 'Quote/GetQuotes',
        'add_on' => 'Benefit/GetAddOnPlans',
        'limited_medical' => 'Quote/GetLMQuotes',
        'short_term_medical' => 'Quote/GetSTMQuotes'
    ],

    'verification' => [
        'esign' => [
            'script' => 'Verification/GetEsignScript',
            'invite' => 'Verification/EsignInvitation',
            'verify' => 'Verification/GetESignVerification/caseID'
        ],
        'voice' => [
            'script' => 'Verification/GetVoiceVerificationScript',
        ],
    ],

];
