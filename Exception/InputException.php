<?php
namespace PhpLibs\Exception;

use Exception;

class InputException extends Exception {
    protected $fieldName; 

    public function __construct($fieldName, $message) {
        parent::__construct($message, null, null);
        $this->fieldName = $fieldName;
    }
    
    public function getFieldName() {
        return $this->fieldName;
    }
}

