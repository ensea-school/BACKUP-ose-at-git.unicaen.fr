<?php

$bdd        = new \BddAdmin\Bdd(Config::get()['bdds']['dev-local']);
//$bdd->debug = true;


$u = $bdd->select("SELECT id FROM UTILISATEUR WHERE USERNAME='oseappli'");
if (isset($u[0]['ID'])){
    $u = (int)$u[0]['ID'];
}
var_dump($u);


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