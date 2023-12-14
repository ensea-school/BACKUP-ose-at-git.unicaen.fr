<?php

$c->println("Veuillez saisir un login :");
$login = $c->getInput();

$c->println("Veuillez maintenant saisir un mot de passe :");
$pwd1 = $c->getSilentInput();

$c->println("Veuillez saisir à nouveau le même mot de passe :");
$pwd2 = $c->getSilentInput();

if ($pwd1 <> $pwd2) {
    $c->printDie('Les mots de passe saisis ne correspondent pas!');
}

$c->println('Application du changement de mot de pase ...');

$args = "changement-mot-de-passe --utilisateur=$login --mot-de-passe=$pwd1";
$c->passthru("php " . getcwd() . "/public/index.php " . $args);

$c->println('Mot de passe changé', $c::COLOR_LIGHT_GREEN);