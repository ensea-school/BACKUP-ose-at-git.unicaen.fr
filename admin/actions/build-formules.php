<?php

/** @var \Formule\Service\FormulatorService $formulator */
$formulator = OseAdmin::instance()->container()->get(\Formule\Service\FormulatorService::class);

$dir = getcwd().'/data/formules';
$fichiers = scandir($dir);

$c->begin('Construction de toutes les formules de calcul');

foreach( $fichiers as $fichier ) {
    if ('.' != $fichier && '..' != $fichier){
        $c->println('Construction de '.$fichier.' ...');
        try {
            $filename = $dir.'/'.$fichier;
            $formulator->update($filename);
        }catch(\Exception $e){
            $c->println($e->getMessage(), $c::COLOR_RED);
        }
    }

}

$c->end();

echo $dir;