<?php

$fromMaster = true;

$osedir    = $oa->getOseDir();
$bdd       = $oa->getBdd();
$oseSource = $oa->getSourceOseId();
$oseId     = $oa->getOseAppliId();
$c->println("Mise à jour de la table employeur");

ini_set('memory_limit', '1024M');
$importDirectory = Config::get('employeur', 'import-directory');
$importArchive   = Config::get('employeur', 'import-archive');
//On vérifier que le fichier est présent
if (!is_file($importDirectory . $importArchive)) {
    $c->println("L'archive $importArchive manquante", $c::COLOR_LIGHT_RED);
    exit;
}
//On vérifie que le répertoire import contient uniquement l'archive et aucun autre CSV
{
    $listFiles = preg_grep('~\.(csv)$~', scandir($importDirectory));
}
if (count($listFiles) > 0) {
    $c->println("Merci de supprimer les fichiers CSV présents dans le dossier $importDirectory", $c::COLOR_LIGHT_RED);
    exit;
}
//Extraction du PharData
$phar = new PharData($importDirectory . $importArchive);
$phar->extractTo($importDirectory);
//récupération de la liste des fichiers CSV
$listFiles = preg_grep('~\.(csv)$~', scandir($importDirectory));
$nbFiles   = count($listFiles);
$i         = 1;
$c->println("Nombre de fichier à charger : $nbFiles", $c::COLOR_LIGHT_GREEN);
$tableEmployeur = $bdd->getTable('EMPLOYEUR');

foreach ($listFiles as $file) {
    $num = str_replace('.csv', '', $file);

    $c->println("Chargement du fichier employeur N° $i sur $nbFiles");
    $csvFile = fopen($importDirectory . $file, "r");
    $row     = 0;
    $datas   = [];
    while (($data = fgetcsv($csvFile, 1000, ",")) !== false) {
        /*
        * $data[0] = Siret
        * $data[1] = Etat Administratif
        * $data[2] = Nom unité légale (cas entreprise en nom propre)
        * $data[3] = Nom usage unité légale
        * $data[4] = Raison sociale pour les personnes morales
        * $data[5] = Nom sous lequel est connu l'entreprise du grand public (champs N°1 à 70 carac)
        * $data[6] = Nom sous lequel est connu l'entreprise du grand public (champs N°2 à 70 carac)
        * $data[7] = Nom sous lequel est connu l'entreprise du grand public (champs N°3 à 70 carac)
        * $data[8] = Date de dernier traitement de l'unité légale
        * $data[9] = Unité pouvant employer des personnes
        * $data[10] = Identifiant association
        *
        */

        if ($row == 0) {
            $row++;
            continue;
        }
        //DENOMINATION_USUELLE
        $nomCommercial = (!empty($data[5])) ? $data[5] : '';
        $nomCommercial .= (!empty($data[6])) ? ' ' . $data[6] : '';
        $nomCommercial .= (!empty($data[7])) ? ' ' . $data[7] : '';
        $nomCommercial = str_replace("'", "''", $nomCommercial);
        //RAISON_SOCIALE
        $nomJuridique = $data[4];
        //SIREN
        $siren = $data[0];
        //IDENTIFIANT ASSOCIATION
        $identifiantAssociation = $data[10];
        //Nom propre entité
        $nomPropre = $data[2];
        //Nom usage entité au lieu du nom propre
        $nomUsage = $data[3];
        //Raison sociale
        if (!empty($nomJuridique)) {
            $raisonSociale = $nomJuridique;
        } elseif (!empty($nomUsage)) {
            $raisonSociale = $nomUsage;
        } elseif (!empty($nomPropre)) {
            $raisonSociale = $nomPropre;
        }
        $raisonSociale = str_replace("'", "''", $raisonSociale);
        //Si pas de raison sociale et pas de nom commercial on passe
        if (empty($raisonSociale) && empty($nomCommercial)) {
            continue;
        }
        //Compilation des datas
        $data                            = [];
        $options                         = [];
        $data['SIREN']                   = $siren;
        $data['RAISON_SOCIALE']          = $raisonSociale;
        $data['NOM_COMMERCIAL']          = $nomCommercial;
        $data['SOURCE_CODE']             = $siren;
        $data['SOURCE_ID']               = $oseSource;
        $data['IDENTIFIANT_ASSOCIATION'] = $identifiantAssociation;
        $data['HISTO_DESTRUCTEUR_ID']    = null;
        $data['HISTO_DESTRUCTION']       = null;
        $data['IDENTIFIANT_ASSOCIATION'] = $identifiantAssociation;
        $data['CRITERE_RECHERCHE']       = reduce($raisonSociale . ' ' . $nomCommercial . ' ' . $siren);
        $datas[]                         = $data;
        $options['histo-user-id']        = $oseId;
        $options['where']                = 'SIREN LIKE \'' . $num . '%\'';
        $options['soft-delete']          = true;
    }
    $i++;

    $tableEmployeur->merge($datas, 'SIREN', $options);
}
$c->println("Fin de mise à jour des données employeurs", $c::COLOR_LIGHT_GREEN);


function reduce($str, $encoding = 'UTF-8')
{
    $from = 'ÀÁÂÃÄÅÇÐÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜŸÑàáâãäåçðèéêëìíîïòóôõöøùúûüÿñ€@()…,<>/?€%!":’\'';
    $to   = 'aaaaaacdeeeeiiiioooooouuuuynaaaaaacdeeeeiiiioooooouuuuynea_______________';

    $rstr = '';
    $ok   = true;
    $len  = mb_strlen($str, $encoding);
    for ($i = 0; $i < $len; $i++) {
        $char = mb_substr($str, $i, 1, $encoding);
        $pos  = mb_strpos($from, $char, 0, $encoding);
        if (false === $pos) {
            $rstr .= $char;
        } else {
            $rstr .= mb_substr($to, $pos, 1, $encoding);
        }
    }

    return strtolower($rstr);
}


