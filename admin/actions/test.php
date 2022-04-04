<?php

require_once dirname(__DIR__) . '/migration/v18.php';

$mm  = new MigrationManager($oa, new \BddAdmin\Ddl\Ddl(), []);
$v18 = new v18($mm);

$v18->before();