<?php

namespace PhpLibs\Util;

class StringUtil {

    public static function startsWith($string, $startswith) {
        return (strlen($string) >= strlen($startswith) && substr($string, 0, strlen($startswith)) == $startswith);
    }

    public static function endsWith($string, $endswith) {
        return (strlen($string) >= strlen($endswith) && substr($string, strlen($string) - strlen($endswith)) == $endswith);
    }

    public static function replaceFirst($search, $replace, $subject) {
        $k = strpos($subject, $search);
        if ($k !== false) {
            return substr($subject, 0, $k) . $replace . substr($subject, $k + strlen($search));
        }
        return $subject;
    }

    public static function implodeAssoc($separator, $separatorKeyValue, array &$data) {
        $result = '';
        foreach ($data as $key => $value) {
            $result .= sprintf('%s%s%s%s', $separator, $key, $separatorKeyValue, $value);
        }
        return substr($result, strlen($separator));
    }
    
    public static function randomString($length, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
        srand();
        $str = str_repeat(' ', $length);
        for($i=0;$i<$length;$i++) {
            $str[$i] = $chars[rand(0, strlen($chars)- 1)];
        }
        return $str;
    }

}
