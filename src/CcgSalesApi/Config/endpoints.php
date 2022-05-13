<?php

return [
    'env' => 'production',

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
        'uca_add_on' => 'Quote/GetLMQuotes',
        'short_term_medical' => 'Quote/GetSTMQuotes',
        'product_benefits' => 'Benefit/GetBenefits'
    ],

    'verification' => [
        'esign' => [
            'script' => 'Verification/GetEsignScript',
            'invite' => 'Verification/EsignInvitation',
            'verify' => 'Verification/GetESignVerification/tokenID'
        ],
        'voice' => [
            'script' => 'Verification/GetVoiceVerificationScript',
        ],
    ],

];
