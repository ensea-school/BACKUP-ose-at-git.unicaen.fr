<?php

$fromMaster = true;

$osedir = $oa->getOseDir();
$bdd = $oa->getBdd();

$c->println("Mise à jour de la table employeur");

$file = Config::get('employeur', 'import-file');
$nbLigne = shell_exec('wc -l ' . $file);
$c->println("Nombre de ligne à traiter : " . $nbLigne);
$csvFile = fopen($file, r);
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
     * $data[8] = Nom sous lequel est connu l'entreprise du grand public (champs N°4 à 70 carac)
     * $data[9] = Date de dernier traitement de l'unité légale
     * $data[10] = Unité pouvant employer des personnes
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
    //Traitement du libellé de l'unité legale
    //Dénomination publique
    $denominationPublique = (!empty($data[5]))?$data[5]:'';
    $denominationPublique .= (!empty($data[6]))?' ' . $data[6]:'';
    $denominationPublique .= (!empty($data[7]))?' ' . $data[7]:'';
    //Raison sociale
    $raisonSociale = $data[4];
    //Nom propre entité
    $nomPropre = $data[2];
    //Nom usage entité au lieu du nom propre
    $nomUsage = $data[3];
    $libelle = '';
    if(!empty($denominationPublique)){
        $libelle = $denominationPublique;
    }elseif (!empty($raisonSociale)){
        $libelle = $raisonSociale;
    }elseif (!empty($nomUsage)){
        $libelle = $nomUsage;
    }elseif(!empty($nomPropre)){
        $libelle = $nomPropre;
    }
    //Pas de libellé correcte pour l'unité on passe
    if(empty($libelle)){
        continue;
    }
    //Gestion des quotes simple pour les insertions oracle
    $libelle = str_replace("'", "''", $libelle);

    $sql = "
        INSERT INTO EMPLOYEUR
            (ID,SIREN,LIBELLE, HISTO_CREATEUR_ID, HISTO_CREATION, HISTO_MODIFICATEUR_ID,HISTO_MODIFICATION)
        VALUES ($nextId, '$data[0]', '$libelle', 1, TO_DATE('$date', 'YYYY-MM-DD HH24:MI:SS'), 1, TO_DATE('$date' , 'YYYY-MM-DD HH24:MI:SS'))
    ";
    $bdd->exec($sql);
    if($row%50000 == 0 && $row != 0)
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

