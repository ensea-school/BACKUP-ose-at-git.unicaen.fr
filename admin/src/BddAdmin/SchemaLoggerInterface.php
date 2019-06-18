<?php

namespace BddAdmin;

interface SchemaLoggerInterface {

    function log(string $message );

    function logTitle(string $title);

}