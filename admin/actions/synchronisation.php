<?php

use UnicaenImport\Processus\ImportProcessus;

if ($oa->inMaintenance()) {
    $c->println("OSE est en maintenance. La synchronisation est coupée pendant ce temps");
} elseif ($oa->config()->get('maintenance', 'desactivationSynchronisation', false)) {
    $c->println("La synchronisation est désactivée");
} else {
    $job = $c->getArg(2);
    $args = 'UnicaenImport SyncJob ' . $job;

    $processusImport = $oa->container()->get(ImportProcessus::class);
    $processusImport->syncJob($job);

    $c->println("Opération terminée");
}