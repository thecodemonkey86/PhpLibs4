<?php

namespace PhpLibs\Sql\Query\Condition;

class MultiAndCondition extends Condition {

    private $conditions;

    public function __construct(array $conditions) {
        $this->conditions = $conditions;
    }
    
    public function __toString() {
        return implode(' AND ', $this->conditions);
    }

}
