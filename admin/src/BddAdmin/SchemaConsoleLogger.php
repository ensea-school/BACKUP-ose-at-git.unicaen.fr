<?php

namespace BddAdmin;

class SchemaConsoleLogger implements SchemaLoggerInterface {

    /**
     * @var \Console
     */
    public $console;

    function log(Ddl\DdlAbstract $object, string $action, string $name)
    {
        if ($this->console){
            $this->console->println($action.' '.$object::ALIAS.' '.$name);
        }
    }

}