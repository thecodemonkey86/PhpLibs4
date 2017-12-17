<?php

namespace PhpLibs\Sql\Query\Condition;

class OrCondtion extends Condition {

    protected $cond1;
    protected $cond2;

    public function __construct($cond1, $cond2) {
        parent::construct();
        $this->cond1 = $cond1;
        $this->cond2 = $cond2;
    }

    public function __toString() {
        return sprintf('%s OR %s', $this->cond1, $this->cond2);
    }

}
