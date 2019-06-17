<?php

$job = $c->getArg(2);
$oa->exec('UnicaenImport SyncJob ' . $job);