<?php


// Initialisation
//$bdd = $oa->getBdd();

//$ref = new Ddl();
//$ref->loadFromDir(getcwd() . '/data/ddl-pg');



//$d = $bdd->schema()->create(['name' => 'lololo8']);
//$d = $bdd->schema()->rename('lololo8', 'lo9');
//$d = $bdd->schema()->drop('lo9');

//$bdd->drop();


//$filter = new \Unicaen\BddAdmin\Ddl\DdlFilters();


use Unicaen\BddAdmin\Bdd;

/* Paramètres d'accès à votre BDD */
$config = [
    'driver'   => 'Postgresql',
    'host'     => 'host.docker.internal',
    'port'     => '5432',
    'dbname'   => 'ose-dev',
    'username' => 'laurent',
    'password' => 'oustBN6',
];

$bdd = new Bdd($config);

$bdd->setOptions([
    /* Facultatif, permet de spécifier une fois pour toutes le répertoire où sera renseignée la DDL de votre BDD */
    Bdd::OPTION_DDL_DIR => getcwd() . '/data/ddl',

    /* Facultatif, spécifie le répertoire où seront stockées vos scripts de migration si vous en avez */
    //Bdd::OPTION_MIGRATION_DIR => getcwd() . '/admin/migration/',

    /* Facultatif, permet de personnaliser l'ordonnancement des colonnes dans les tables */
    Bdd::OPTION_COLUMNS_POSITIONS_FILE => getcwd() . '/data/ddl_columns_pos.php',
]);


// Récupération du schéma de référence, issu du répertoire spécifié via l'option Bdd::OPTION_DDL_DIR
$ref = $bdd->getRefDdl();

// Filtre pour l'appliquer les modification que pour la DDL et ne supprime pas les objets autres
// C'est facultatif, à activer selon contexte
$filters = $ref->makeFilters();

// On met à jour la BDD
$bdd->alter($ref, $filters);
