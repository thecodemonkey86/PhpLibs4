<?php
namespace PhpLibs\Mvc\View;

interface IView {

    public function update($data); 
    
    public function getHttpContentType();
}
