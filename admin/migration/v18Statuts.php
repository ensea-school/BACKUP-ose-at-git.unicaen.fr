<?php


class v18Statuts extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des statuts de OSE 17 vers OSE 18";
    }


    public function utile(): bool
    {
        return $this->manager->hasOld('table', 'STATUT_INTERVENANT')
            && $this->manager->tableRealExists('SAVE_V18_STATUT')
            && $this->manager->tableRealExists('SAVE_V18_STATUT_PRIVILEGE')
            && $this->manager->tableRealExists('SAVE_V18_DOSSIER_AUTRE_STATUT')
            && $this->manager->tableRealExists('SAVE_V18_TA_STATUT')
            && $this->manager->tableRealExists('SAVE_V18_INTERVENANT')
            && $this->manager->tableRealExists('SAVE_V18_DOSSIER');
    }


    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c = $this->manager->getOseAdmin()->getConsole();

        try {
            $this->preMigrationStatuts();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        try {
            $this->preMigrationIntervenants();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        try {
            $this->preMigrationDossiers();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        try {
            $this->preMigrationTypePieceJointeStatuts();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        try {
            $this->preMigrationTypeInterventionStatuts();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        $ddl = $bdd->table()->get('TBL_WORKFLOW')['TBL_WORKFLOW'];
        if (!isset($ddl['columns']['STATUT_ID'])) {
            $c->msg('Vidage temporaire de WF_DEP_BLOQUANTE (dépendances du Workflow)');
            $bdd->exec('DELETE FROM wf_dep_bloquante');

            $c->msg('Suppression de la table TBL_WORKFLOW, qui sera recréée au nouveau format');
            $bdd->exec('DROP TABLE tbl_workflow CASCADE CONSTRAINTS');
        }

        $ddl = $bdd->table()->get('MODELE_CONTRAT')['MODELE_CONTRAT'];
        if (!isset($ddl['columns']['STATUT_ID'])) {
            $bdd->exec("ALTER TABLE modele_contrat RENAME COLUMN STATUT_INTERVENANT_ID TO STATUT_ID");
            $c->msg('Colonne MODELE_CONTRAT.STATUT_INTERVENANT_ID renommée en STATUT_ID');

            try {
                $bdd->exec("ALTER TABLE modele_contrat DROP CONSTRAINT MCT_STATUT_INTERVENANT_FK");
            } catch (\Exception $e) {
                // rien à faire : la contrainte a déjà du être supprimée
            }

            $res = $bdd->select("
            SELECT
              mc.id, MIN(s.id) statut_id
            FROM
              modele_contrat mc
              JOIN save_v18_statut old_s ON old_s.id = mc.statut_id
              JOIN statut s ON s.code = old_s.code AND s.histo_destruction IS NULL
            WHERE 
              mc.statut_id IS NOT NULL
            GROUP BY
              mc.id
            ");
            if (0 !== count($res)) {
                foreach ($res as $r) {
                    $bdd->exec("UPDATE modele_contrat SET statut_id = :statutid WHERE id = :id", [
                        'statutId' => (int)$r['STATUT_ID'],
                        'id'       => (int)$r['ID'],
                    ]);
                }
                $c->msg('Association entre les modèles de contrats et les statuts mise à jour');
            }
        }
    }


    public function after()
    {
        $bdd = $this->manager->getBdd();
        $c = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Mise à jour des libellés des champs complémentaires pour les statuts');

        $bdd->exec("UPDATE parametre SET valeur='Est ATV (oui ou non)' WHERE nom='statut_intervenant_codes_corresp_1_libelle'");
        $bdd->exec("UPDATE parametre SET valeur='Code Siham' WHERE nom='statut_intervenant_codes_corresp_2_libelle'");
        $bdd->exec("UPDATE parametre SET valeur='Est VA (oui ou non)' WHERE nom='statut_intervenant_codes_corresp_3_libelle'");

        $c->end('Libellés mis à jour');
    }


    public function preMigrationStatuts()
    {
        $bdd = $this->manager->getBdd();
        $c = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Mise à jour de la liste des statuts');

        /* Modifications préalables à faire en BDD */
        if (empty($bdd->sequence()->get('STATUT_ID_SEQ'))) {
            $bdd->exec('CREATE SEQUENCE STATUT_ID_SEQ INCREMENT BY 1 MINVALUE 1 NOCACHE');
            $c->msg('Nouvelle séquence STATUT_ID_SEQ ajoutée');
        }

        if (empty($bdd->table()->get('STATUT'))) {
            $bdd->exec('CREATE TABLE statut(  
              id number(*,0) NOT NULL ENABLE,
              code varchar2(50 CHAR) NOT NULL ENABLE,
              libelle varchar2(128 CHAR) NOT NULL ENABLE,
              type_intervenant_id number(*,0) NOT NULL ENABLE,
              annee_id number(*,0),
              ordre number(*,0) DEFAULT 9999 NOT NULL ENABLE,
              prioritaire_indicateurs number(1) DEFAULT 0 NOT NULL ENABLE,
              service_statutaire FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
              depassement_service_du_sans_hc number(1) DEFAULT 0 NOT NULL ENABLE,
              taux_charges_patronales FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
              dossier number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_selectionnable number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_identite_comp number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_contact number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_tel_perso number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_email_perso number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_adresse number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_banque number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_insee number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_employeur number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_autre_1 number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_autre_1_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_1_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_2 number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_autre_2_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_2_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_3 number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_autre_3_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_3_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_4 number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_autre_4_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_4_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_5 number(1) DEFAULT 0 NOT NULL ENABLE,
              dossier_autre_5_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              dossier_autre_5_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              pj_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              pj_telechargement number(1) DEFAULT 1 NOT NULL ENABLE,
              pj_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              pj_archivage number(1) DEFAULT 1 NOT NULL ENABLE,
              conseil_restreint number(1) DEFAULT 1 NOT NULL ENABLE,
              conseil_restreint_visu number(1) DEFAULT 1 NOT NULL ENABLE,
              conseil_restreint_duree_vie number DEFAULT 1 NOT NULL ENABLE,
              conseil_aca number(1) DEFAULT 1 NOT NULL ENABLE,
              conseil_aca_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              conseil_aca_duree_vie number DEFAULT 5 NOT NULL ENABLE,
              contrat number(1) DEFAULT 1 NOT NULL ENABLE,
              contrat_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              contrat_depot number(1) DEFAULT 1 NOT NULL ENABLE,
              service_prevu number(1) DEFAULT 1 NOT NULL ENABLE,
              service_prevu_visu number(1) DEFAULT 1 NOT NULL ENABLE,
              service_prevu_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              service_realise number(1) DEFAULT 1 NOT NULL ENABLE,
              service_realise_visu number(1) DEFAULT 1 NOT NULL ENABLE,
              service_realise_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              service_exterieur number(1) DEFAULT 0 NOT NULL ENABLE,
              referentiel_prevu number(1) DEFAULT 0 NOT NULL ENABLE,
              referentiel_prevu_visu number(1) DEFAULT 1 NOT NULL ENABLE,
              referentiel_prevu_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              cloture number(1) DEFAULT 0 NOT NULL ENABLE,
              modif_service_du number(1) DEFAULT 0 NOT NULL ENABLE,
              modif_service_du_visualisation number(1) DEFAULT 0 NOT NULL ENABLE,
              paiement_visualisation number(1) DEFAULT 1 NOT NULL ENABLE,
              motif_non_paiement number(1) DEFAULT 0 NOT NULL ENABLE,
              formule_visualisation number(1) DEFAULT 0 NOT NULL ENABLE,
              codes_corresp_1 varchar2(1000 CHAR),
              codes_corresp_2 varchar2(1000 CHAR),
              codes_corresp_3 varchar2(1000 CHAR),
              codes_corresp_4 varchar2(1000 CHAR),
              histo_creation DATE DEFAULT sysdate NOT NULL ENABLE,
              histo_createur_id number(*,0) NOT NULL ENABLE,
              histo_modification DATE DEFAULT sysdate NOT NULL ENABLE,
              histo_modificateur_id number(*,0),
              histo_destruction DATE,
              histo_destructeur_id number(*,0),
              referentiel_realise number(1) DEFAULT 0 NOT NULL ENABLE,
              referentiel_realise_edition number(1) DEFAULT 1 NOT NULL ENABLE,
              referentiel_realise_visu number(1) DEFAULT 1 NOT NULL ENABLE
            )');
            $c->msg('Nouvelle table STATUT créée');
        }

        /* Récup de tous les statuts */
        $res = $bdd->select('SELECT * FROM save_v18_statut');
        $statuts = [];
        foreach ($res as $r) {
            $r['privileges'] = [];
            $r['annees'] = [];
            $r['dossierAutres'] = [];
            $r['agrements'] = [];
            $r['new'] = [];
            $statuts[(int)$r['ID']] = $r;
        }

        /* Récup des privilèges associés */
        $res = $bdd->select("
        SELECT 
          sp.statut_id statut_id,
          cp.code || '-' || p.code privilege
        FROM 
          save_v18_statut_privilege sp
          JOIN privilege p ON p.id = sp.privilege_id
          JOIN categorie_privilege cp ON cp.id = p.categorie_id
        ");
        foreach ($res as $r) {
            $statuts[(int)$r['STATUT_ID']]['privileges'][] = $r['PRIVILEGE'];
        }

        /* Récup des utilisations de champs autres */
        $res = $bdd->select("SELECT * FROM save_v18_dossier_autre_statut");
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
          save_v18_ta_statut  tas
          JOIN type_agrement ta ON ta.id = tas.type_agrement_id
        WHERE 
          tas.histo_destruction IS NULL
        ");
        foreach ($res as $r) {
            $statuts[(int)$r['STATUT_ID']]['agrements'][$r['TYPE_AGREMENT']] = (int)$r['DUREE_VIE'];
        }

        /* Calcul des années utilisées */
        $res = $bdd->select("
        SELECT
          MAX(si.id) id, i.annee_id
        FROM
          save_v18_intervenant i
          JOIN save_v18_statut si ON si.id = i.statut_id
        GROUP BY
          si.code, i.annee_id
        ORDER BY
          i.annee_id
        ");
        foreach ($res as $r) {
            $statuts[(int)$r['ID']]['annees'][] = (int)$r['ANNEE_ID'];
        }

        /* Calcul des années */
        $toutesAnnees = [];
        for ($a = 2010; $a < 2100; $a++) {
            $toutesAnnees[] = $a;
        }

        foreach ($statuts as $id => $statut) {
            $code = $statut['CODE'];
            if ($code == 'AUTRES' || $code == 'NON_AUTORISE') {
                $statuts[$id]['annees'] = $toutesAnnees;
            } else {
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
        }
        $c->end('Récupération terminée');

        /* Calcul des nouveau statuts */
        $c->begin('Convertion des statuts au nouveau format');
        foreach ($statuts as $id => $statut) {
            $statuts[$id]['new'] = [
                //'ANNEE_ID'                        => , // NUMBER(*,0),
                'ORDRE'                          => $statut['ORDRE'], // NUMBER(*,0) DEFAULT 9999 NOT NULL ENABLE,
                'CODE'                           => $statut['CODE'], // VARCHAR2(50 CHAR) NOT NULL ENABLE,
                'LIBELLE'                        => $statut['LIBELLE'], // VARCHAR2(128 CHAR) NOT NULL ENABLE,
                'TYPE_INTERVENANT_ID'            => $statut['TYPE_INTERVENANT_ID'], // NUMBER(*,0) NOT NULL ENABLE,
                'PRIORITAIRE_INDICATEURS'        => 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'SERVICE_STATUTAIRE'             => $statut['SERVICE_STATUTAIRE'], // FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
                'DEPASSEMENT_SERVICE_DU_SANS_HC' => $statut['DEPASSEMENT_SERVICE_DU_SANS_HC'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'TAUX_CHARGES_PATRONALES'        => $statut['CHARGES_PATRONALES'], // FLOAT(126) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER'                        => $statut['PEUT_SAISIR_DOSSIER'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_VISUALISATION'          => in_array('dossier-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_EDITION'                => in_array('dossier-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_SELECTIONNABLE'         => $statut['PEUT_CHOISIR_DANS_DOSSIER'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_IDENTITE_COMP'          => $statut['DOSSIER_IDENTITE_COMP'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_CONTACT'                => $statut['DOSSIER_CONTACT'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_TEL_PERSO'              => $statut['DOSSIER_TEL_PERSO'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_EMAIL_PERSO'            => $statut['DOSSIER_EMAIL_PERSO'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_ADRESSE'                => $statut['DOSSIER_ADRESSE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_BANQUE'                 => $statut['DOSSIER_IBAN'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_INSEE'                  => $statut['DOSSIER_INSEE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_EMPLOYEUR'              => $statut['DOSSIER_EMPLOYEUR'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_1'                => in_array(1, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_1_VISUALISATION'  => in_array('dossier-champ-autre-1-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_1_EDITION'        => in_array('dossier-champ-autre-1-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_2'                => in_array(2, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_2_VISUALISATION'  => in_array('dossier-champ-autre-2-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_2_EDITION'        => in_array('dossier-champ-autre-2-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_3'                => in_array(3, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_3_VISUALISATION'  => in_array('dossier-champ-autre-3-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_3_EDITION'        => in_array('dossier-champ-autre-3-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_4'                => in_array(4, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_4_VISUALISATION'  => in_array('dossier-champ-autre-4-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_4_EDITION'        => in_array('dossier-champ-autre-4-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_5'                => in_array(5, $statut['dossierAutres']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'DOSSIER_AUTRE_5_VISUALISATION'  => in_array('dossier-champ-autre-5-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'DOSSIER_AUTRE_5_EDITION'        => in_array('dossier-champ-autre-5-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_VISUALISATION'               => in_array('piece-justificative-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_TELECHARGEMENT'              => in_array('piece-justificative-telechargement', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_EDITION'                     => in_array('piece-justificative-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'PJ_ARCHIVAGE'                   => in_array('piece-justificative-archivage', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_RESTREINT'              => isset($statut['agrements']['CONSEIL_RESTREINT']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_RESTREINT_VISU'         => in_array('agrement-conseil-restreint-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_RESTREINT_DUREE_VIE'    => $statut['agrements']['CONSEIL_RESTREINT'] ?? 1, // NUMBER DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_ACA'                    => isset($statut['agrements']['CONSEIL_ACADEMIQUE']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_ACA_VISUALISATION'      => in_array('agrement-conseil-academique-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONSEIL_ACA_DUREE_VIE'          => $statut['agrements']['CONSEIL_ACADEMIQUE'] ?? 5, // NUMBER DEFAULT 5 NOT NULL ENABLE,
                'CONTRAT'                        => $statut['PEUT_AVOIR_CONTRAT'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONTRAT_VISUALISATION'          => in_array('contrat-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'CONTRAT_DEPOT'                  => in_array('contrat-depot-retour-signe', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_PREVU'                  => $statut['PEUT_SAISIR_SERVICE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_PREVU_VISU'             => in_array('enseignement-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_PREVU_EDITION'          => in_array('enseignement-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_REALISE'                => $statut['PEUT_SAISIR_SERVICE'], // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_REALISE_VISU'           => in_array('enseignement-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_REALISE_EDITION'        => in_array('enseignement-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'SERVICE_EXTERIEUR'              => $statut['PEUT_SAISIR_SERVICE_EXT'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'REFERENTIEL_PREVU'              => $statut['PEUT_SAISIR_REFERENTIEL'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'REFERENTIEL_PREVU_VISU'         => in_array('referentiel-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'REFERENTIEL_PREVU_EDITION'      => in_array('referentiel-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'REFERENTIEL_REALISE'            => $statut['PEUT_SAISIR_REFERENTIEL'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'REFERENTIEL_REALISE_EDITION'    => in_array('referentiel-edition', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'REFERENTIEL_REALISE_VISU'       => in_array('referentiel-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE
                'CLOTURE'                        => $statut['PEUT_CLOTURER_SAISIE'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'MODIF_SERVICE_DU'               => in_array('modif-service-du-association', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'MODIF_SERVICE_DU_VISUALISATION' => in_array('modif-service-du-visualisation', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'PAIEMENT_VISUALISATION'         => in_array('mise-en-paiement-visualisation-intervenant', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
                'MOTIF_NON_PAIEMENT'             => $statut['PEUT_SAISIR_MOTIF_NON_PAIEMENT'], // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'FORMULE_VISUALISATION'          => in_array('intervenant-calcul-hetd', $statut['privileges']) ? 1 : 0, // NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
                'CODES_CORRESP_1'                => $statut['TEM_ATV'] == '1' ? 'Oui' : 'Non', // VARCHAR2(1000 CHAR),
                'CODES_CORRESP_2'                => $statut['CODE_RH']$statut['TEM_ATV'] == '1' ? 'Oui' : 'Non', // VARCHAR2(1000 CHAR),
                'CODES_CORRESP_3'                => $statut['TEM_VA'] == '1' ? 'Oui' : 'Non', // VARCHAR2(1000 CHAR),
                'CODES_CORRESP_4'                => null, // VARCHAR2(1000 CHAR),
                'HISTO_CREATION'                 => new \DateTime(), // DATE DEFAULT SYSDATE NOT NULL ENABLE,
                'HISTO_CREATEUR_ID'              => $this->manager->getOseAdmin()->getOseAppliId(), // NUMBER(*,0) NOT NULL ENABLE,
                'HISTO_MODIFICATION'             => new \DateTime(), // DATE DEFAULT SYSDATE NOT NULL ENABLE,
                'HISTO_MODIFICATEUR_ID'          => $this->manager->getOseAdmin()->getOseAppliId(), // NUMBER(*,0),
                'HISTO_DESTRUCTION'              => null, // DATE,
                'HISTO_DESTRUCTEUR_ID'           => null, // NUMBER(*,0),
            ];
        }


        /* Récupération des nouveaux statuts déjà créés */
        $res = $bdd->select("SELECT id, code, annee_id FROM statut WHERE histo_destruction IS NULL");
        $newStatuts = [];
        foreach ($res as $r) {
            $newStatuts[$r['CODE'] . '-' . $r['ANNEE_ID']] = (int)$r['ID'];
        }

        /* Insertion des nouveaux statuts */
        $count = count($statuts);
        $current = 0;
        foreach ($statuts as $id => $statut) {
            $current++;
            $c->msg('Convertion du statut "' . $statut['LIBELLE'] . '" (' . $current . '/' . $count . ') ...');
            $first = true;
            foreach ($statut['annees'] as $annee) {
                $new = $statut['new'];
                $new['ANNEE_ID'] = $annee;
                if (!$first) {
                    $new['HISTO_MODIFICATEUR_ID'] = null;
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
        $c = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Application des nouveaux statuts aux intervenants');

        $convInts = $bdd->select("
        SELECT
          i.id intervenant_id,
          osi.id old_statut_id,
          nsi.id new_statut_id
        FROM
          save_v18_intervenant i
          JOIN save_v18_statut osi ON osi.id = i.statut_id
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
            $bdd->exec("ALTER TABLE intervenant DROP CONSTRAINT INTERVENANT_STATUT_FK");
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
            $bdd->exec('UPDATE intervenant SET statut_id = :newstatutid WHERE id = :id', [
                'id'          => $r['INTERVENANT_ID'],
                'newStatutId' => $r['NEW_STATUT_ID'],
            ]);
        }
        $bdd->exec("alter trigger F_INTERVENANT enable");
        $bdd->exec("alter trigger F_INTERVENANT_S enable");
        $bdd->exec("alter trigger INTERVENANT_CK enable");
        $c->end('Intervenants mis à jour');
    }


    protected function preMigrationDossiers()
    {
        $bdd = $this->manager->getBdd();
        $c = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Application des nouveaux statuts aux données personnelles');

        $convInts = $bdd->select("
        SELECT
          d.id dossier_id,
          osi.id old_statut_id,
          nsi.id new_statut_id
        FROM
          save_v18_dossier d
          JOIN intervenant i ON i.id = d.intervenant_id
          JOIN save_v18_statut osi ON osi.id = d.statut_id
          LEFT JOIN statut nsi ON nsi.code = osi.code AND nsi.annee_id = i.annee_id
        WHERE
          osi.id <> COALESCE(nsi.id,0)
        ");
        foreach ($convInts as $r) {
            if (empty($r['NEW_STATUT_ID'])) {
                $c->printDie('ERREUR : certaines données personnelles ne pourront pas avoir de statut au nouveau format. Merci de contacter l\'équipe OSE Caen pour résoudre ce problème');
            }
        }
        try {
            $bdd->exec("ALTER TABLE intervenant_dossier DROP CONSTRAINT INT_DOSSIER_STATUT_FK");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }
        $current = 0;
        $count = count($convInts);
        foreach ($convInts as $r) {
            $current++;
            $c->msg("Traitement des données personnelles $current / $count", true);
            $bdd->exec('UPDATE intervenant_dossier SET statut_id = :newstatutid WHERE id = :id', [
                'id'          => $r['DOSSIER_ID'],
                'newStatutId' => $r['NEW_STATUT_ID'],
            ]);
        }
        $c->end('Données personnelles mises à jour');
    }


    protected function preMigrationTypePieceJointeStatuts()
    {
        $bdd = $this->manager->getBdd();
        $c = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Application des nouveaux statuts aux paramétrages de PJ');

        try {
            $bdd->exec("ALTER TABLE type_piece_jointe_statut DROP CONSTRAINT TPJS_STATUT_INTERVENANT_FK");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }

        /* Modifications au niveau de la table TPJS */
        $ddl = $bdd->table()->get('TYPE_PIECE_JOINTE_STATUT')['TYPE_PIECE_JOINTE_STATUT'];
        if (!isset($ddl['columns']['ANNEE_ID'])) {
            $bdd->exec('DELETE FROM type_piece_jointe_statut');
            $c->msg("Vidage de la table \"TYPE_PIECE_JOINTE_STATUT\" avant d'insérer les nouveaux paramètres");

            $bdd->exec("ALTER TABLE type_piece_jointe_statut ADD(ANNEE_ID NUMBER)");
        }
        if (!isset($ddl['columns']['NUM_REGLE'])) {
            $bdd->exec("ALTER TABLE type_piece_jointe_statut ADD(NUM_REGLE NUMBER DEFAULT 1 NOT NULL ENABLE)");
        }
        if (!isset($ddl['columns']['STATUT_ID'])) {
            $bdd->exec("ALTER TABLE type_piece_jointe_statut RENAME COLUMN STATUT_INTERVENANT_ID TO STATUT_ID");
        }
        if (!$ddl['columns']['HISTO_MODIFICATEUR_ID']['nullable']) {
            $bdd->exec("ALTER TABLE type_piece_jointe_statut MODIFY(HISTO_MODIFICATEUR_ID NULL)");
        }

        /* Récupération des anciennes données et insertion des nouvelles */
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
          CASE WHEN MIN(t.annee_id) OVER (PARTITION BY t.type_piece_jointe_id, t.statut_code, t.num_regle) = t.annee_id THEN ose_divers.get_ose_utilisateur_id ELSE NULL END histo_modificateur_id,
          sysdate histo_modification
        FROM
          (SELECT
            tpjs.type_piece_jointe_id,
            s.id statut_id,
            s.code statut_code,
            a.id annee_id,
            row_number() over (partition BY tpjs.type_piece_jointe_id, s.id ORDER BY tpjs.id) num_regle,
            tpjs.obligatoire,
            COALESCE(tpjs.seuil_hetd,0) seuil_hetd,
            tpjs.fc,
            tpjs.changement_rib,
            tpjs.duree_vie,
            tpjs.obligatoire_hnp
          FROM 
            save_v18_tpjs tpjs
            JOIN save_v18_statut si ON si.id = tpjs.statut_intervenant_id
            JOIN (SELECT MIN(annee_id) annee_debut_id, MAX(annee_id) annee_fin_id, code FROM statut GROUP BY code) sc ON sc.code = si.code
            JOIN annee a ON a.id BETWEEN greatest(COALESCE(tpjs.annee_debut_id,1950), sc.annee_debut_id) AND least(COALESCE(tpjs.annee_fin_id,2100), sc.annee_fin_id) 
            JOIN statut s ON s.code = si.code AND s.annee_id = a.id
          WHERE
            tpjs.histo_destruction IS NULL
          ) t
          LEFT JOIN type_piece_jointe_statut tpjs ON tpjs.type_piece_jointe_id = t.type_piece_jointe_id AND tpjs.statut_id = t.statut_id AND tpjs.num_regle = t.num_regle
        ORDER BY
          type_piece_jointe_id, statut_code, num_regle, annee_id
        ");

        $count = count($res);
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


    protected function preMigrationTypeInterventionStatuts()
    {
        $bdd = $this->manager->getBdd();
        $c = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Application des nouveaux statuts aux paramétrages des types d\'intervention');

        /* Modifications au niveau de la table TIS */
        $ddl = $bdd->table()->get('TYPE_INTERVENTION_STATUT')['TYPE_INTERVENTION_STATUT'];
        if (!isset($ddl['columns']['ANNEE_ID'])) {
            $bdd->exec('DELETE FROM type_intervention_statut');
            $c->msg("Vidage de la table \"TYPE_INTERVENTION_STATUT\" avant d'insérer les nouveaux paramètres");

            $bdd->exec("ALTER TABLE type_intervention_statut ADD(ANNEE_ID NUMBER)");
        }
        if (!isset($ddl['columns']['STATUT_ID'])) {
            $bdd->exec("ALTER TABLE type_intervention_statut RENAME COLUMN STATUT_INTERVENANT_ID TO STATUT_ID");
        }
        if (!isset($ddl['columns']['HISTO_CREATEUR_ID'])) {
            $bdd->exec("ALTER TABLE type_intervention_statut ADD(HISTO_CREATEUR_ID NUMBER(*,0))");
        }
        if (!isset($ddl['columns']['HISTO_CREATION'])) {
            $bdd->exec("ALTER TABLE type_intervention_statut ADD(HISTO_CREATION DATE)");
        }
        if (!isset($ddl['columns']['HISTO_MODIFICATEUR_ID'])) {
            $bdd->exec("ALTER TABLE type_intervention_statut ADD(HISTO_MODIFICATEUR_ID NUMBER(*,0))");
        }
        if (!isset($ddl['columns']['HISTO_MODIFICATION'])) {
            $bdd->exec("ALTER TABLE type_intervention_statut ADD(HISTO_MODIFICATION DATE)");
        }

        try {
            $bdd->exec("ALTER TABLE type_intervention_statut DROP CONSTRAINT TI_STATUT_STATUT_INT_FK");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }

        /* Récupération des anciennes données et insertion des nouvelles */
        $res = $bdd->select("
        SELECT
          ntis.id,
          tis.type_intervention_id,
          s.id statut_id,
          COALESCE(tis.taux_hetd_service,1) taux_hetd_service,
          COALESCE(tis.taux_hetd_complementaire,1) taux_hetd_complementaire,
          s.annee_id,
          ose_divers.get_ose_utilisateur_id histo_createur_id,
          sysdate histo_creation,
          ose_divers.get_ose_utilisateur_id histo_modificateur_id,
          sysdate histo_modification
        FROM
          save_v18_tis tis
          JOIN save_v18_statut si ON si.id = tis.statut_intervenant_id
          JOIN statut s ON s.code = si.code
          LEFT JOIN type_intervention_statut ntis ON ntis.type_intervention_id = tis.type_intervention_id AND ntis.statut_id = s.id
        ");

        $count = count($res);
        $current = 0;
        foreach ($res as $r) {
            $current++;
            $c->msg("Ajout du paramètre $current / $count ...", true);
            if (empty($r['ID'])) {
                $bdd->getTable('TYPE_INTERVENTION_STATUT')->insert($r);
            }
        }

        $c->end("Paramétrages des types d'intervention mis à jour");
    }
}