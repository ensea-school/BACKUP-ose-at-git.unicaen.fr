<?php

$oseDir = $oa->getOseDir();

$c->println("\nMise à jour des liens vers les répertoires publics des dépendances", $c::COLOR_LIGHT_CYAN);

/** @todo à supprimer mi-2020 */
if (file_exists($oseDir . "public/vendor/unicaen/app/unicaen")) {
    $this->console->exec("rm $oseDir" . "public/vendor/unicaen/app");
}

$oldLibs = [];
$od      = array_filter(glob($oseDir . 'public/vendor/unicaen/*'), 'is_dir');
foreach ($od as $dir) {
    $oldLibs[] = basename($dir);
}

$newLibs = [];
$nd      = array_filter(glob($oseDir . 'vendor/unicaen/*'), 'is_dir');
foreach ($nd as $dir) {
    if (is_dir($dir . '/public')) {
        $newLibs[] = basename($dir);
    }
}

$deleteLibs = array_diff($oldLibs, $newLibs);
$createLibs = array_diff($newLibs, $oldLibs);

foreach ($deleteLibs as $lib) {
    $command = "rm $oseDir" . "public/vendor/unicaen/$lib";
    $c->print($command);
    $c->exec($command);
}

foreach ($createLibs as $lib) {
    if (is_dir($oseDir . "/vendor/unicaen/$lib/public/unicaen/$lib")) {
        $command = "cd $oseDir" . "public/vendor/unicaen;ln -sf ../../../vendor/unicaen/$lib/public/unicaen/$lib $lib";
    } else {
        $command = "cd $oseDir" . "public/vendor/unicaen;ln -sf ../../../vendor/unicaen/$lib/public $lib";
    }
    $c->print($command);
    $c->exec($command);
}


if (!(empty($deleteLibs) && empty($createLibs))) {
    $c->println('Liens mis à jour', $c::COLOR_LIGHT_GREEN);
} else {
    $c->println('Liens déjà à jour', $c::COLOR_LIGHT_GREEN);
}