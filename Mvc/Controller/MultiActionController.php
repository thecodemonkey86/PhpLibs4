<?php

namespace PhpLibs\Mvc\Controller;

class MultiActionController extends AbstractController {

    protected $actionViews;
    protected $controllerActionsMapping;
    protected $defaultAction;
    
    public function __construct() {
        $this->actionViews = array();
    }

    public function registerView(\PhpLibs\Mvc\View\IView $view) {
        throw new \Exception('Bitte statt registerView registerActionView verwenden');
    }

    public function registerActionView($urlAction, \PhpLibs\Mvc\View\IView $view) {
        $this->actionViews[$urlAction] = $view;
        $this->controllerActionsMapping[$urlAction] = $urlAction;
    }
    
    public function registerActionViewExtended($urlAction, $controllerAction, \PhpLibs\Mvc\View\IView $view) {
        $this->actionViews[$urlAction] = $view;
        $this->controllerActionsMapping[$urlAction] = $controllerAction;
    }

    public function redirectAction($action) {
        header('Location: ' . \PhpLibs\Web\Url::current()->addParam('action', $action)->__toString());
        die;
    }
    

    protected function run() {
        $action =  isset($_GET['action']) ? $_GET['action'] : $this->defaultAction;
        $controllerAction = $this->controllerActionsMapping[$action];
        $cls = new \ReflectionClass(get_class($this));
        $methodName = $controllerAction . 'Action';
        
        if ($cls->hasMethod($methodName)) {
            $method = $cls->getMethod($methodName);
            if ($method->isPublic()) {
                if (isset($this->actionViews[$action])) {
                    $view = $this->actionViews[$action];
                    header('Content-Type: ' . $view->getHttpContentType());
                    return $view->update($method->invoke($this));
                } else {
                    $method->invoke($this);
                }
            }
        }
        return null;
    }
    
    public function getDefaultAction() {
        return $this->defaultAction;
    }

    public function setDefaultAction($defaultAction) {
        $this->defaultAction = $defaultAction;
    }



}
