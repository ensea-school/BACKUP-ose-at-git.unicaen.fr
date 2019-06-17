<?php

$c->println("\nCréation d'un nouveau compte utilisateur", $c::COLOR_LIGHT_CYAN);

$params = $c->getInputs([
    'nom'               => 'Nom de l\'utilisateur',
    'prenom'            => 'Prénom',
    'date-naissance'    => ['description' => 'Date de naissance (format jj/mm/aaaa)', 'type' => 'date'],
    'login'             => 'Login',
    'mot-de-passe'      => ['description' => 'Mot de passe (6 caractères minimum)', 'silent' => true],
    'creer-intervenant' => ['description' => 'Voulez-vous créer un intervenant pour cet utilisateur ? (O ou Y pour oui)', 'type' => 'bool'],
]);

var_export($params);