<?php

/** @var \UnicaenTbl\Service\TableauBordService $c */
$c = $container->get(\UnicaenTbl\Service\TableauBordService::class);

$ptbl = $c->getTableauBord('paiement');


$params = [
    'INTERVENANT_ID' => 32779,
    //'INTERVENANT_ID' => 20970,
    //'SERVICE_REFERENTIEL_ID' => 19194
    //'SERVICE_ID' => 258206,
    //'ANNEE_ID' => 2014,
];




$debut = microtime(true);

$data = $ptbl->getProcess()->getData($params);
echo '<h2>Data en entrée</h2>';
echo phpDump($data);


$ptbl->calculer($params);


$res = phpDump(OseAdmin::instance()->getBdd()->getTable('TBL_PAIEMENT')->select($params));
echo '<h2>Résultat</h2>';
echo phpDump($res);

$fin = microtime(true);
$duree = $fin - $debut;

echo "  Durée : ".round($duree, 4) . ' seconde(s)'."\n";