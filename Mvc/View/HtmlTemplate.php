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

        echo '<!DOCTYPE html><html><head></head><body>';
    }

    protected abstract function renderBody($data);

    protected function renderFooter() {
        echo '</body></html>';
    }

}
