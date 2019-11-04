<?php 

namespace Nexusvc\CcgSalesApi\Validator;

use Nexusvc\CcgSalesApi\Exceptions\InvalidSchemaFormat as Exception;

class SchemaValidator {

    protected $rules;

    protected $payload;

    public function __construct(array $rules = [], array $payload = []) {
        $this->rules    = $rules;
        $this->payload  = $payload;
    }

    public function validate() {
        throw new Exception('Invalid Schema Format. Failed validation schema requirements.');
    }

}
