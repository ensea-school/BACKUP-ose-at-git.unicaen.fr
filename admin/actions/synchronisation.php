<?php

if ($oa->inMaintenance()) {
    $c->println("OSE est en maintenance. La synchronisation est coupée pendant ce temps");
} elseif ($oa->config()->get('maintenance', 'desactivationSynchronisation', false)) {
    $c->println("La synchronisation est désactivée");
} else {
    $job = $c->getArg(2);
    $args = 'UnicaenImport SyncJob ' . $job;
    $c->passthru("php " . getcwd() . "/public/index.php " . $args);

    $c->println("Opération terminée");
}