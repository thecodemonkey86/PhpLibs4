<?php
namespace PhpLibs\Mvc\Controller\Form\Validator;

use PhpLibs\Exception\ObligatoryException;



abstract class Validator {
    
    protected $fieldname;
    protected $obligatory;
    protected $obligatoryMessage;


    public function __construct($fieldname, $obligatory = false) {
        $this->fieldname = $fieldname;
        $this->obligatory = $obligatory;
    }
    
    protected function checkEmpty($value) {
        if ($value=== null || $value=== false || is_string($value) && $value==="" ) {
            if ($this->obligatory) {
                throw new ObligatoryException($this->fieldname);
            } else {
                return true;
            }
        }
        return false;
    }
    
    abstract function getValidatedValue($value);

    
    public function getFieldname() {
        return $this->fieldname;
    }

    public function isObligatory() {
        return $this->obligatory;
    }
    
    public function getObligatoryMessage() {
        return $this->obligatoryMessage;
    }

    public function setObligatoryMessage($obligatoryMessage) {
        $this->obligatoryMessage = $obligatoryMessage;
    }

}