<?php

namespace PhpLibs\Mvc\Controller;

class MultiActionController extends AbstractController {

    protected $actionViews;

    public function __construct() {
        $this->actionViews = array();
    }

    public function registerView(\PhpLibs\Mvc\View\IView $view) {
        throw new \Exception('Bitte statt registerView registerActionView verwenden');
    }

    public function registerActionView($action, \PhpLibs\Mvc\View\IView $view) {
        $this->actionViews[$action] = $view;
    }

    public function runController() {
        if (isset($_GET['action']) && isset($this->actionViews[$_GET['action']])) {
            $view = $this->actionViews[$_GET['action']];
            header('Content-Type: ' . $view->getHttpContentType());
            $view->update($this->run());
        }
    }

    //put your code here
    protected function run() {
        $cls = new \ReflectionClass(get_class($this));
        $methodName = $_GET['action'] . 'Action';
      
        if ($cls->hasMethod($methodName)) {
            $method = $cls->getMethod($methodName);
            if ($method->isPublic()) {
                return $method->invoke($this);
            }
        }
        return null;
    }

}
