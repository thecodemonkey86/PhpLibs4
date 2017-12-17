<?php

namespace PhpLibs\Sql\Query;


class SqlParam {

    private $type, $value;

    function __construct($type, $value) {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType() {
        return $this->type;
    }

    public function getValue() {
        return $this->value;
    }

    public static function getString(string $string) {
        return new SqlParam('s', $string);
    }

    public static function getInt(int $int) {
        return new SqlParam('i', $int);
    }

    public static function getFloat(float $float) {
        return new SqlParam('f', $float);
    }

    public static function getDateTime(DateTime $dt) {
        return new SqlParam('s', $dt->format('Y-m-d H:i:s'));
    }

    public static function getDate(DateTime $dt) {
        return new SqlParam('s', $dt->format('Y-m-d'));
    }
    
    public static function getNullString() {
        return new SqlParam('s', null);
    }

    public static function getNullInt() {
        return new SqlParam('i', null);
    }

    public static function getNullFloat() {
        return new SqlParam('f', null);
    }

    public static function getNullDateTime() {
        return new SqlParam('s', null);
    }

    public static function getNullDate() {
        return new SqlParam('s', null);
    }

    public function __toString() {
        return $this->value;
    }

}
