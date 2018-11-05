<?php

namespace PhpLibs\Mvc\Controller\Form;

/**
 * Stellt Funktionalität zum Prüfen von Inputfeldern bereit. Arbeitet mit Exceptions, falls ungültige Werte übergeben werden
 */
abstract class HttpPostForm {

    protected $fieldValidators;
    protected $submit;

    public function __construct($submitFieldName = 'submit') {

        $this->fieldValidators = array();
        $this->submit = $submitFieldName;
    }

    public function addValidator($validator) {
        $this->fieldValidators[$validator->getFieldname()] = $validator;
    }

    public function getValue($fieldName, $default = "") {
        if (isset($this->fieldValidators[$fieldName])) {
            return $this->fieldValidators[$fieldName]->getValidatedValue((isset($_POST[$fieldName]) ? $_POST[$fieldName] : $default));
        }
        return (isset($_POST[$fieldName]) ? $_POST[$fieldName] : $default);
    }

    public function isObligatory($fieldName) {
        if (isset($this->fieldValidators[$fieldName])) {
            return $this->fieldValidators[$fieldName]->isObligatory();
        }
        return false;
    }

    /**
     * Fügt ein einfaches (String-)Pflichtfeld hinzu
     * 
     * @param string Name des Parameters
     */
    public function addObligatoryString($fieldname) {
        $this->addValidator(new ObligatoryStringValidator($fieldname));
    }

    public function addObligatoryStrings(array $fields) {
        foreach ($fields as $fieldname) {
            $this->addValidator(new ObligatoryStringValidator($fieldname));
        }
    }

    public function addObligatoryInt($fieldname) {
        $this->addValidator(new IntValidator($fieldname, true));
    }

    public function addInt($fieldname) {
        $this->addValidator(new IntValidator($fieldname));
    }

    public function addObligatoryDate($fieldname) {
        $this->addValidator(new DateValidator($fieldname, true));
    }

    public function addDateField($fieldname) {
        $this->addValidator(new DateValidator($fieldname));
    }

    public function addObligatoryDateArrayField($fieldname) {
        $this->addValidator(new DateArrayValidator($fieldname, true));
    }

    public function addDateArrayField($fieldname) {
        $this->addValidator(new DateArrayValidator($fieldname, false));
    }

    protected function afterSubmit() {
        
    }

    protected function beforeSubmit() {
        
    }

    /**
     * 
     * @return mixed false, wenn die Form nicht abgeschickt wurde (d.h. das Feld mit dem Feldnamen im Klassenattribut submit), ansonsten das Ergebnis von onSubmit
     */
    public function submit() {
        if (!$this->getValue($this->submit, false)) {
            return false;
        }
        $this->beforeSubmit();
        $result = $this->onSubmit();
        $this->afterSubmit();
        return $result;
    }

    protected abstract function onSubmit();
}
