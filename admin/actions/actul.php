<?php

$action = $c->getArg(2);

$actionsDir = $oa->getOseDir() . 'admin/actul/actions/';

if (file_exists($actionsDir . $action . '.php')) {
    require_once $actionsDir . $action . '.php';
} else {
    $c->printDie('L\'Action Actul "' . $action . '" n\'existe pas.');
}
