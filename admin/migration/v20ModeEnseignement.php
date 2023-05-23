<?php





class v20ModeEnseignement extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des modes de saisie (semestriel ou calendaire) au niveau des statuts";
    }



    public function utile(): bool
    {
        return true;

        return $this->manager->hasNewColumn('STATUT', 'MODE_REFERENTIEL_REALISE');
    }



    public function before()
    {


        $c   = $this->manager->getOseAdmin()->getConsole();
        $bdd = $this->manager->getBdd();

        $c->begin("Migration des modes de saisie sur le statut");
        //Sauvegarde de la table paramètre
        $this->manager->sauvegarderTable('PARAMETRE', 'SAVE_PARAMETRE');
        //On met à jour les statuts

    }



    public function after()
    {
        echo 'test';
        //On récupère le paramètrage par défaut des modes de saisi des enseignements
        $modes = $bdd->select("SELECT nom, valeur FROM SAVE_PARAMETRE WHERE nom IN ('modalite_services_prev_ens', 'modalite_services_real_ens')");

        foreach ($modes as $mode) {
            //On met à jour le mode previsionnel au niveau des statuts
            if ($mode['NOM'] = 'modalite_services_prev_ens') {
                $val = $mode['VALEUR'];
                $c->msg('Mise à jour du mode prévisionnel sur les statuts : ' . $val);
                $sqlUpdate = "UPDATE statut SET mode_service_previsionnel = '" . $val . "'";
                //        $bdd->exec($sqlUpdate);
            }
            //On met à jour le mode realiser au niveau des statuts
            if ($mode['NOM'] = 'modalite_services_prev_ens') {
                $val = $mode['VALEUR'];
                $c->msg('Mise à jour du mode prévisionnel sur les statuts : ' . $val);
                $sqlUpdate = "UPDATE statut SET mode_service_realise = '" . $val . "'";
                //          $bdd->exec($sqlUpdate);
            }
        }

        $c->end("Fin migration des modes de saisie sur le statut");
    }

    /* public function after()
     {
         $c   = $this->manager->getOseAdmin()->getConsole();
         $bdd = $this->manager->getBdd();

         $c->begin("Convertion des contrats de travail en états de sortie");

         $this->manager->sauvegarderTable('MODELE_CONTRAT', 'SAVE_MODELE_CONTRAT');
         // On supprime l'ancienne table afin de ne jamais recommencer la migration, puis on travaille sur la sauvegarde

         $bdd->table()->drop('MODELE_CONTRAT');

         $modeles = $bdd->select("SELECT * FROM SAVE_MODELE_CONTRAT");

         $etatsSortie = [];
         $statuts     = [];

         $sts = $bdd->select('select distinct code from statut WHERE histo_destruction IS NULL');
         foreach ($sts as $st) {
             $statuts[$st['CODE']] = null;
         }

         foreach ($modeles as $modele) {
             $id       = (int)$modele['ID'];
             $code     = 'CONTRAT_' . $id;
             $libelle  = 'Contrat de travail - ' . $modele['LIBELLE'];
             $statutId = $modele['STATUT_ID'] ? (int)$modele['STATUT_ID'] : null;
             $fichier  = $modele['FICHIER'];
             $requete  = $modele['REQUETE'] ?: 'SELECT * FROM v_contrat_main';

             if ($statutId) {
                 $statutCode           = $bdd->select("SELECT code FROM statut WHERE id = $statutId")[0]['CODE'];
                 $statuts[$statutCode] = $code;
             } else {
                 $statutCode = null;
                 foreach ($statuts as $scode => $sc) {
                     if (empty($sc)) {
                         $statuts[$scode] = $code;
                     }
                 }
             }

             $etatSortie = [
                 'CODE'           => $code,
                 'LIBELLE'        => $libelle,
                 'FICHIER'        => $fichier,
                 'REQUETE'        => $requete,
                 'PDF_TRAITEMENT' => '$mainData    = reset($data);
 $data        = [];
 $exemplaires = [];

 for ($i = 1; $i <= 3; $i++) {
     $exemplaire = $mainData[\'exemplaire\' . $i] ?? \'0\';
     if ($exemplaire !== \'0\') {
         $exemplaires[$i] = $exemplaire;
     }
     unset($mainData[\'exemplaire\' . $i]);
 }

 foreach ($exemplaires as $exemplaire) {
     $newExemplaire               = $mainData;
     $newExemplaire[\'exemplaire\'] = $exemplaire;
     $data[]                      = $newExemplaire;
 }

 return $data;',
                 'CLE'            => 'CONTRAT_ID',
                 'BLOC1_NOM'      => 'serviceCode',
                 'BLOC1_ZONE'     => 'table:table-row',
                 'BLOC1_REQUETE'  => 'SELECT * FROM V_CONTRAT_SERVICES',
             ];

             $etatsSortie[] = $etatSortie;
         }

         $c->msg("Création des nouveaux états");
         foreach ($etatsSortie as $etatSortie) {
             $bdd->getTable('ETAT_SORTIE')->insert($etatSortie);
             foreach ($statuts as $scode => $setat) {
                 if ($setat === $etatSortie['CODE']) {
                     $statuts[$scode] = $etatSortie['ID'];
                 }
             }
         }

         $c->msg("Configuration des statuts");
         foreach ($statuts as $scode => $modeleId) {
             $bdd->exec("UPDATE STATUT SET contrat_etat_sortie_id = $modeleId WHERE CODE = :code", ['code' => $scode]);
         }

         $c->end("Convertion terminée");
     }*/
}