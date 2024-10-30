<?php

use Unicaen\BddAdmin\Ddl\Ddl;

/* Initialisation */
$bdd = $oa->getBdd();

/* Mise en place du schéma de la BDD */
$ref = new Ddl();
$ref->loadFromDir(getcwd() . '/data/ddl');
$bdd->create($ref);

/* Insertion des données */

// On s'occupe d'abord de créer puis d'initialiser l'utilisateur OSE et la source OSE
$bdd->dataUpdater()->run('install', 'UTILISATEUR');
$bdd->dataUpdater()->run('install', 'SOURCE');

// On installe ensuite toutes les données
$bdd->dataUpdater()->run('install');
//Provisoire en attendant de mettre à jour les données par défaut
$sqlUpdatePjActive = "UPDATE statut SET PJ_ACTIVE  = 0 WHERE id NOT IN (
                                    SELECT s.id FROM type_piece_jointe_statut tpjs
                                    JOIN statut s ON s.id = tpjs.statut_id 
                                    AND tpjs.histo_destruction is NULL
                                    GROUP BY s.id)";
$bdd->exec($sqlUpdatePjActive);

/* On construit les plafonds et les tableaux de bord */
$args = 'plafonds construire';
$c->passthru("php " . getcwd() . "/public/index.php " . $args);


/* Définition d'un mdp pour oseappli */
if ($c->hasOption('oseappli-pwd')) {
    $pwd1 = $c->getOption('oseappli-pwd');
} else {
    $c->println("\n" . 'Choix d\'un mot de passe pour l\'utilisateur système oseappli', $c::COLOR_LIGHT_CYAN);
    $c->println("Veuillez saisir un mot de passe (au minimum 6 caractères) :");
    $pwd1 = $c->getSilentInput();

    $c->println("Veuillez saisir à nouveau le même mot de passe :");
    $pwd2 = $c->getSilentInput();

    if ($pwd1 <> $pwd2) {
        $c->printDie('Les mots de passe saisis ne correspondent pas!');
    }
}

$c->println('Application du mot de passe de oseappli...');
$args = "changement-mot-de-passe --utilisateur=oseappli --mot-de-passe=$pwd1";
$c->passthru("php " . getcwd() . "/public/index.php " . $args);

$c->println('Mot de passe changé', $c::COLOR_LIGHT_GREEN);

$c->println('Vous pourrez vous connecteur à OSE avec le login "oseappli" et votre nouveau mot de passe.');


/* Terminé!! */
$c->println('L\'installation de la base de données est maintenant terminée!', $c::COLOR_LIGHT_GREEN);