<?php





class v18 extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration de OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return $this->manager->hasOld('table', 'STATUT_INTERVENANT')
            && $this->manager->hasNew('table', 'STATUT');
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    public function before()
    {
        $this->preMigrationIndicateurs();
        $this->preMigrationStatuts();
        $this->preMigrationIntervenants();
        $this->preMigrationTypePieceJointeStatuts();
    }



    protected function after()
    {
        echo 'youyoud';
    }



    public function preMigrationIndicateurs()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Préparation à la mise à jour des indicateurs');
        if (empty($bdd->table()->get('TYPE_INDICATEUR'))) {
            $bdd->exec('ALTER TABLE INDICATEUR ADD (TYPE_INDICATEUR_ID NUMBER)');
            $bdd->exec('CREATE TABLE TYPE_INDICATEUR (  
              ID NUMBER NOT NULL ENABLE,
              LIBELLE VARCHAR2(60 CHAR) NOT NULL ENABLE,
              ORDRE NUMBER DEFAULT 1 NOT NULL ENABLE
            )');
            $bdd->exec('INSERT INTO TYPE_INDICATEUR (ID, LIBELLE, ORDRE) VALUES (1,\'provisoire\', 1)');
            $bdd->exec('UPDATE INDICATEUR SET TYPE_INDICATEUR_ID = 1');
        }
        $c->end('Préparation à la migration des indicateurs terminée');
    }



    public function preMigrationStatuts()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        /* Modifications préalables à faire en BDD */
        if (empty($bdd->sequence()->get('STATUT_ID_SEQ'))) {
            $bdd->exec('CREATE SEQUENCE STATUT_ID_SEQ INCREMENT BY 1 MINVALUE 1 NOCACHE');
        }

        if (empty($bdd->table()->get('STATUT'))) {
            $bdd->exec('CREATE TABLE STATUT(  
              ID NUMBER(*,0) NOT NULL ENABLE,
              CODE VARCHAR2(50 CHAR) NOT NULL ENABLE,
              LIBELLE VARCHAR2(128 CHAR) NOT NULL ENABLE,
              TYPE_INTERVENANT_ID NUMBER(*,0) NOT NULL ENABLE,
              ANNEE_ID NUMBER(*,0),
              ORDRE NUMBER(*,0) DEFAULT 9999 NOT NULL ENABLE,
              PRIORITAIRE_INDICATEURS NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              SERVICE_STATUTAIRE FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
              DEPASSEMENT_SERVICE_DU_SANS_HC NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              TAUX_CHARGES_PATRONALES FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_SELECTIONNABLE NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_IDENTITE_COMP NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_CONTACT NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_TEL_PERSO NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_EMAIL_PERSO NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_ADRESSE NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_BANQUE NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_INSEE NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_EMPLOYEUR NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_AUTRE_1 NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_AUTRE_1_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_1_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_2 NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_AUTRE_2_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_2_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_3 NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_AUTRE_3_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_3_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_4 NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_AUTRE_4_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_4_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_5 NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              DOSSIER_AUTRE_5_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              DOSSIER_AUTRE_5_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              PJ_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              PJ_TELECHARGEMENT NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              PJ_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              PJ_ARCHIVAGE NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONSEIL_RESTREINT NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONSEIL_RESTREINT_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONSEIL_RESTREINT_DUREE_VIE NUMBER DEFAULT 1 NOT NULL ENABLE,
              CONSEIL_ACA NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONSEIL_ACA_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONSEIL_ACA_DUREE_VIE NUMBER DEFAULT 5 NOT NULL ENABLE,
              CONTRAT NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONTRAT_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CONTRAT_DEPOT NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_PREVU NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_PREVU_VISU NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_PREVU_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_REALISE NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_REALISE_VISU NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_REALISE_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              SERVICE_EXTERIEUR NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              REFERENTIEL_PREVU NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              REFERENTIEL_PREVU_VISU NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              REFERENTIEL_PREVU_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              CLOTURE NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              MODIF_SERVICE_DU NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              MODIF_SERVICE_DU_VISUALISATION NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              PAIEMENT_VISUALISATION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              MOTIF_NON_PAIEMENT NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              FORMULE_VISUALISATION NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              CODES_CORRESP_1 VARCHAR2(1000 CHAR),
              CODES_CORRESP_2 VARCHAR2(1000 CHAR),
              CODES_CORRESP_3 VARCHAR2(1000 CHAR),
              CODES_CORRESP_4 VARCHAR2(1000 CHAR),
              HISTO_CREATION DATE DEFAULT SYSDATE NOT NULL ENABLE,
              HISTO_CREATEUR_ID NUMBER(*,0) NOT NULL ENABLE,
              HISTO_MODIFICATION DATE DEFAULT SYSDATE NOT NULL ENABLE,
              HISTO_MODIFICATEUR_ID NUMBER(*,0),
              HISTO_DESTRUCTION DATE,
              HISTO_DESTRUCTEUR_ID NUMBER(*,0),
              REFERENTIEL_REALISE NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
              REFERENTIEL_REALISE_EDITION NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
              REFERENTIEL_REALISE_VISU NUMBER(1) DEFAULT 1 NOT NULL ENABLE
            )');
        }

        /* Récup de tous les statuts */
        $c->begin('Récupération des anciennes données liées aux statuts des intervenants');
        $res     = $bdd->select('SELECT * FROM statut_intervenant');
        $statuts = [];
        foreach ($res as $r) {
            $r['privileges']        = [];
            $r['annees']            = [];
            $r['dossierAutres']     = [];
            $r['agrements']         = [];
            $r['new']               = [];
            $statuts[(int)$r['ID']] = $r;
        }

        /* Récup des privilèges associés */
        $res = $bdd->select("
        SELECT 
          sp.statut_id statut_id,
          cp.code || '-' || p.code privilege
        FROM 
          statut_privilege sp
          JOIN privilege p ON p.id = sp.privilege_id
          JOIN categorie_privilege cp ON cp.id = p.categorie_id
        ");
        foreach ($res as $r) {
            $statuts[(int)$r['STATUT_ID']]['privileges'][] = $r['PRIVILEGE'];
        }

        /* Récup des utilisations de champs autres */
        $res = $bdd->select("select * from dossier_champ_autre_par_statut");
        foreach ($res as $r) {
            $statuts[(int)$r['STATUT_ID']]['dossierAutres'][] = (int)$r['DOSSIER_CHAMP_AUTRE_ID'];
        }

        /* Récup des données d'agréments */
        $res = $bdd->select("
        SELECT
          ta.code type_agrement, 
          tas.statut_intervenant_id statut_id,
          COALESCE(tas.duree_vie,99) duree_vie
        FROM 
          type_agrement_statut  tas
          JOIN type_agrement ta ON ta.id = tas.type_agrement_id
        WHERE 
          tas.histo_destruction is null
        ");
        foreach ($res as $r) {
            $statuts[(int)$r['STATUT_ID']]['agrements'][$r['TYPE_AGREMENT']] = (int)$r['DUREE_VIE'];
        }

        /* Calcul des années utilisées */
        $res = $bdd->select("
        SELECT
          max(si.id) id, i.annee_id
        FROM
          intervenant i
          JOIN statut_intervenant si ON si.id = i.statut_id
        GROUP BY
          si.code, i.annee_id
        ORDER BY
          i.annee_id
        ");
        foreach ($res as $r) {
            $statuts[(int)$r['ID']]['annees'][] = (int)$r['ANNEE_ID'];
        }

        /* Calcul des années */
        foreach ($statuts as $id => $statut) {
            if (empty($statut['HISTO_DESTRUCTION'])) {
                if (empty($statut['annees'])) {
                    $maxAnnee = 2020;
                } else {
                    $maxAnnee = max($statut['annees']);
                }
                for ($a = $maxAnnee + 1; $a < 2100; $a++) {
                    $statuts[$id]['annees'][] = $a;
                }
            } else {
                if (empty($statut['annees'])) {
                    unset($statuts[$id]); // on supprime les statuts supprimés n'ayant jamais servi
                }
            }
        }
        $c->end('Récupération terminée');

        /* Calcul des nouveau statuts */
        $c->begin('Convertion des statuts au nouveau format');
        foreach ($statuts as $id => $statut) {
            $statuts[$id]['new'] = [
                //'ANNEE_ID'                        => , // NUMBER(*,0),
                'ORDRE'                           => $statut['ORDRE'], // NUMBER(*,0) DEFAULT 9999 NOT NULL ENABLE,
                'CODE'                            => $statut['CODE'], // VARCHAR2(50 CHAR) NOT NULL ENABLE,
                'LIBELLE'                         => $statut['LIBELLE'], // VARCHAR2(128 CHAR) NOT NULL ENABLE,
                'TYPE_INTERVENANT_ID'             => $statut['TYPE_INTERVENANT_ID'], // NUMBER(*,0) NOT NULL ENABLE,
                'PRIORITAIRE_INDICATEURS'         => 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'SERVICE_STATUTAIRE'              => $statut['SERVICE_STATUTAIRE'], // FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
                'DEPASSEMENT_SERVICE_DU_SANS_HC'  => $statut['DEPASSEMENT_SERVICE_DU_SANS_HC'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'TAUX_CHARGES_PATRONALES'         => $statut['CHARGES_PATRONALES'], // FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER'                         => $statut['PEUT_SAISIR_DOSSIER'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_VISUALISATION'           => in_array('dossier-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_EDITION'                 => in_array('dossier-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_SELECTIONNABLE'          => $statut['PEUT_CHOISIR_DANS_DOSSIER'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_IDENTITE_COMP'           => $statut['DOSSIER_IDENTITE_COMP'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_CONTACT'                 => $statut['DOSSIER_CONTACT'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_TEL_PERSO'               => $statut['DOSSIER_TEL_PERSO'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_EMAIL_PERSO'             => $statut['DOSSIER_EMAIL_PERSO'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_ADRESSE'                 => $statut['DOSSIER_ADRESSE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_BANQUE'                  => $statut['DOSSIER_IBAN'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_INSEE'                   => $statut['DOSSIER_INSEE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_EMPLOYEUR'               => $statut['DOSSIER_EMPLOYEUR'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_1'                 => in_array(1, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_1_VISUALISATION'   => in_array('dossier-champ-autre-1-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_1_EDITION'         => in_array('dossier-champ-autre-1-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_2'                 => in_array(2, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_2_VISUALISATION'   => in_array('dossier-champ-autre-2-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_2_EDITION'         => in_array('dossier-champ-autre-2-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_3'                 => in_array(3, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_3_VISUALISATION'   => in_array('dossier-champ-autre-3-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_3_EDITION'         => in_array('dossier-champ-autre-3-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_4'                 => in_array(4, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_4_VISUALISATION'   => in_array('dossier-champ-autre-4-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_4_EDITION'         => in_array('dossier-champ-autre-4-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_5'                 => in_array(5, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_5_VISUALISATION'   => in_array('dossier-champ-autre-5-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_5_EDITION'         => in_array('dossier-champ-autre-5-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_VISUALISATION'                => in_array('piece-justificative-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_TELECHARGEMENT'               => in_array('piece-justificative-telechargement', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_EDITION'                      => in_array('piece-justificative-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_ARCHIVAGE'                    => in_array('piece-justificative-archivage', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_RESTREINT'               => isset($statut['agrements']['CONSEIL_RESTREINT']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_RESTREINT_VISUALISATION' => in_array('agrement-conseil-restreint-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_RESTREINT_DUREE_VIE'     => $statut['agrements']['CONSEIL_RESTREINT'] ?? 1, // NUMBER DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_ACA'                     => isset($statut['agrements']['CONSEIL_ACADEMIQUE']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_ACA_VISUALISATION'       => in_array('agrement-conseil-academique-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_ACA_DUREE_VIE'           => $statut['agrements']['CONSEIL_RESTREINT'] ?? 5, // NUMBER DEFAULT 5 NOT NULL ENABLE,
                'CONTRAT'                         => $statut['PEUT_AVOIR_CONTRAT'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONTRAT_VISUALISATION'           => in_array('contrat-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONTRAT_DEPOT'                   => in_array('contrat-depot-retour-signe', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_PREVU'                   => $statut['PEUT_SAISIR_SERVICE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_PREVU_VISU'              => in_array('enseignement-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_PREVU_EDITION'           => in_array('enseignement-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_REALISE'                 => $statut['PEUT_SAISIR_SERVICE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_REALISE_VISU'            => in_array('enseignement-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_REALISE_EDITION'         => in_array('enseignement-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_EXTERIEUR'               => $statut['PEUT_SAISIR_SERVICE_EXT'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'REFERENTIEL_PREVU'               => $statut['PEUT_SAISIR_REFERENTIEL'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'REFERENTIEL_PREVU_VISU'          => in_array('referentiel-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'REFERENTIEL_PREVU_EDITION'       => in_array('referentiel-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'REFERENTIEL_REALISE'             => $statut['PEUT_SAISIR_REFERENTIEL'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'REFERENTIEL_REALISE_EDITION'     => in_array('referentiel-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'REFERENTIEL_REALISE_VISU'        => in_array('referentiel-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE
                'CLOTURE'                         => $statut['PEUT_CLOTURER_SAISIE'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'MODIF_SERVICE_DU'                => in_array('modif-service-du-association', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'MODIF_SERVICE_DU_VISUALISATION'  => in_array('modif-service-du-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'PAIEMENT_VISUALISATION'          => in_array('mise-en-paiement-visualisation-intervenant', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'MOTIF_NON_PAIEMENT'              => $statut['PEUT_SAISIR_MOTIF_NON_PAIEMENT'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'FORMULE_VISUALISATION'           => in_array('intervenant-calcul-hetd', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'CODES_CORRESP_1'                 => $statut['CODE_RH'], // VARCHAR2(1000 CHAR),
                'CODES_CORRESP_2'                 => $statut['TEM_ATV'] == '1' ? 'Oui' : 'Non', // VARCHAR2(1000 CHAR),
                'CODES_CORRESP_3'                 => $statut['TEM_VA'] == '1' ? 'Oui' : 'Non', // VARCHAR2(1000 CHAR),
                'CODES_CORRESP_4'                 => null, // VARCHAR2(1000 CHAR),
                'HISTO_CREATION'                  => new \DateTime(), // DATE DEFAULT SYSDATE NOT NULL ENABLE,
                'HISTO_CREATEUR_ID'               => $this->manager->getOseAdmin()->getOseAppliId(), // NUMBER(*,0) NOT NULL ENABLE,
                'HISTO_MODIFICATION'              => new \DateTime(), // DATE DEFAULT SYSDATE NOT NULL ENABLE,
                'HISTO_MODIFICATEUR_ID'           => $this->manager->getOseAdmin()->getOseAppliId(), // NUMBER(*,0),
                'HISTO_DESTRUCTION'               => null, // DATE,
                'HISTO_DESTRUCTEUR_ID'            => null, // NUMBER(*,0),
            ];
        }


        /* Récupération des nouveaux statuts déjà créés */
        $res        = $bdd->select("select id, code, annee_id from statut WHERE histo_destruction IS NULL");
        $newStatuts = [];
        foreach ($res as $r) {
            $newStatuts[$r['CODE'] . '-' . $r['ANNEE_ID']] = (int)$r['ID'];
        }

        /* Insertion des nouveaux statuts */
        $count   = count($statuts);
        $current = 0;
        foreach ($statuts as $id => $statut) {
            $current++;
            $c->msg('Convertion du statut "' . $statut['LIBELLE'] . '" (' . $current . '/' . $count . ') ...');
            $first = true;
            foreach ($statut['annees'] as $annee) {
                $new             = $statut['new'];
                $new['ANNEE_ID'] = $annee;
                if (!$first) {
                    $new['HISTO_MODIFICATION'] = null;
                }
                if (!isset($newStatuts[$new['CODE'] . '-' . $new['ANNEE_ID']])) {
                    $bdd->getTable('STATUT')->insert($new);
                }
                $first = false;
            }
        }
        $c->end('Convertion des status terminée');

        /* Application des nouveaux statuts aux ... ... ... */
        // var_dump($statuts);

        //$bdd->exec('ALTER TABLE MODELE_CONTRAT RENAME COLUMN STATUT_INTERVENANT_ID TO STATUT_ID');
    }



    protected function preMigrationIntervenants()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Application des nouveaux statuts aux intervenants');

        $convInts = $bdd->select("
        SELECT
          i.id intervenant_id,
          osi.id old_statut_id,
          nsi.id new_statut_id
        FROM
          intervenant i
          JOIN statut_intervenant osi ON osi.id = i.statut_id
          LEFT JOIN statut nsi ON nsi.code = osi.code AND nsi.annee_id = i.annee_id
        WHERE
          osi.id <> COALESCE(nsi.id,0)
        ");
        foreach ($convInts as $r) {
            if (empty($r['NEW_STATUT_ID'])) {
                $c->printDie('ERREUR : certains intervenants ne pourront pas avoir de statut au nouveau format. Merci de contacter l\'équipe OSE Caen pour résoudre ce problème');
            }
        }
        try {
            $bdd->exec("ALTER TABLE INTERVENANT DROP CONSTRAINT INTERVENANT_STATUT_FK");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }
        $current = 0;
        $bdd->exec("alter trigger F_INTERVENANT disable");
        $bdd->exec("alter trigger F_INTERVENANT_S disable");
        $bdd->exec("alter trigger INTERVENANT_CK disable");
        $count = count($convInts);
        foreach ($convInts as $r) {
            $current++;
            $c->msg("Traitement de l'intervenant $current / $count", true);
            $bdd->exec('UPDATE INTERVENANT SET STATUT_ID = :newStatutId WHERE ID = :id', [
                'id'          => $r['INTERVENANT_ID'],
                'newStatutId' => $r['NEW_STATUT_ID'],
            ]);
        }
        $bdd->exec("alter trigger F_INTERVENANT enable");
        $bdd->exec("alter trigger F_INTERVENANT_S enable");
        $bdd->exec("alter trigger INTERVENANT_CK enable");
        $c->end('Intervenants mis à jour');
    }



    protected function preMigrationTypePieceJointeStatuts()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Application des nouveaux statuts aux paramétrages de PJ');

        if (empty($bdd->table()->get('SAVE_V18_TPJS'))) {
            $this->manager->sauvegarderTable('TYPE_PIECE_JOINTE_STATUT', 'SAVE_V18_TPJS');
            $c->msg('Anciens paramètres "TYPE_PIECE_JOINTE_STATUT" sauvegardés dans "SAVE_V18_TPJS".');

            $bdd->exec('DELETE FROM TYPE_PIECE_JOINTE_STATUT');
            $c->msg("Vidage de la table \"TYPE_PIECE_JOINTE_STATUT\" avant d'insérer les nouveaux paramètres");
        }

        /* Modifications au niveau de la table TPJS */
        $ddl = $bdd->table()->get('TYPE_PIECE_JOINTE_STATUT')['TYPE_PIECE_JOINTE_STATUT'];
        if (!isset($ddl['columns']['ANNEE_ID'])) {
            $bdd->exec("ALTER TABLE TYPE_PIECE_JOINTE_STATUT ADD(ANNEE_ID NUMBER)");
        }
        if (!isset($ddl['columns']['NUM_REGLE'])) {
            $bdd->exec("ALTER TABLE TYPE_PIECE_JOINTE_STATUT ADD(NUM_REGLE NUMBER default 1 NOT null ENABLE)");
        }
        if (!isset($ddl['columns']['STATUT_ID'])) {
            $bdd->exec("ALTER TABLE TYPE_PIECE_JOINTE_STATUT RENAME COLUMN STATUT_INTERVENANT_ID TO STATUT_ID");
        }
        if (!$ddl['columns']['HISTO_MODIFICATEUR_ID']['nullable']) {
            $bdd->exec("ALTER TABLE TYPE_PIECE_JOINTE_STATUT MODIFY(HISTO_MODIFICATEUR_ID null)");
        }

        /* Récupération des anciennes données */
        $res = $bdd->select("SELECT
          tpjs.id,
          t.type_piece_jointe_id,
          t.statut_id,
          t.annee_id,
          t.num_regle,
          t.obligatoire,
          t.seuil_hetd,
          t.fc, t.changement_rib,
          t.duree_vie,
          t.obligatoire_hnp,
          ose_divers.get_ose_utilisateur_id histo_createur_id,
          sysdate histo_creation,
          CASE WHEN MIN(t.annee_id) OVER (partition by t.type_piece_jointe_id, t.statut_code, t.num_regle) = t.annee_id THEN ose_divers.get_ose_utilisateur_id ELSE NULL END histo_modificateur_id,
          sysdate histo_modification
        FROM
          (SELECT
            tpjs.type_piece_jointe_id,
            s.id statut_id,
            s.code statut_code,
            a.id annee_id,
            ROW_NUMBER() OVER (PARTITION BY tpjs.type_piece_jointe_id, s.id ORDER BY tpjs.id) num_regle,
            tpjs.obligatoire,
            COALESCE(tpjs.seuil_hetd,0) seuil_hetd,
            tpjs.fc,
            tpjs.changement_rib,
            tpjs.duree_vie,
            tpjs.obligatoire_hnp
          FROM 
            SAVE_V18_TPJS tpjs
            JOIN statut_intervenant si ON si.id = tpjs.statut_intervenant_id
            JOIN (SELECT min(annee_id) annee_debut_id, max(annee_id) annee_fin_id, code FROM statut GROUP BY code) sc ON sc.code = si.code
            JOIN annee a ON a.id BETWEEN GREATEST(COALESCE(tpjs.annee_debut_id,1950), sc.annee_debut_id) AND LEAST(COALESCE(tpjs.annee_fin_id,2100), sc.annee_fin_id) 
            JOIN statut s ON s.code = si.code AND s.annee_id = a.id
          WHERE
            tpjs.histo_destruction IS NULL
          ) t
          LEFT JOIN type_piece_jointe_statut tpjs ON tpjs.type_piece_jointe_id = t.type_piece_jointe_id AND tpjs.statut_id = t.statut_id AND tpjs.num_regle = t.num_regle
        ORDER BY
          type_piece_jointe_id, statut_code, num_regle, annee_id
        ");

        $count   = count($res);
        $current = 0;
        foreach ($res as $r) {
            $current++;
            $c->msg("Ajout du paramètre $current / $count ...", true);
            if (empty($r['ID'])) {
                $bdd->getTable('TYPE_PIECE_JOINTE_STATUT')->insert($r);
            }
        }


        $c->end("Paramétrages de PJ mis à jour");
    }
}