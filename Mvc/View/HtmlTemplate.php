<?php

namespace PhpLibs\Mvc\View;

abstract class HtmlTemplate implements IView {

    protected $title;
    protected $includeJs;
    protected $includeCss;

    public function update($data) {
        ob_start();
        $this->renderHeader();
        $this->renderBody($data);
        $this->renderFooter();
        ob_end_flush();
    }

    protected function renderHeader() {

        echo '<!DOCTYPE html><html><head>';
        echo '<meta charset="utf-8">';
        if ($this->title) {
            echo '<title>';
            echo htmlentities($this->title);
            echo '</title>';
        }
        if ($this->includeCss) {
            foreach ($this->includeCss as $css) {
                echo '<link rel="stylesheet" type="text/css" href="';
                echo htmlentities($css);
                echo '">';
            }
        }
        echo '<style type="text/css">';
        $this->renderInlineCss();
        echo '</style></head><body>';
    }

    protected function renderInlineJs() {
        
    }

    protected function renderInlineCss() {
        
    }

    protected abstract function renderBody($data);

    protected function renderFooter() {
        if ($this->includeJs) {
            foreach ($this->includeJs as $js) {
                echo '<script type="text/javascript" src="';
                echo htmlentities($js);
                echo '"></script>';
            }
        }
        echo '<script type="text/javascript">';
        $this->renderInlineJs();
        echo '</script></body></html>';
    }

    public function getHttpContentType() {
        return \PhpLibs\Web\HttpHeader::CONTENT_TYPE_TEXT_HTML;
    }

    public function addJs($url) {
        if ($this->includeJs === null) {
            $this->includeJs = array($url);
        } else {
            $this->includeJs[] = $url;
        }
    }

    public function addCss($url) {
        if ($this->includeCss === null) {
            $this->includeCss = array($url);
        } else {
            $this->includeCss[] = $url;
        }
    }

}
