<?php
namespace PhpLibs\Web;


class Url {

    protected $params;
    protected $url;
    protected $anchor;
    
    public function __construct($baseurl, array $params = array()) {
        $this->params = $params;
        $this->url = $baseurl;
        $this->anchor = null;
    }

    public function __toString() {
        $s = $this->url . "?";
        foreach ($this->params as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $s .= $name . "=" . urlencode($v) . "&";
                }
            } else {
                $s .= $name . "=" . urlencode($value) . "&";
            }
        }
        $s = substr($s, 0, strlen($s) - 1);
        
        if ($this->anchor !== null) {
            $s .= "#" . $this->anchor;
        }
        
        return $s;
    }

    /**
     * addParam: arbeitet auf $this, copyAddParam macht eine Kopie von $this und ändert damit $this nicht
     * 
     * @param type $name
     * @param type $value
     * @return \Url
     */
    public function addParam($name, $value) {
        $this->addParamIntern($name, $value);
        return $this;
        
    }
    
    private function addParamIntern($name, $value) {
        if (\PhpLibs\Util\StringUtil::endsWith($name, "[]")) {
            if (isset($this->params[$name])) {
                $this->params[$name][] = $value;
            } else {
                 $this->params[$name] = array($value);
            }
        } else {
            $this->params[$name] = $value;
        }
    }
    
    public function addParams(array &$newParams) {
        foreach ($newParams as $name => $value) {
            $this->addParamIntern($name, $value);
        }
        return $this;
    }
    
    
    public function copyAddParam($name, $value) {
        $url = new Url(null);
        $url->url = $this->url;
        $url->params = $this->params;
        $url->addParam($name, $value);
        return $url;
    }
    
    
    public function copyAddParams(array &$newParams) {
        $url = new Url(null);
        $url->url = $this->url;
        $url->params = $this->params;
        foreach ($newParams as $name => $value) {
            $url->addParam($name, $value);
        }
        return $url;
    }
    
    
    public function removeParam($name) {
        unset($this->params[$name]);
        return $this;
    }
    
    public static function externalUrl($url) {
        $u = strpos($url, "://")>0 ? $url : "http://" . $url;
        return self::parse($u);
    }
         
    public function setAnchor($a) {
        $this->anchor = $a;
        return $this;
    }
    
    public static function parse($url, array $addParams = array()) {
        $urlparts = explode("?", $url);
        if (count($urlparts) == 0 || count($urlparts) > 2) {
            throw new Exception("Ung&uuml;ltige URL: ".  print_r(count($urlparts),true));
        }
        $params = array();
        $anchor = null;
        if (count($urlparts) == 2) {
            $paramUrlPart = explode("#", $urlparts[1]);
            if (count($paramUrlPart) == 2) {
                $anchor = $paramUrlPart[1];
            }
            
            $strparams = explode("&", $paramUrlPart[0]);
            for ($i = 0; $i < count($strparams); $i++) {
                $params[] = explode("=", $strparams[$i]);
            }
        }
        $resulturl = new Url($urlparts[0]);
        foreach ($params as $p) {
            $resulturl->addParam($p[0], $p[1]);
        }
        
        $resulturl->setAnchor($anchor);
        foreach ($addParams as $name => $value) {
            $resulturl->addParam($name, $value);
        }
        return $resulturl;
    }
    
    public static function getAnchorLink($anchor) {
        $url = self::current();
        $url->setAnchor($anchor);
        return $url;
    }
    
    public static function current() {
        return self::parse(urldecode($_SERVER['REQUEST_URI']));
    }
    
    public function toAbsoluteUrl() {
        $url = isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == "on" ? "https://" : "http://";
        $url .= $_SERVER['HTTP_HOST'] . $this->url;
        
        $u = new Url($url);
        $u->params = $this->params;
        return $u;
    }
}

