<?php

namespace PhpLibs\Sql\Query;

use Exception;
use Sql\Query\Condition;
use PhpLibs\Util\StringUtil;

abstract class SqlQuery {

    const MODE_SELECT = 1;
    const MODE_INSERT = 2;
    const MODE_UPDATE = 3;
    const MODE_DELETE = 4;
    const SUBMODE_NONE = 1;
    const SUBMODE_MULTIROW = 2;
    const ON_CONFLICT_UPDATE = 1;
    const ON_CONFLICT_DO_NOTHING = 2;
    const ORDER_BY_ASC = 'asc';
    const ORDER_BY_DESC = 'desc';
    
    protected $selectFields;
    protected $table;
    protected $orderBy;
    protected $joinTables;
    protected $conditions;
    protected $group;
    protected $limitResults, $resultOffset;
    protected $mode;
    protected $submode;
    protected $paramsJoin;
    protected $paramsSetValue;
    protected $paramsWhere;
    protected $sqlCon;
    protected $insertUpdateColumns;
    protected $onConflictColumns;
    protected $onConflictUpdateColumns;
    protected $insertOrUpdateRowCount;
    protected $onConflict;
    protected $insertRawValues;
    protected $insertUpdateRawValuesColumns;

    public function __construct($sqlCon) {
        if($sqlCon === null) {
            throw new \InvalidArgumentException("sql connection must not be null");
        }
        $this->sqlCon = $sqlCon;
        $this->limitResults = -1;
        $this->resultOffset = -1;
        $this->joinTables = array();
        $this->conditions = array();
        $this->submode = self::SUBMODE_NONE;
        $this->onConflict = null;
    }

    public function insertInto(string $table) {
        $this->table = $table;
        $this->insertUpdateColumns = array();
        $this->mode = self::MODE_INSERT;
        return $this;
    }

    public function update(string $table) {
        $this->table = $table;
        $this->insertUpdateColumns = array();
        $this->paramsWhere = array();
        $this->mode = self::MODE_UPDATE;
        return $this;
    }

    public function insertMultiRow(string $table, array $columns) {
        $this->table = $table;
        $this->insertUpdateColumns = $columns;
        $this->mode = self::MODE_INSERT;
        $this->submode = self::SUBMODE_MULTIROW;
        return $this;
    }

    protected function addParamsSetValue($params) {
        if ($params !== null) {
            if ($this->paramsSetValue === null) {
                $this->paramsSetValue = array();
            }
            if (is_array($params)) {
                $this->paramsSetValue = array_merge($this->paramsSetValue, $params);
            } else {
                $this->paramsSetValue[] = $params;
            }
        }
    }

    protected function addParamsJoin($params) {
        if ($params !== null) {
            if ($this->paramsJoin === null) {
                $this->paramsJoin = array();
            }
            if (is_array($params)) {
                $this->paramsJoin = array_merge($this->paramsJoin, $params);
            } else {
                $this->paramsJoin[] = $params;
            }
        }
    }

    protected function addParamsWhere($params) {
        if ($params !== null) {
            if (is_array($params)) {
                $this->paramsWhere = array_merge($this->paramsWhere, $params);
            } else {
                $this->paramsWhere[] = $params;
            }
        }
    }

    public function setValues(array $insertUpdateColumnsValues) {
        foreach ($insertUpdateColumnsValues as $column => $value) {
            $this->insertUpdateColumns[] = ($column);
            $this->addParamsSetValue($value);
        }
        return $this;
    }

    public function setValue(string $column, $value) {
        $this->insertUpdateColumns[] = $column;
        $this->addParamsSetValue($value);
        return $this;
    }

    public function addInsertRawExpression($column, $expression) {
        
         if($this->insertRawValues === null) {
             $this->insertUpdateRawValuesColumns = array($column);
             $this->insertRawValues = array($expression);
         } else {
             $this->insertUpdateRawValuesColumns[] = $column;
             $this->insertRawValues[] = $expression;
         }
      
    }
    
    public function addInsertColumn(string $column) {
        $this->insertUpdateColumns[] = $column;
        return $this;
    }

    public function deleteFrom(string $table) {
        $this->table = $table;
        $this->mode = self::MODE_DELETE;
        $this->paramsWhere = array();
        return $this;
    }

    public function from(string $table, $alias = null) {
        if($alias !== null) {
            $this->table = sprintf('%s %s', $table, $alias);
        } else {
            $this->table = $table;
        }
        return $this;
    }

    public function select(string $selectFields = '*') {
        $this->selectFields = $selectFields;
        $this->paramsWhere = array();
        $this->mode = self::MODE_SELECT;
        return $this;
    }

    public function join(string $joinTableAlias, string $on, $params = null) {
        $this->joinTables[] = sprintf(' JOIN %s ON %s', $joinTableAlias, $on);
        $this->addParamsJoin($params);
        return $this;
    }

    public function leftJoin(string $joinTableAlias, string $on, $params = null) {
        $this->joinTables[] = sprintf(' LEFT JOIN %s ON %s', $joinTableAlias, $on);
        $this->addParamsJoin($params);
        return $this;
    }

    public function where($whereCond, $params = null) {
        $this->conditions[] = ($whereCond);
        $this->addParamsWhere($params);
        return $this;
    }

    public function onConflictDoNothing(array $columns) {
        $this->onConflictColumns = $columns;
        $this->onConflict = self::ON_CONFLICT_DO_NOTHING;
        return $this;
    }

    public function onConflictUpdate(array $conflictColumns, array $updateColumns, $params = null) {
        $this->onConflictColumns = $conflictColumns;
        $this->onConflictUpdateColumns = $updateColumns;
        $this->onConflict = self::ON_CONFLICT_UPDATE;
        $this->addParamsSetValue($params);
        return $this;
    }

    public function onConflict(string $conflictColumn) {
        if ($this->onConflictColumns === null) {
            $this->onConflictColumns = array();
        }
        $this->onConflictColumns[] = $conflictColumn;
        return $this;
    }

    public function onConflictSetValue(string $updateColumn, $params = null) {
        if ($this->onConflictUpdateColumns === null) {
            $this->onConflict = self::ON_CONFLICT_UPDATE;
            if ($this->submode == self::SUBMODE_MULTIROW) {
                throw new Exception('cannot perform upsert operation on multi-row insert');
            }
            $this->onConflictUpdateColumns = array();
        }
        $this->onConflictUpdateColumns[] = $updateColumn;
        $this->addParamsSetValue($params);
        return $this;
    }

    public function andWhere(Condition $cond1, Condition $cond2, $params = null) {
        $this->conditions[] = new AndCondition($cond1, $cond2);
        $this->addParamsWhere($params);
        return $this;
    }

    public function orWhere(Condition $cond1, Condition $cond2, $params = null) {
        $this->conditions[] = new OrCondition($cond1, $cond2);
        $this->addParamsWhere($params);
        return $this;
    }

    public function orderBy(string $order, string $direction = self::ORDER_BY_ASC) {
        if($this->orderBy === null) {
            $this->orderBy = array();
        }
        
        $this->orderBy[] = sprintf('%s %s ', $order, $direction);
        return $this;
    }
    
    public function orderByPrepend(string $order, string $direction = self::ORDER_BY_ASC) {
        if($this->orderBy === null) {
            $this->orderBy = array();
        }
        array_unshift($this->orderBy, sprintf('%s %s ', $order, $direction));
        return $this;
    }
    
    public function orderByMultiple(array $orderBy) {
        if($this->orderBy === null) {
            $this->orderBy = array();
        }
        
        foreach ($orderBy as $order => $direction) {
             $this->orderBy[] = sprintf('%s %s ', $order, $direction);
        }
        
        return $this;
    }

    public function __toString() {
        return $this->getSqlString();
    }

    public function limit($limit) {
        $this->limitResults = $limit;
        $this->resultOffset = -1;
        return $this;
    }

    public function limitAndOffset($limit, $offset) {
        $this->limitResults = $limit;
        $this->resultOffset = $offset;
        return $this;
    }

    public function offset($offset) {
        $this->limitResults = -1;
        $this->resultOffset = $offset;
        return $this;
    }

    public abstract function query();

    /**
     * @return bool success
     */
    public abstract function execute() : bool ;

    public function getDebugString() {
        $sql = $this->__toString();
       
        if ($this->paramsJoin != null) {
            foreach ($this->paramsJoin as $p) {
                $sql = StringUtil::replaceFirst('?', $p, $sql);
            }
        }
        if ($this->paramsSetValue != null) {
            foreach ($this->paramsSetValue as $p) {
                $sql = StringUtil::replaceFirst('?', $p, $sql);
            }
        }
        if ($this->paramsWhere != null) {
            foreach ($this->paramsWhere as $p) {
                $sql = StringUtil::replaceFirst('?', $p, $sql);
            }
        }
        return $sql;
    }

    public abstract function getSqlString();

    // TODO
    public abstract function getColumnEscapeChar();

    public function getEscapedColumName($col) {
        return sprintf('%s%s%s', $this->getColumnEscapeChar(), $col,$this->getColumnEscapeChar() );
    }
    
    public function beginTransaction() {
        
    }

    public function commitTransaction() {
        
    }

    public function rollbackTransaction() {
        
    }

    public function addInsertRow(array $values) {
        $this->insertOrUpdateRowCount++;
        $this->addParamsSetValue($values);
    }

    public abstract function supportsMultiRowInsert();

    public abstract function error();

    protected function throwException() {
        throw new Exception("DB Error: " . $this->error() .': '. $this->getDebugString());
    }

    protected function getParams() {
        $params = array();
        if ($this->paramsJoin !== null && count($this->paramsJoin) > 0) {
            foreach ($this->paramsJoin as $p) {
                $params[] = ( ($p instanceof SqlParam) ? $p->getValue() :  $p);
            }
        }
        if ($this->paramsSetValue !== null && count($this->paramsSetValue) > 0) {
            foreach ($this->paramsSetValue as $p) {
                $params[] = ( ($p instanceof SqlParam) ? $p->getValue() :  $p);
            }
        }
        if ($this->paramsWhere !== null && count($this->paramsWhere) > 0) {
            foreach ($this->paramsWhere as $p) {
                $params[] = ( ($p instanceof SqlParam) ? $p->getValue() :  $p);
            }
        }
        return $params;
    }
    
    

}
