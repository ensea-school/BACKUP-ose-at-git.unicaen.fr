<?php
/*
SI validation du dossier alors on force complétude à 1 car de toute façon il est validé*
SINON on calcule la complétude du dossier en reprennant
 */





class MigrationDossier extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration des dossiers en V15";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'DOSSIER_CHAMP_AUTRE');
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    protected function before()
    {
        $this->manager->sauvegarderTable('DOSSIER', 'DOSSIER_SAVE');

        $bdd = $this->manager->getBdd();
        $bdd->exec("TRUNCATE TABLE TBL_DOSSIER"); // On supprime le contenu du de cette table pour éviter les erreurs de clés étrangères à la migration
    }



    protected function after()
    {
        $oa                 = $this->manager->getOseAdmin();
        $bdd                = $this->manager->getBdd();
        $console            = $this->manager->getOseAdmin()->getConsole();
        $nbDossierMigration = 0;

        //Get id pays FRANCE
        $sql      = "SELECT id FROM PAYS WHERE libelle = 'FRANCE'";
        $pays     = $bdd->select($sql);
        $france   = current($pays);
        $idFrance = $france['ID'];

        $sql = "
        SELECT
            * 
        FROM
            DOSSIER_SAVE d
        WHERE
            d.histo_destruction IS NULL
        ";

        $dossiers           = $bdd->select($sql);
        $nbDossierToMigrate = count($dossiers);

        $console->println("Nombre de dossier à migrer : " . count($dossiers));

        $mappingDossierToIntervenantDossier = [
            'CIVILITE_ID'              => 'CIVILITE_ID',
            'COMMUNE_NAISSANCE'        => 'VILLE_NAISSANCE',
            'DATE_NAISSANCE'           => 'DATE_NAISSANCE',
            'DEPARTEMENT_NAISSANCE_ID' => 'DEPT_NAISSANCE_ID',
            'EMAIL_PERSO'              => 'EMAIL_PERSO',
            'EMAIL_PRO'                => 'EMAIL',
            'ID'                       => 'ID',
            'INTERVENANT_ID'           => 'INTERVENANT_ID',
            'NOM_PATRONYMIQUE'         => 'NOM_PATRONYMIQUE',
            'NOM_USUEL'                => 'NOM_USUEL',
            'NUMERO_INSEE'             => 'NUMERO_INSEE',
            'NUMERO_INSEE_PROVISOIRE'  => 'NUMERO_INSEE_EST_PROVISOIRE',
            'PAYS_NAISSANCE_ID'        => 'PAYS_NAISSANCE_ID',
            'PRENOM'                   => 'PRENOM',
            'RIB_HORS_SEPA'            => 'RIB_HORS_SEPA',
            'STATUT_ID'                => 'STATUT_ID',
            'HISTO_CREATEUR_ID'        => 'HISTO_CREATEUR_ID',
            'HISTO_CREATION'           => 'HISTO_CREATION',
            'HISTO_MODIFICATEUR_ID'    => 'HISTO_MODIFICATEUR_ID',
            'HISTO_MODIFICATION'       => 'HISTO_MODIFICATION',
            'HISTO_DESTRUCTEUR_ID'     => 'HISTO_DESTRUCTEUR_ID',
            'HISTO_DESTRUCTION'        => 'HISTO_DESTRUCTION',
            'PAYS_NATIONALITE_ID'      => 'PAYS_NAISSANCE_ID',
            'BIC'                      => '',
            'IBAN'                     => '',
            'ADRESSE_CODE_POSTAL'      => '',
            'ADRESSE_COMMUNE'          => '',
            'ADRESSE_LIEU_DIT'         => '',
            'ADRESSE_NUMERO'           => '',
            'ADRESSE_NUMERO_COMPL_ID'  => '',
            'ADRESSE_PAYS_ID'          => '',
            'ADRESSE_PRECISIONS'       => '',
            'ADRESSE_VOIE'             => '',
            'ADRESSE_VOIRIE_ID'        => '',
            'COMPLETUDE'               => '',
        ];


        $datasIntervenantDossier = [];
        $console->begin("Début de migration des dossiers intervenants");
        foreach ($dossiers as $dossier) {
            $nbDossierMigration++;
            $console->msg('Migration du dossier ID Transfert ' . $dossier['ID'] . ' / ' . $nbDossierMigration . ' sur ' . $nbDossierToMigrate, true);
            //$console->println("Migration du dossier ID : " . $dossier['ID'], $console::COLOR_GREEN);
            $intervenantDossier = [];
            //On traite dans un premier ce qu'on a pu mapper entre ancien dossier et nouveau dossier
            foreach ($mappingDossierToIntervenantDossier as $newColumn => $oldColumn) {
                if (!empty($oldColumn)) {
                    $intervenantDossier[$newColumn] = $dossier[$oldColumn];
                }
            }
            //Traitement des nouvelles colonnes intervenant dossier
            //BIC et IBAN
            $rib = $dossier['RIB'];
            if (!empty($rib)) {
                $splitRib = explode('-', $rib);
                if (count($splitRib) > 1) {
                    $intervenantDossier['BIC'] = $splitRib [0];
                }
                $intervenantDossier['IBAN'] = $splitRib[1];
            }
            //On récupére les adresses
            $adresse = $dossier['ADRESSE'];
            if (!empty($adresse)) {
                //On sépare l'adresse pour récupérer le code postal et la ville
                if (preg_match("'(.*)([0-9]{5})(.*)'s", $adresse, $out)) {
                    $adressePrecisions = $out[1];
                    $adresseCodePostal = $out[2];
                    $commune           = explode(',', $out[3]);
                    $adresseCommune    = (!empty($commune[1])) ? $commune[1] : $out[3];
                } else {
                    $adressePrecisions = $adresse;
                    $adresseCodePostal = null;
                    $adresseCommune    = null;
                }

                $intervenantDossier['ADRESSE_PRECISIONS']  = $adressePrecisions;
                $intervenantDossier['ADRESSE_COMMUNE']     = $adresseCommune;
                $intervenantDossier['ADRESSE_CODE_POSTAL'] = $adresseCodePostal;
                $intervenantDossier['ADRESSE_PAYS_ID']     = $idFrance;
            }
            //On met par défault la complétude du dossier à 1
            $intervenantDossier['COMPLETUDE'] = 1;
            $datasIntervenantDossier[]        = $intervenantDossier;
        }

        //On ne delete pas
        $options['delete']       = false;
        $options['update']       = false;
        $tableIntervenantDossier = $bdd->getTable('INTERVENANT_DOSSIER');
        //On merge toutes les données
        $tableIntervenantDossier->merge($datasIntervenantDossier, 'ID', $options);
        $console->println("Nombre de dossiers migrés : " . $nbDossierMigration);
        //Mise à jour sequence
        $bdd->majSequences($this->manager->getRef());
        //Recalcule le tableau de bord des dossier
        $console->println("Calcul du tableau de bord TBL_DOSSIER");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'dossier\'); END;');
        $console->println("Terminé");
        $console->println("Fin de la migration des dossiers V15");
    }
}

