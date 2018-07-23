<?php

namespace PhpLibs\Sql\Query;

class FirebirdSqlQuery extends SqlQuery {

    public function __construct($sqlCon) {
        parent::__construct($sqlCon);
    }

    // TODO
    public function getColumnEscapeChar() {
        return '"';
    }

    public function getSqlString() {
        $sql = null;
        switch ($this->mode) {
            case self::MODE_INSERT:
                switch ($this->submode) {
                    case self::SUBMODE_NONE:
                        $sql = sprintf('insert into %s (%s) values %s', $this->table, implode(',', ($this->insertRawValues? array_merge($this->insertUpdateColumns,$this->insertUpdateRawValuesColumns) : $this->insertUpdateColumns)), ($this->insertRawValues?\PhpLibs\Sql\Util\SqlUtil::getPlaceholdersAndRawValues(count($this->paramsSetValue), $this->insertRawValues) :\PhpLibs\Sql\Util\SqlUtil::getPlaceholders(count($this->insertUpdateColumns))));
                           var_dump($sql);
                        break;
                    case self::SUBMODE_MULTIROW:
                        $sql = sprintf('insert into %s (%s) values %s', $this->table, implode(',', $this->insertUpdateColumns), substr(str_repeat(',' . \PhpLibs\Sql\Util\SqlUtil::getPlaceholders(count($this->insertUpdateColumns)), $this->insertOrUpdateRowCount), 1));
                        break;
                    default:
                        throw new \InvalidArgumentException();
                }

                break;
            case self::MODE_UPDATE:
                $sqlUpdate = '';
                foreach ($this->insertUpdateColumns as $col) {
                    $sqlUpdate .= sprintf(',%s = ?', $col);
                }
                $sql = sprintf('update %s set %s', $this->table, substr($sqlUpdate, 1));
                if (count($this->conditions) > 0) {
                    if (count($this->conditions) == 1) {
                        $sql .= sprintf(' where %s', $this->conditions[0]);
                    } else {
                        $sql .= sprintf(' where %s', implode(' AND ', $this->conditions));
                    }
                }
                break;
            case self::MODE_SELECT:
                $sql = sprintf('select %s from %s', $this->selectFields, $this->table);

                foreach ($this->joinTables as $join) {
                    $sql .= $join;
                }

                if (count($this->conditions) > 0) {
                    if (count($this->conditions) == 1) {
                        $sql .= sprintf(' where %s', $this->conditions[0]);
                    } else {
                        $sql .= sprintf(' where %s', implode(' AND ', $this->conditions));
                    }
                }

                if ($this->orderBy !== null) {
                    if (count($this->orderBy) == 1) {
                        $sql .= ' order by ' . $this->orderBy[0];
                    } else {
                        $sql .= ' order by ' . implode(',', $this->orderBy);
                    }
                }

                break;
            case self::MODE_DELETE:
                $sql = sprintf('delete from %s', $this->table);
                if (count($this->conditions) > 0) {
                    if (count($this->conditions) == 1) {
                        $sql .= sprintf(' where %s', $this->conditions[0]);
                    } else {
                        $sql .= sprintf(' where %s', implode(' AND ', $this->conditions));
                    }
                }
                break;
            default:
                throw new \InvalidArgumentException();
        }
        return $sql;
    }

    /*private function getParamTypes(array &$params) {
        $type = '';
        if ($params !== null) {
            foreach ($params as $param) {
                if (is_string($param)) {
                    if (filter_var($param, FILTER_VALIDATE_INT)) {
                        $type .= 'i';
                    } else if (is_numeric($param)) {
                        $type .= 'd';
                    } else {
                        $type .= 's';
                    }
                } else if (is_int($param)) {
                    $type .= 'i';
                } else if (is_float($param)) {
                    $type .= 'd';
                } else {
                    $type .= $param->getType();
                }
            }
        }
        return $type;
    }*/

    public function query() {
        $params = $this->getParams();
        if (count($params) > 0) {			
            $stmt = ibase_prepare($this->sqlCon, $this->getSqlString());
			array_unshift($params, $stmt);
            return call_user_func_array('ibase_execute', $params);
        } else {
             return ibase_query($this->sqlCon, $this->getSqlString());
        }
    }

    public function fetchAssoc() {
        $res = ibase_query($this->sqlCon, $this->getSqlString());
        if (!$res) {
            $this->throwException();
        }
        return ibase_fetch_assoc($res);
    }

    public function supportsMultiRowInsert() {
        
    }

    public function error() {
        return $this->sqlCon->error;
    }

    public function execute(): bool {
        $params = $this->getParams();
        if (count($params) > 0) {			
            $stmt = ibase_prepare($this->sqlCon, $this->getSqlString());
			array_unshift($params, $stmt);
            return call_user_func_array('ibase_execute', $params);
        } else {
             return ibase_query($this->sqlCon, $this->getSqlString());
        }
    }

}
