<?php

namespace PhpLibs\Sql\Util;

class SqlUtil {

    public static function getPlaceholders(int $count): string {
        if ($count == 0) {
            throw new \InvalidArgumentException("count must be > 0");
        } else if ($count == 1) {
            return "(?)";
        } else {
            return sprintf('(%s)', substr(str_repeat(',?', $count), 1));
        }
    }

}
