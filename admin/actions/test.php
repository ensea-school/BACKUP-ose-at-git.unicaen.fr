<?php

$bdd        = new \BddAdmin\Bdd(Config::get()['bdds']['dev-local']);
//$bdd->debug = true;

$schema = new \BddAdmin\Schema($bdd);

$schema->majSequences();

$oa = new OseAdmin();
$oa->setBdd($bdd);

$dataGen = new DataGen($oa);
$dataGen->update();



/*
$bddp        = new \BddAdmin\Bdd(Config::get()['bdds']['dev-local']);

$i = $bddp->select('SELECT * FROM tbl order by ordre');
$indicateurs = [];
foreach( $i as $indic ){

    $indicateurs[] = $indic;
}
var_dump($indicateurs);

file_put_contents($oa->getOseDir().'/data/tbl.php', var_export($indicateurs,true));
*/