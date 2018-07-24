<?php

namespace PhpLibs\Sql\Util;
use PhpLibs\Util\StringUtil;

class SqlUtil {

    public static function getPlaceholders(int $count): string {
        if ($count == 0) {
            throw new \InvalidArgumentException("count must be > 0");
        } else if ($count == 1) {
            return '(?)';
        } else {
            return sprintf('(%s)', substr(str_repeat(',?', $count), 1));
        }
    }

    public static function getPlaceholdersAndRawValues(int $count, array $rawValues): string {
        if ($count == 0) {
            throw new \InvalidArgumentException("count must be > 0");
        } else if ($count == 1) {
            return sprintf('(?,%s)',implode(',', $rawValues));
        } else {
            return sprintf('(%s)', str_repeat('?,', $count).implode(',', $rawValues));
        }
    }
    
     public static function debugSql($sql, $params) {
        $s = $sql;
        foreach ($params as $p) {
            $s = StringUtil::replaceFirst('?', is_numeric($p) ? $p : '"'.$p.'"', $s);
        }
        var_dump($s); 
    }
    
}
