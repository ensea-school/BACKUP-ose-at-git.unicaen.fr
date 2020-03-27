<?php

namespace BddAdmin\BddCheck;

use BddAdmin\BddCheck;

abstract class BddCheckRule
{

    public $tableName;

    public $columnName;

    /**
     * @var BddCheck
     */
    private $collector;



    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(BddCheck $collector)
    {
        $this->collector = $collector;
    }



    /**
     * @return string
     */
    abstract public function sql();



    abstract public function check();



    protected function error($error, $sqlRes = null)
    {
        $rule = str_replace('BddAdmin\\BddCheck\\', '', get_class($this));
        $this->collector->addError($this->tableName, $this->columnName, compact('rule', 'error', 'sqlRes'));
    }
}