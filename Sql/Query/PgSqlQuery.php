<?php

namespace PhpLibs\Sql\Query;

class PgSqlQuery extends SqlQuery {

    public function __construct($sqlCon) {
        parent::__construct($sqlCon);
    }

    public function getColumnEscapeChar() {
        return '"';
    }

    public function getSqlString() {
        $sql = null;
        switch ($this->mode) {
            case self::MODE_INSERT:
                $sql = sprintf('insert into %s (%s) values (%s)', $this->table, implode(',', $this->insertUpdateColumns));
                break;
            case self::MODE_SELECT:
                $sql = sprintf('select %s from %s', $this->selectFields, $this->table);
                if (count($this->conditions) > 0) {
                    if (count($this->conditions) == 1) {
                        $sql .= sprintf(' where %s', $this->conditions[0]);
                    } else {
                        $sql .= sprintf(' where %s', implode(' AND ', $this->conditions));
                    }
                }
                break;
        }
        return $sql;
    }

    private function getParamTypes(array &$params) {
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
    }

    public function query() {
        $params = $this->getParams();
        if (count($params) > 0) {
            $stmt = $this->sqlCon->prepare($this->getSqlString());
            if (!$stmt) {
                $this->throwException();
            }

            $type = '';
            if ($this->paramsJoin != null) {
                $type .= $this->getParamTypes($this->paramsJoin);
            }
            if ($this->paramsSetValue != null) {
                $type .= $this->getParamTypes($this->paramsSetValue);
            }
            if ($this->paramsWhere != null) {
                $type .= $this->getParamTypes($this->paramsWhere);
            }
            $stmt->bind_param($type, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            $query = $this->sqlCon->query($this->getSqlString());
            return $query;
        }
    }

    public function fetchAssoc() {
        $res = $this->query();
        if (!$res) {
            $this->throwException();
        }
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function supportsMultiRowInsert() {
        
    }

    public function error() {
        return $this->sqlCon->error;
    }

    public function execute() {
        throw new \Exception('not yet implemented');
    }

}
