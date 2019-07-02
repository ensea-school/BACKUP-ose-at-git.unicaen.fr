<?php

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$c->println("\n".'Mise à jour des définitions de la base de données', $c::COLOR_LIGHT_PURPLE);

/* Récupération du schéma de référence */
$ref = $schema->loadFromFile($oa->getOseDir() . 'bdd/ddl.php');


/* Construction de la config de DDL pour filtrer */
$ddlConfig = [];
// On me met à jour que les objets présents dans le schéma par défaut
foreach ($ref as $ddlClass => $config) {
    $ddlConfig[$ddlClass] = ['includes' => array_keys($config)];
}
$ddlConfig = [
    'explicit'                      => true,
    \BddAdmin\Ddl\DdlView::class    => $ddlConfig[\BddAdmin\Ddl\DdlView::class],
    \BddAdmin\Ddl\DdlPackage::class => $ddlConfig[\BddAdmin\Ddl\DdlPackage::class],
    \BddAdmin\Ddl\DdlTrigger::class => $ddlConfig[\BddAdmin\Ddl\DdlTrigger::class],
]; // Pour le moment, travail uniquement sur ces 3 structures de données. Pour les autres, cela viendra plus tard.

//$ddlConfig[\BddAdmin\Ddl\DdlView::class]['includes'] = [
//    'V_FORMULE_LOCAL_I_PARAMS',
//    'V_FORMULE_LOCAL_VH_PARAMS',
//];


/* Mise en place du logging en mode console */
$scl          = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);

/* Mise à jour de la BDD */
$schema->alter($ref, $ddlConfig, true);

$c->println('Fin de mise à jour des définitions');


$c->println('Mise à jour des privilèges', $c::COLOR_LIGHT_PURPLE);
$oa->majPrivileges();
$c->println('Fin de la mise à jour des privilèges');


$c->println('');