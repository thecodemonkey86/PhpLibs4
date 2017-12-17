<?php

namespace PhpLibs\Orm\Model;

abstract class BaseBean {

    protected $insert;
    protected $loaded;
    protected $autoIncrement;
    protected $primaryKeyModified;

    public function __construct(bool $insertNew) {
        $this->insert = $insertNew;
        $this->primaryKeyModified = false;
    }

    public function setAutoIncrementId(int $id) {
        
    }

    public function isLoaded(): bool {
        return $this->loaded;
    }

    public function setLoaded(bool $value) {
        $this->loaded = $value;
    }

    public function setInsertNew() {
        $this->insert = true;
    }

    public function isInsertNew() : bool {
        return $this->insert;
    }

    public function isAutoIncrement() : bool  {
        return $this->autoIncrement;
    }

    public function isPrimaryKeyModified() : bool  {
        return $this->primaryKeyModified;
    }

    public function setPrimaryKeyModified(bool $primaryKeyModified) {
        $this->primaryKeyModified = $primaryKeyModified;
    }

    public abstract function hasUpdate() : bool;
}
