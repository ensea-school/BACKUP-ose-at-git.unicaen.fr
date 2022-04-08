<?php

require_once dirname(__DIR__) . '/migration/v18Plafonds.php';

$mm  = new MigrationManager($oa, new \BddAdmin\Ddl\Ddl(), []);
$v18 = new v18Plafonds($mm);

$v18->before();