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
        header('Content-Type: ' . $this->view->getHttpContentType());
        $this->view->update($this->run());
    }
        
    public function redirect($location) {
        header('Location: ' . $location);
        die;
    }
    
    /**
     * @return \PhpLibs\Mvc\ViewData\ViewData data
     */
    protected abstract function run();
}
