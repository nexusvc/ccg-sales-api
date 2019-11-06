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

    public function fetch() {
        
        $schema = new \Nexusvc\CcgSalesApi\Schema\Schema(parent::$ccg->order);

        // VoiceScript Schema
        foreach($schema->load('voice-script')->format() as $key => $value) {
            array_set($this->attributes, $key, $value);
        }

        return parent::fetch();
    }

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

        // @todo: Temporary include recurring, deposit, and total
        array_set($formattedVariables, 'monthlyAmount',  number_format((float)parent::$ccg->order->recurring, 2, '.', ''));
        array_set($formattedVariables, 'enrollmentFeeAmount',  number_format((float)parent::$ccg->order->deposit, 2, '.', ''));
        array_set($formattedVariables, 'firstPaymentAmount',  number_format((float)parent::$ccg->order->total, 2, '.', ''));

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

    protected function isLocal($path) {
        return strpos($path, 'http') !== false;
    }

    public function getVariables() {
        return $this->variables;
    }

    public function setVariables(array $array = []) {
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

    public function addRecording($path) {
        $this->recording = $path;
        return $this;
    }

}
