<?php

$bdd = $oa->getBdd();

$c->println("Chargement d'une base de données", $c::COLOR_CYAN);
if ($c->getOption('doload') !== 'oui') {
    $c->println("Ce script va charger votre base à l'aide des données fournies. Ceci écrasera vos données actuelles. Voulez-vous poursuivre l'opération ?\n(tapez \"oui\" pour continuer)...");
    $doLoad = $c->getInput('doload');

    if ($doLoad != 'oui') $c->printDie("Interruption de l'opération de chargement de la base de données");
}


if (!$c->hasOption('dir')) {
    $c->print("Veuillez entrer le répertoire où se trouvent les données à charger: ");
}
$dir = $c->getInput('dir');


$bdd->load($dir);

$bdd->majSequences();