<?php

$bdd = $oa->getBdd();
/*
$table = 'TYPE_VOLUME_HORAIRE';
$table = 'ETAT_SORTIE';

$dir = getcwd() . '/cache/' . $table;

if (is_dir($dir)) rrmdir($dir);
mkdir($dir);
$bdd->getTable($table)->save($dir);

$d = $bdd->getTable($table)->load($dir);

function rrmdir($src)
{
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $full = $src . '/' . $file;
            if (is_dir($full)) {
                rrmdir($full);
            } else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

*/

$dir = getcwd() . "/cache/bdd-save";
/*$bdd->save($dir, ['FICHIER' => function ($d) {
    $d['TAILLE']  = 0;
    $d['CONTENU'] = null;

    return $d;
}]);*/

$bdd->load($dir);