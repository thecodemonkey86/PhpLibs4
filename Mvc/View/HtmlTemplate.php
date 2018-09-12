<?php

namespace PhpLibs\Mvc\View;

abstract class HtmlTemplate implements IView {

    public function update($data) {
        ob_start();
        $this->renderHeader();
        $this->renderBody($data);
        $this->renderFooter();
        ob_end_flush();
    }

    protected function renderHeader() {

        echo '<!DOCTYPE html><html><head>';
        echo '<style type="text/css">';
         $this->renderInlineCss();
        echo '</style>';
        echo '<script type="text/javascript">';
        $this->renderInlineJs();
        echo '</script></head><body>';
    }

    protected function renderInlineJs() {
        
    }
    protected function renderInlineCss() {
        
    }

    protected abstract function renderBody($data);

    protected function renderFooter() {
        echo '</body></html>';
    }

}
