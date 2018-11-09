<?php
namespace PhpLibs\Mvc\Controller\Form\Validator;

use Aktivweb\AwPhpLibs\Core\Exception\InputException;
use DateTime;
use PhpLibs\Exception\ObligatoryException;
use PhpLibs\Util\DateUtil;

class DateValidator extends Validator {
    private $messageInvalid;


    public function __construct($fieldname, $obligatory = false) {
        parent::__construct($fieldname, $obligatory);
    }
    
    /**
     * 
     * @param type $value
     * @return DateTime Date object or null if not obligatory
     * @throws ObligatoryException if empty and obligatory
     * @throws InputException if not supported Format dd.mm.YYYY, dd.mm.YY, YYYY-mm-dd or YY-mm-dd
     */
    public function getValidatedValue($value) {
        if ($this->checkEmpty($value)) {
            return null;
        }
        try{
            return DateUtil::parseDate($value);
        } catch(\Exception $e) {
            throw new InputException($this->getFieldname(), $this->messageInvalid);
        }
    }
    
    public function getMessageInvalid() {
        return $this->messageInvalid;
    }

    public function setMessageInvalid($messageInvalid) {
        $this->messageInvalid = $messageInvalid;
    }



}