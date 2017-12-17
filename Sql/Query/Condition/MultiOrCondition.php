<?php

namespace PhpLibs\Sql\Query;

class MultiOrCondition extends Condition {

    private $conditions;

    public function __construct(array $conditions) {
        $this->conditions = $conditions;
    }
    
    public function __toString() {
        return implode(' OR ', $this->conditions);
    }

}
