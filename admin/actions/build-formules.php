<?php

/** @var \Formule\Service\FormulatorService $formulator */
$formulator = OseAdmin::instance()->container()->get(\Formule\Service\FormulatorService::class);

$formuleName = $c->getArg(2) ?? "";

$dir = getcwd() . '/data/formules';
$fichiers = scandir($dir);

$cacheDir = $formulator->cacheDir();

if (file_exists($cacheDir)) {
    $c->exec([
        "cd " . getcwd(),
        "rm -Rf $cacheDir",
    ], false);
}


$c->begin('Construction de toutes les formules de calcul');

foreach ($fichiers as $fichier) {
    if ('.' != $fichier && '..' != $fichier && (strtolower($fichier) == strtolower($formuleName).'.ods' || empty($formuleName))) {
        $c->println('Construction de ' . $fichier . ' ...');
        try {
            $filename = $dir . '/' . $fichier;
            $formulator->update($filename);
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }
    }
}

$c->end();

echo $dir;