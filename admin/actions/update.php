<?php

// Alerte pour l'activation du mode maintenance
$c->println("Mise à jour de OSE");
if ($c->getOption('maintenance') != 'no') {
    $c->println("Assurez-vous bien d'avoir mis OSE en mode maintenance avant de démarrer\n(pressez Entrée pour continuer)...");
    $c->getInput();
}


$bin = getcwd()."/bin/ose";
passthru("$bin update-code");
passthru("$bin update-bdd");

//Conclusion
$c->println("\nFin de la mise à jour.");
if ($c->getOption('maintenance') != 'no') {
    $c->println("N'oubliez pas de sortir du mode maintenance!");
}
$c->println('');