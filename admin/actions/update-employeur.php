<?php

$fromMaster = true;

$osedir = $oa->getOseDir();
$bdd = $oa->getBdd();

$c->println("Mise à jour de la table employeur");

$file = Config::get('employeur', 'import-file');
$nbLigne = shell_exec('wc -l ' . $file);
$c->println("Nombre de ligne à traiter : " . $nbLigne);
$csvFile = fopen($file, "r");
$row = 0;
$c->println("Suppression des employeurs en base de données");
$sql = "DELETE FROM EMPLOYEUR";
$bdd->exec($sql);

$bdd->beginTransaction();
while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
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
    $datetime = new \DateTime();
    $date =  $datetime->format('y-m-d h:m:s');
    if($row == 0)
    {
        $c->println("Debut import table employeur : " . $date);
        $row++;
        continue;
    }
    $nextId = $bdd->sequenceNextVal('EMPLOYEUR_ID_SEQ');
    $datetime = new \DateTime();
    $date =  $datetime->format('Y-m-d h:i:s');
    //Traitement RAISON_SOCIALE ET DENOMINATION_USUELLE

    //DENOMINATION_USUELLE
    $nomCommercial = (!empty($data[5]))?$data[5]:'';
    $nomCommercial .= (!empty($data[6]))?' ' . $data[6]:'';
    $nomCommercial .= (!empty($data[7]))?' ' . $data[7]:'';
    $nomCommercial = str_replace("'", "''",$nomCommercial);

    //RAISON_SOCIALE
    $nomJuridique = $data[4];
    //Nom propre entité
    $nomPropre = $data[2];
    //Nom usage entité au lieu du nom propre
    $nomUsage = $data[3];

    if (!empty($nomJuridique)){
        $raisonSociale = $nomJuridique;
    }elseif (!empty($nomUsage)){
        $raisonSociale = $nomUsage;
    }elseif(!empty($nomPropre)){
        $raisonSociale = $nomPropre;
    }

    $raisonSociale = str_replace("'", "''", $raisonSociale);

    //Pas de libellé correcte pour l'unité on passe
    if(empty($raisonSociale) && empty($nomCommercial)){
        continue;
    }
    //Gestion des quotes simple pour les insertions oracle

    $sql = "
        INSERT INTO EMPLOYEUR
            (ID, SIREN, RAISON_SOCIALE, NOM_COMMERCIAL, SOURCE_ID, HISTO_CREATEUR_ID, HISTO_CREATION, HISTO_MODIFICATEUR_ID,HISTO_MODIFICATION, IDENTIFIANT_ASSOCIATION)
        VALUES ($nextId, '$data[0]', '$raisonSociale', '$nomCommercial','1', 1, TO_DATE('$date', 'YYYY-MM-DD HH24:MI:SS'), 1, TO_DATE('$date' , 'YYYY-MM-DD HH24:MI:SS'), '$data[10]')
    ";
    $bdd->exec($sql);
    if($row%100 == 0 && $row != 0)
    {
        $bdd->commitTransaction();
        $bdd->beginTransaction();
        $c->println("Etat avancement insertion employeur : " . $row);
        $c->println("Debut import table employeur : " . $date);
        $c->println("-----------------------------------------");
    }
    $row++;

}

$c->println("Mise à jour de la table employeur");

