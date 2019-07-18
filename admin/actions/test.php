<?php

$bdd        = new \BddAdmin\Bdd(Config::get()['bdds']['deploy-local']);
$bdd->debug = true;

$oa = new OseAdmin();
$oa->setBdd($bdd);

$dataGen = new DataGen($oa);
$dataGen->update();


/*

$bddp        = new \BddAdmin\Bdd(Config::get()['bdds']['dev-local']);

$i = $bddp->select('SELECT * FROM formule order by libelle');
$indicateurs = [];
foreach( $i as $indic ){

    $indicateurs[] = $indic;
}
var_dump($indicateurs);

file_put_contents($oa->getOseDir().'/data/formules.php', var_export($indicateurs,true));
*/