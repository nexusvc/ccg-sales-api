<?php

namespace Nexusvc\CcgSalesApi\Verification\Types;

use Carbon\Carbon;
use Nexusvc\CcgSalesApi\Verification\Verification;

class Voice extends Verification {

    protected $uri = "verification.voice.script";

    public $script;

    protected $contents;

    protected $variables = [];

    protected static $params = [
        'brandName',
        'planID',
        'addOnPlanIDs',
        'payType',
        'state',
        'coverageType'
    ];

    protected $required = [
        'brandName',
        'planID',
        'payType',
        'state',
        'coverageType'
    ];

    protected function setResponse($response) {
        return $this->getVariablesAndBody($response->getContents());
    }

    protected function getVariablesAndBody($html) {

        $content = [];

        $remove = substr($html, 0, strpos($html, '<div style="font-family: abeezee;">'));
        $html   = str_replace($remove, '', $html);
        $html   = str_replace('<div style="font-family: abeezee;">', '', $html);
        $html   = str_replace('</div>', '', $html);
        $html   = substr($html, 0, strpos($html, '</body>'));
        
        $this->script = $content['script'] = trim(preg_replace('/\s+/', ' ', $html));

        // Lets Grab the variables from script
        $content['variables'] = $this->getVariablesInContent($this->script, '##', '##');

        // TEMP - Include for Verification Agent Replacement
        // $content['variables'][] = '&lt;Your Name&gt;';

        foreach ($content['variables'] as $variable) {
            // Set a temporary false value
            // Filter Duplicates
            array_set($this->variables, $variable, false);
        }

        $this->standardizeContent();

        $this->contents = $content;

        return $this;
    }

    protected function standardizeContent() {
        // REPLACE ##VARIABLES## w/ $cameCaseVariable

        $content = $this->script;

        foreach($this->variables as $key => $value) {
            $content = str_replace("##{$key}##", '{$'.camel_case($key).'}', $content);
        }

        // Temporary Replace Ver Agent
        $content = str_replace("&lt;Your Name&gt;", '{$verificationAgent}', $content);

        $this->script = $content;

        $formattedVariables = [];

        foreach($this->variables as $key => $value) {
            if($key != '&lt;YourName&gt;' || $key != "<YourName>") array_set($formattedVariables, camel_case($key), $value);
        }

        // Temporary Create Verification Agent
        array_set($formattedVariables, camel_case('verificationAgent'), false);

        $this->variables = $formattedVariables;
        return $this;
    }

    protected function getVariablesInContent($str, $startDelimiter, $endDelimiter) {
      $contents = [];

      $startDelimiterLength = strlen($startDelimiter);
      $endDelimiterLength   = strlen($endDelimiter);

      $startFrom = $contentStart = $contentEnd = 0;

      while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
        $contentStart += $startDelimiterLength;
        $contentEnd = strpos($str, $endDelimiter, $contentStart);
        if (false === $contentEnd) {
          break;
        }
        $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
        $startFrom = $contentEnd + $endDelimiterLength;
      }

      return $contents;
    }

    protected function getPreparedVariables() {
        $variables = [];
        foreach($this->variables as $key => $value) {
            array_set($variables, '{$'.$key.'}', $value);
        }

        return $variables;
    }

    protected function parseAndReplace($html) {
        $replace = [
            '&lt;Your Name&gt;' => 'John Paul Medina',
            '##Date##' => Carbon::now()->format('l jS \\of F Y h:i:s A'),
            '##AgentName##' => 'Special Agent',
            '##Email##' => 'jp@leadtrust.io',
            '##Phone##' => '+13058049506',
            '##EffectiveDate##' => Carbon::now()->format('l jS \\of F Y h:i:s A'),
            '##FirstPaymentAmount##' => '469.95',
            '##EnrollmentFeeAmount##' => '99.95',
            '##MonthlyAmount##' => '269.95',
            '##BillDay##' => '15th',
            '##PaymentInfo##' => 'Credit Card'
        ];

        return strtr($html, $replace);
    }

    public function getVariables() {
        return $this->variables;
    }

    public function setVariables(array $array = []) {

        // Set Variable Values
        foreach($array as $key => $value) {
            if(array_key_exists($key, $this->variables)) {
                array_set($this->variables, $key, $value);
            }
        }

        return $this;
    }

    public function format() {
        $variables = $this->getPreparedVariables();
        $this->script = strtr($this->script, $variables);
        return $this;
    }

}
