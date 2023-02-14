<?php

$fromMaster = true;

$osedir = $oa->getOseDir();
$bdd = $oa->getBdd();
$oseSource = $oa->getSourceOseId();
$oseId = $oa->getOseAppliId();
$c->println("Mise à jour de la table employeur");

ini_set('memory_limit', '-1');
$importDirectory = $osedir . 'cache/employeurs/';
$importArchive = 'employeurs.tar.gz';
$importFilePath = $importDirectory . $importArchive;
if (!file_exists($importDirectory)) {
    mkdir($importDirectory);
}

if (file_exists($importFilePath)) {
    unlink($importFilePath);
}
$c->exec("cd $importDirectory;wget https://ose.unicaen.fr/employeurs.tar.gz;rm -rf *.csv");

//On vérifier que le fichier est présent
if (!file_exists($importFilePath)) {
    $c->printDie("L'archive $importArchive manquante");
}
//On vérifie que le répertoire import contient uniquement l'archive et aucun autre CSV

$listFiles = preg_grep('~\.(csv)$~', scandir($importDirectory));
if (count($listFiles) > 0) {
    $c->printDie("Merci de supprimer les fichiers CSV présents dans le dossier $importDirectory");
}
//Extraction du PharData
$phar = new PharData($importFilePath);
$phar->extractTo($importDirectory);
exec('cd ' . $importDirectory . ' && tar -xvf ' . $importFilePath);
//Verification si des SIRET sont déjà chargé en base
$bdd = $oa->getBdd();

//On vérifie si la source INSEE existe dans OSE

$haveAlreadyInseeSource = $bdd->select("SELECT * FROM source WHERE code='INSEE'", [], ['fetch' => $bdd::FETCH_ONE]);
if (!($haveAlreadyInseeSource)) {
    $idSource = $bdd->sequenceNextVal('SOURCE_ID_SEQ');
    $bdd->exec("INSERT INTO source (id, code, libelle, importable) VALUES ($idSource, 'INSEE', 'INSEE', 1)");
    //Le source INSEE n'existe pas encore, donc on migre tout ce qui est source OSE vers source INSEE
    $bdd->exec("UPDATE employeur SET source_id = $idSource");
} else {
    $idSource = $haveAlreadyInseeSource['ID'];
}

//$haveAlreadySiret     = $bdd->select("SELECT siret FROM employeur e WHERE siret IS NOT null FETCH FIRST 5 ROWS ONLY", [], ['fetch' => $bdd::FETCH_ONE]);
$haveAlreadySiret = $bdd->select("SELECT siret FROM (SELECT siret, rownum AS rn FROM employeur e WHERE siret IS NOT NULL) e WHERE e.rn < 10", [], ['fetch' => $bdd::FETCH_ONE]);
$haveAlreadyEmployeur = $bdd->select("SELECT * FROM employeur e");


//récupération de la liste des fichiers CSV
$listFiles = preg_grep('~\.(csv)$~', scandir($importDirectory));

$nbFiles = count($listFiles);
$i = 1;
$c->println("Nombre de fichier à charger : $nbFiles", $c::COLOR_LIGHT_GREEN);
$tableEmployeur = $bdd->getTable('EMPLOYEUR');

foreach ($listFiles as $file) {

    $num = str_replace('.csv', '', $file);

    $c->println("Chargement du fichier employeur N° $i sur $nbFiles");

    $csvFile = fopen($importDirectory . $file, "r");

    $row = 0;
    $datas = [];

    while (($data = fgetcsv($csvFile, 1000, ",")) !== false) {


        /*
        * $data[0] = Siren
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
        * $data[12] = Siret
        *
        */


        /* if ($row == 0) {
             $row++;
             continue;
         }*/
        //DENOMINATION_USUELLE
        $nomCommercial = (!empty($data[5])) ? $data[5] : '';
        $nomCommercial .= (!empty($data[6])) ? ' ' . $data[6] : '';
        $nomCommercial .= (!empty($data[7])) ? ' ' . $data[7] : '';
        $nomCommercial = str_replace("''", "'", $nomCommercial);
        //RAISON_SOCIALE
        $nomJuridique = $data[4];
        //SIREN
        $siren = $data[0];
        //SIRET
        $siret = (isset($data[12])) ? $data[0] . $data[12] : '';

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
        $raisonSociale = str_replace("''", "'", $raisonSociale);
        //Si pas de raison sociale et pas de nom commercial on passe
        if (empty($raisonSociale) && empty($nomCommercial)) {
            continue;
        }
        //Compilation des datas
        $data = [];
        $options = [];
        $data['SIREN'] = $siren;
        $data['SIRET'] = $siret;
        $data['RAISON_SOCIALE'] = $raisonSociale;
        $data['NOM_COMMERCIAL'] = $nomCommercial;
        $data['SOURCE_CODE'] = $siret;
        $data['SOURCE_ID'] = $idSource;
        $data['IDENTIFIANT_ASSOCIATION'] = $identifiantAssociation;
        $data['HISTO_DESTRUCTEUR_ID'] = null;
        $data['HISTO_DESTRUCTION'] = null;
        $data['IDENTIFIANT_ASSOCIATION'] = $identifiantAssociation;
        $data['CRITERE_RECHERCHE'] = reduce($raisonSociale . ' ' . $nomCommercial . ' ' . $siren . ' ' . $siret);
        $datas[] = $data;
        $options['histo-user-id'] = $oseId;
        $options['where'] = 'SIREN LIKE \'' . $num . '%\' AND SOURCE_ID = (SELECT id FROM source WHERE code = \'INSEE\') AND SIREN NOT IN (\'999999999\', \'000000000000\')';
        $options['delete'] = false;
    }

    $i++;

    $tableEmployeur->merge($datas, 'SIREN', $options);
    if (!$haveAlreadySiret) {
        $c->println('Migration avec ajouts des SIRET des entreprises');
        $tableEmployeur->merge($datas, 'SIRET', $options);
    }
}

//On remet l'insertion de l'employeur étrangé
$data = [];
$data['SIREN'] = '999999999';
$data['RAISON_SOCIALE'] = 'EMPLOYEUR ETRANGÉ';
$data['NOM_COMMERCIAL'] = 'EMPLOYEUR ETRANGÉ';
$data['SOURCE_CODE'] = '999999999';
$data['SOURCE_ID'] = $idSource;
$data['IDENTIFIANT_ASSOCIATION'] = null;
$data['HISTO_DESTRUCTEUR_ID'] = null;
$data['HISTO_DESTRUCTION'] = null;
$data['IDENTIFIANT_ASSOCIATION'] = null;
$data['CRITERE_RECHERCHE'] = reduce('Employeur étrangé 999999999');
$options['histo - user - id'] = $oseId;
$options['where'] = 'SIREN = \'999999999\'';
$options['soft-delete'] = true;
$datas = [];
$datas[] = $data;
$tableEmployeur->merge($datas, 'SIREN', $options);

$data = [];
$data['SIREN'] = '000000000000';
$data['RAISON_SOCIALE'] = 'Employeur non présent dans la liste';
$data['NOM_COMMERCIAL'] = 'Employeur non présent dans la liste';
$data['SOURCE_CODE'] = '000000000000';
$data['SOURCE_ID'] = $idSource;
$data['IDENTIFIANT_ASSOCIATION'] = null;
$data['HISTO_DESTRUCTEUR_ID'] = null;
$data['HISTO_DESTRUCTION'] = null;
$data['IDENTIFIANT_ASSOCIATION'] = null;
$data['CRITERE_RECHERCHE'] = reduce('Employeur non présent dans la liste 000000000000');
$options['histo-user-id'] = $oseId;
$options['where'] = 'SIREN = \'000000000000\'';
$options['soft-delete'] = true;
$datas = [];
$datas[] = $data;
$tableEmployeur->merge($datas, 'SIREN', $options);


exec('cd ' . $importDirectory . ' && rm -rf *.csv');
$c->println("Fin de mise à jour des données employeurs", $c::COLOR_LIGHT_GREEN);
unlink($importFilePath);
exec('rm -r ' . $importDirectory);


function reduce($str, $encoding = 'UTF-8')
{
    $from = 'ÀÁÂÃÄÅÇÐÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜŸÑàáâãäåçðèéêëìíîïòóôõöøùúûüÿñ€@()…,<>/?€%!":’\'';
    $to = 'aaaaaacdeeeeiiiioooooouuuuynaaaaaacdeeeeiiiioooooouuuuynea_______________';

    $rstr = '';
    $ok = true;
    $len = mb_strlen($str, $encoding);
    for ($i = 0; $i < $len; $i++) {
        $char = mb_substr($str, $i, 1, $encoding);
        $pos = mb_strpos($from, $char, 0, $encoding);
        if (false === $pos) {
            $rstr .= $char;
        } else {
            $rstr .= mb_substr($to, $pos, 1, $encoding);
        }
    }

    return strtolower($rstr);
}


