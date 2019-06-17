<?php

$osedir = $oa->getOseDir();

$c->println("\nMise à jour des liens vers les répertoires publics des dépendances", $c::COLOR_LIGHT_CYAN);

$res = $oa->majUnicaenSymLinks($osedir);

if ($res) {
    $c->println('Liens mis à jour', $c::COLOR_LIGHT_GREEN);
} else {
    $c->println('Liens déjà à jour', $c::COLOR_LIGHT_GREEN);
}