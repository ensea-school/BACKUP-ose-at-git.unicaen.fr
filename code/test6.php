<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$data = require 'data/donnees_par_defaut.php';
$data = $data['CORPS'];

//unset($data[34]); // 213

$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

$table = $bdd->getTable('CORPS');

$table->delete(['SOURCE_CODE' => 639]);
$table->update(['LIBELLE_COURT' => 'test'], ['SOURCE_CODE' => 602]);
$table->update(['HISTO_DESTRUCTION' => new \DateTime(), 'HISTO_DESTRUCTEUR_ID' => 1], ['SOURCE_CODE' => 813]);

$options = [
    'undelete' => false,
];

$table->merge2($data, 'SOURCE_CODE', $options);