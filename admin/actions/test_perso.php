<?php


$bdd = $oa->getBdd();

$console = $oa->getConsole();
$ref     = new \BddAdmin\Ddl\Ddl();
$ref->loadFromDir($oa->getOseDir() . 'data/ddl');
$filters = require $oa->getOseDir() . 'data/ddl_config.php';
$mm      = new MigrationManager($oa, $ref, $filters);

$mm->migration('post', 'MigrationPrivilegesServiceEditionMasse');
