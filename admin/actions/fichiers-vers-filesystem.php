<?php

$bdd = $oa->getBdd();

$conf = $oa->config()->get('fichiers');

if (!$conf['stockage'] == 'file') {
    $c->printDie('Votre instance ne stocke pas les fichiers dans le système de fichiers');
}

$dir = $conf['dir'];
if (substr($dir, -1) != '/') $dir .= '/';

$f     = $bdd->select('SELECT ID, CONTENU FROM FICHIER WHERE CONTENU IS NOT NULL', [], ['fetch' => $bdd::FETCH_EACH]);
$count = (int)$bdd->select('SELECT COUNT(*) c FROM FICHIER WHERE CONTENU IS NOT NULL', [], ['fetch' => $bdd::FETCH_ONE])['C'];
$i     = 0;
$c->begin("Transfert du contenu des fichiers de la base de données vers le système de fichiers");
while ($fichier = $f->next()) {
    $i++;
    $c->msg("Transfert $i / $count...", true);

    $id      = (int)$fichier['ID'];
    $contenu = $fichier['CONTENU'];

    $filename = 'd' . (str_pad((string)floor($id / 1000), 4, '0', STR_PAD_LEFT))
        . '/f'
        . str_pad((string)($id % 1000), 3, '0', STR_PAD_LEFT);

    $filename = $dir . $filename;
    if ($contenu && !file_exists($filename)) {
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename));
        }
        file_put_contents($filename, $contenu);
        if (file_exists($filename)) {
            $bdd->getTable('FICHIER')->update(['CONTENU' => null], ['ID' => $id]);
        }
    }
}

$c->end("Fin du transfert");