<?php

namespace PhpLibs\Mvc\Controller;

abstract class AbstractController {
    private $view;
    protected $sqlCon;
        
    public function registerView(\PhpLibs\Mvc\View\IView $view) {
        $this->view = $view; 
    }
    
    public function setSqlCon($sqlCon) {
        $this->sqlCon = $sqlCon;
    }

    public function runController() {
        $this->view->update($this->run());
    }
        
    /**
     * @return \PhpLibs\Mvc\ViewData\ViewData data
     */
    protected abstract function run();
}
