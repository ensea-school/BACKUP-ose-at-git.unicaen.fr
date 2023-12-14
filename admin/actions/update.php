<?php

// Alerte pour l'activation du mode maintenance
$c->println("Mise à jour de OSE");
if ($c->getOption('maintenance') != 'no') {
    $c->println("Assurez-vous bien d'avoir mis OSE en mode maintenance avant de démarrer\n(pressez Entrée pour continuer)...");
    $c->getInput();
}

// Mise à jour du code source
$oa->run('update-code', true);

// Mise à jour de la base de données à partir d'un nouveau processus
$oa->run('update-bdd', true);

//Conclusion
$c->println("\nFin de la mise à jour.");
if ($c->getOption('maintenance') != 'no') {
    $c->println("N'oubliez pas de sortir du mode maintenance!");
}
$c->println('');