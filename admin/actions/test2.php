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
$config = $oa->config()->get('bdd');

$bdd = new Bdd($config);

$bdd->setOptions([
    /* Facultatif, permet de spécifier une fois pour toutes le répertoire où sera renseignée la DDL de votre BDD */
    Bdd::OPTION_DDL_DIR => getcwd() . '/data/ddl',

    /* Facultatif, spécifie le répertoire où seront stockées vos scripts de migration si vous en avez */
    //Bdd::OPTION_MIGRATION_DIR => getcwd() . '/admin/migration/',

    /* Facultatif, permet de personnaliser l'ordonnancement des colonnes dans les tables */
    Bdd::OPTION_COLUMNS_POSITIONS_FILE => getcwd() . '/data/ddl_columns_pos.php',
]);


$data = $bdd->select('select * from annee');
var_dump($data);