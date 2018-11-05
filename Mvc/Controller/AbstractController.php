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
        if($this->view !== null)
            header('Content-Type: ' . $this->view->getHttpContentType());
        $viewData = $this->run();
        if($viewData !== null)
            $this->view->update($viewData);
    }
        
    public static function redirect($location) {
        header('Location: ' . $location);
        die;
    }
    
    /**
     * @return \PhpLibs\Mvc\ViewData data
     */
    protected abstract function run();
}
