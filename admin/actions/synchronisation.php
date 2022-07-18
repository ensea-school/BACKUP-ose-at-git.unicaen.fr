<?php

if ($oa->inMaintenance()) {
    $c->println("OSE est en maintenance. La synchronisation est coupée pendant ce temps");
} elseif ($oa->getConfig('maintenance', 'desactivationSynchronisation', false)) {
    $c->println("La synchronisation est désactivée");
} else {
//    $job = $c->getArg(2);
//    $oa->exec('UnicaenImport SyncJob ' . $job);
    $c->println("Opération terminée");
}