<?php

namespace BddAdmin;

interface SchemaLoggerInterface {

    function log( Ddl\DdlAbstract $object, string $action, string $name );

}