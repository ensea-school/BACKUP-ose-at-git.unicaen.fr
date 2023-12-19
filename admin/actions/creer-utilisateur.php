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

$params['date-naissance'] = $params['date-naissance']->format('d/m/Y');
$params['params']         = ['creer-intervenant' => $params['creer-intervenant']];
unset($params['creer-intervenant']);

if ($params['params']['creer-intervenant']) {
    if ($c->hasOption('code')) {
        $params['params']['code'] = $c->getOption('code');
    }


    $bdd = $oa->getBdd();

    $annee                     = $bdd->select("SELECT LIBELLE FROM ANNEE WHERE ID = (SELECT VALEUR FROM PARAMETRE WHERE NOM = 'annee')", [], ['fetch' => $bdd::FETCH_ONE])['LIBELLE'];
    $params['params']['annee'] = $c->getInput('annee', 'Année universitaire (' . $annee . ' par défaut, sinon entrez 2020 pour 2020/2021, etc.)');

    if (!$c->hasOption('statut')) {
        $statuts = $bdd->select("SELECT CODE CODE, LIBELLE FROM STATUT WHERE HISTO_DESTRUCTION IS NULL AND CODE <> 'AUTRES' ORDER BY ORDRE");
        $c->println('Statut de \'intervenant ("AUTRES" par défaut, sinon entrez le code parmi les propositions suivantes) :');
        $maxCodeLength = 0;
        foreach ($statuts as $statut) {
            $sLen = strlen($statut['CODE']);
            if ($sLen > $maxCodeLength) $maxCodeLength = $sLen;
        }
        foreach ($statuts as $statut) {
            $c->print(' * ');
            $c->print(str_pad($statut['CODE'], $maxCodeLength, ' '), $c::COLOR_CYAN);
            $c->println(' ' . $statut['LIBELLE']);
        }
    }
    $params['params']['statut'] = $c->getInput('statut');
}

$params = base64_encode(json_encode($params));

$args = "creer-utilisateur --data=$params";
$c->passthru("php " . getcwd() . "/public/index.php " . $args);