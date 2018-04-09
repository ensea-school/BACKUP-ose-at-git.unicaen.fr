<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Constants;
use Doctrine\ORM\EntityManager;
use UnicaenCode\Console;

if (!isset($_SERVER['argv']) || !in_array('bdd=deploy', $_SERVER['argv'])) {
    Console::println("Attention : l'opération DOIT se dérouler sur la base de déploiement!!\n", null, Console::BG_RED);
    die();
}

Console::printMainTitle('Suppression totale de contenu de schéma de BDD');

/** @var EntityManager $em */
$em = $sl->get(Constants::BDD);

$schema = strtoupper($em->getConnection()->getSchemaManager()->getSchemaSearchPaths()[0]);

$queries = [
    'Suppression des triggers'  =>
        "SELECT 'DROP TRIGGER ' || trigger_name dsql FROM ALL_TRIGGERS WHERE owner='$schema'",
    'Suppression des séquences' =>
        "SELECT 'DROP SEQUENCE ' || sequence_name dsql FROM ALL_SEQUENCES WHERE sequence_owner='$schema'",

    'Suppression des contraintes' =>
        "SELECT 'ALTER TABLE ' || table_name || ' DROP CONSTRAINT ' || constraint_name dsql
        FROM ALL_CONSTRAINTS WHERE owner='$schema' AND constraint_type = 'R'",

    'Suppression des vues' =>
        "SELECT 'DROP VIEW ' || view_name dsql FROM ALL_VIEWS WHERE owner = '$schema'",

    'Suppression des vues matérialisées' =>
        "SELECT 'DROP MATERIALIZED VIEW ' || mview_name dsql FROM ALL_MVIEWS WHERE owner = '$schema'",

    'Suppression des packages' =>
        "SELECT 'DROP PACKAGE ' || object_name dsql FROM USER_OBJECTS WHERE object_type = 'PACKAGE'",

    'Suppression des tables' =>
        "SELECT 'DROP TABLE ' || table_name dsql FROM ALL_TABLES WHERE owner='$schema'",

    'Suppression des index restants' =>
        "SELECT 'DROP INDEX ' || index_name dsql FROM ALL_INDEXES WHERE owner = '$schema'",
];

$count = 0;
$errors = 0;

foreach ($queries as $title => $query) {
    list($c,$errs) = makeExec($query, $em);
    Console::print('=> '.Console::strPad($title,40));
    Console::print($c." supprimés", Console::COLOR_GREEN);
    Console::print(', ');
    Console::print($errs." erreurs", Console::COLOR_RED);
    Console::println('.');

    $count += $c;
    $errors += $errs;
}

Console::print('Processus terminé : ');
Console::print("$count éléments supprimés", Console::COLOR_GREEN);
Console::print(', ');
Console::print("$errors erreurs rencontrées", Console::COLOR_RED);
Console::println('.');



function makeExec($sql, EntityManager $em)
{
    $queries = $em->getConnection()->fetchAll($sql);

    $count = 0;
    $errors = 0;
    foreach ($queries as $query) {
        $sql = $query['DSQL'];

        try {
            $em->getConnection()->exec($query['DSQL']);
            $count++;
        } catch (\Exception $e) {
            Console::println("ERREUR SUR LA REQUETE SUIVANTE :\n$sql\nMESSAGE :\n".$e->getMessage(), null, Console::BG_RED);
            $errors++;
        }
    }

    return [$count,$errors];
}