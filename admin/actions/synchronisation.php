<?php

$job = $c->getArg(2);
$oa->exec('UnicaenImport SyncJob ' . $job);
$c->println("Opération terminée");