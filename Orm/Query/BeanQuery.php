<?php

namespace PhpLibs\Orm\Query;

use Exception;

abstract class BeanQuery {

    /**
     *
     * @var string 
     */
    protected $mainBeanAlias;
    
    /**
     *
     * @var ISqlQuery 
     */
    protected $sqlQuery;

    /**
     * 
     * @param ISqlQuery $sqlQuery datenbankabhängiger Querybuilder
     */
    public function __construct(\PhpLibs\Sql\Query\SqlQuery $sqlQuery) {
        $this->sqlQuery = $sqlQuery;
    }

    /**
     * 
     * @param string $mainBeanAlias
     * @param bool $joinRelations wenn true, dann werden select-Felder und SQL-Joins der Relationen (1. Ebene) automatisch eingefügt
     * @return $this
     */
    public function select(string $mainBeanAlias = 'b1', bool $joinRelations = true) {
        $this->mainBeanAlias = $mainBeanAlias;
        
        if($joinRelations) {
            $this->sqlQuery->select($this->getAllSelectFields());
            $this->sqlQuery->from($this->getTableName(), $mainBeanAlias);
            $this->addRelatedTableJoins();
        } else {
            $this->sqlQuery->from($this->getTableName(), $mainBeanAlias);
        }
        return $this;
    }

   /**
    * select Alle Selectfelder inkl. die der Tabellen der Relationen (1. Ebene)
    */
    protected abstract function getAllSelectFields() : string;

    /**
     * Joins der Tabellen der Relationen (1. Ebene) einfügen
     */
    protected abstract function addRelatedTableJoins();

    /**
     * Tabellenname der Haupttabelle
     */
    protected abstract function getTableName() : string;

    /**
     * Liefert array mit Bean-Instanzen (BaseBean-Subklasse), die die Haupttabelle repräsentieren 
     */
    public abstract function fetch();

     /**
     * Liefert eine Bean-Instanz (BaseBean-Subklasse), die die Haupttabelle repräsentiert 
     */
    public abstract function fetchOne();

    /**
     * 
     * @param string $whereCond
     * @param mixed $params
     * @return $this
     */
    public function where($whereCond, $params = null) {
        $this->sqlQuery->where($whereCond, $params);
        return $this;
    }
   
    /**
     * 
     * @param string $joinTableAlias
     * @param string $on
     * @param mixed $params
     * @return $this
     */
    public function join($joinTableAlias, $on, $params = null) {
        $this->sqlQuery->join($joinTableAlias, $on, $params);
        return $this;
    }

    /**
     * 
     * @param string $joinTableAlias
     * @param string $on
     * @param mixed $params
     * @return $this
     */
    public function leftJoin($joinTableAlias, $on, $params = null) {
        $this->sqlQuery->leftJoin($joinTableAlias, $on, $params);
        return $this;
    }

    /**
     * 
     * @param \Orm\Condition $cond1
     * @param \Orm\Condition $cond2
     * @param mixed $params
     * @return $this
     */
    public function andWhere(Condition $cond1, Condition $cond2, $params = null) {
        $this->sqlQuery->andWhere($cond1, $cond2, $params);
        return $this;
    }

    /**
     * 
     * @param \Orm\Condition $cond1
     * @param \Orm\Condition $cond2
     * @param mixed $params
     * @return $this
     */
    public function orWhere(Condition $cond1, Condition $cond2, $params = null) {
        $this->sqlQuery->andWhere($cond1, $cond2, $params);
        return $this;
    }

    /**
     * 
     * @param string $order
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $order, string $direction = \PhpLibs\Sql\Query\SqlQuery::ORDER_BY_ASC) {
        $this->sqlQuery->orderBy($order, $direction);
        return $this;
    }

    public function orderByPrepend(string $order, string $direction = \PhpLibs\Sql\Query\SqlQuery::ORDER_BY_ASC) {
        $this->sqlQuery->orderByPrepend($order, $direction);
        return $this;
    }
    
     public function orderByMultiple(array $orderBy) {
         $this->sqlQuery->orderByMultiple($orderBy);
        return $this;
     }
    
    /**
     * @param int $limit
     */
    public function limit($limit) {
        throw new Exception("todo: reimplementation required");
    }

    /**
     * 
     * @param int $limit
     * @param int $offset
     */
    public function limitAndOffset($limit, $offset) {
        throw new Exception("todo: reimplementation required");
    }

    /**
     * 
     * @param int $offset
     * @throws Exception
     */
    public function offset($offset) {
        throw new Exception("todo: reimplementation required");
    }

    public function query() {
        print_r($this->getDebugString());
        return $this->sqlQuery->query();
    }
   
    public function getDebugString() {
        return $this->sqlQuery->getDebugString();
    }

    /**
     * 
     * @return string
     */
    public function getSqlString() : string {
        return $this->sqlQuery->getSqlString();
    }

    public function getColumnEscapeChar() {
        return $this->sqlQuery->getColumnEscapeChar();
    }

    public function beginTransaction() {
        return $this->sqlQuery->beginTransaction();
    }

    public function commitTransaction() {
        return $this->sqlQuery->commitTransaction();
    }

    public function rollbackTransaction() {
        return $this->sqlQuery->rollbackTransaction();
    }

    public function __toString() {
        return $this->sqlQuery->toString();
    }

}
