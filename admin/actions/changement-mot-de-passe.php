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
$oa->exec("changement-mot-de-passe --utilisateur=$login --mot-de-passe=$pwd1");

$c->println('Mot de passe changé', $c::COLOR_LIGHT_GREEN);