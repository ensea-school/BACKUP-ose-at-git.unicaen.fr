<?php





class v18Plafonds extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des plafonds de OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'PLAFOND_PERIMETRE') || $this->manager->hasTable('SAVE_V18_PLAFOND');
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        if (!empty($bdd->table()->get('PLAFOND'))) {
            $bdd->exec('DROP TABLE PLAFOND CASCADE CONSTRAINTS');
            $c->msg('Suppression des anciens plafonds');
        }
    }



    public function after()
    {
        $c = $this->manager->getOseAdmin()->getConsole();
        try {
            $this->migrationParamsStructure();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        try {
            $this->migrationParamsReferentiel();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }

        try {
            $this->migrationParamsStatut();
        } catch (\Exception $e) {
            $c->println($e->getMessage(), $c::COLOR_RED);
        }
    }



    public function preMigrationIndicateurs()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Préparation à la mise à jour des indicateurs');

        $c->end('Préparation à la migration des indicateurs terminée');
    }



    public function migrationParamsStructure()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $sql = "
        SELECT
          s.id entite_id,
          p.id plafond_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          s.plafond_referentiel heures,
          pa.annee_debut_id annee_id,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_structure s
          JOIN plafond p ON p.numero = 15
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
        WHERE
          s.histo_destruction IS NULL
          AND s.plafond_referentiel IS NOT NULL
          AND pa.plafond_etat_id > 1
        ";


        $data = [];
        $qr   = $bdd->select($sql);
        foreach ($qr as $r) {
            $anneeDebut = (int)$r['ANNEE_ID'];
            $anneeFin   = (int)$r['ANNEE_FIN_ID'];
            if ($anneeFin == 0) $anneeFin = 2099;

            $plafondId = (int)$r['PLAFOND_ID'];
            $entiteId  = (int)$r['ENTITE_ID'];

            $etatPrevuId   = $r['PLAFOND_ETAT_PREVU_ID'] ? (int)$r['PLAFOND_ETAT_PREVU_ID'] : null;
            $etatRealiseId = $r['PLAFOND_ETAT_REALISE_ID'] ? (int)$r['PLAFOND_ETAT_REALISE_ID'] : null;
            $heures        = $r['HEURES'] ? (int)$r['HEURES'] : 0;

            for ($a = $anneeDebut; $a <= $anneeFin; $a++) {
                if (!isset($data[$a][$plafondId][$entiteId])) {
                    $data[$a][$plafondId][$entiteId] = [
                        'saisie'                  => $a == $anneeDebut,
                        'PLAFOND_ETAT_PREVU_ID'   => 1, // désactivé
                        'PLAFOND_ETAT_REALISE_ID' => 1, // désactivé
                        'HEURES'                  => 0,
                    ];
                }

                if ($etatPrevuId !== null) {
                    $data[$a][$plafondId][$entiteId]['PLAFOND_ETAT_PREVU_ID'] = $etatPrevuId;
                }

                if ($etatRealiseId !== null) {
                    $data[$a][$plafondId][$entiteId]['PLAFOND_ETAT_REALISE_ID'] = $etatRealiseId;
                }

                if ($heures !== null) {
                    $data[$a][$plafondId][$entiteId]['HEURES'] = $heures;
                }
            }
        }

        /* On supprime ce qui a déjà été créé */
        $sql = "SELECT * FROM PLAFOND_STRUCTURE WHERE histo_destruction IS NULL";
        $des = $bdd->select($sql);
        foreach ($des as $de) {
            $a = (int)$de['ANNEE_ID'];
            $p = (int)$de['PLAFOND_ID'];
            $e = (int)$de['STRUCTURE_ID'];
            unset($data[$a][$p][$e]);
        }

        $inserts = [];
        foreach ($data as $anneeId => $data2) {
            foreach ($data2 as $plafondId => $data3) {
                foreach ($data3 as $entiteId => $d) {
                    $insert                          = $d;
                    $insert['ANNEE_ID']              = $anneeId;
                    $insert['PLAFOND_ID']            = $plafondId;
                    $insert['ENTITE_ID']             = $entiteId;
                    $insert['HISTO_CREATION']        = new \DateTime();
                    $insert['HISTO_CREATEUR_ID']     = $this->manager->getOseAdmin()->getOseAppliId();
                    $insert['HISTO_MODIFICATION']    = new \DateTime();
                    $insert['HISTO_MODIFICATEUR_ID'] = $d['saisie'] ? $this->manager->getOseAdmin()->getOseAppliId() : null;
                    unset($insert['saisie']);
                    $inserts[] = $insert;
                }
            }
        }


        $c->begin('Convertion des paramètres de plafonds pour les structures');
        $count = count($inserts);
        foreach ($inserts as $current => $insert) {
            $insert['STRUCTURE_ID'] = $insert['ENTITE_ID'];
            unset($insert['ENTITE_ID']);
            $c->msg('Ajout du paramètre ' . ($current + 1) . ' sur ' . $count . ' ...', true);
            $bdd->getTable('PLAFOND_STRUCTURE')->insert($insert);
        }
        $c->end('Fin de la convertion des paramètres de plafonds pour les structures');
    }



    public function migrationParamsReferentiel()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $sql  = "
        SELECT
          r.id entite_id,
          p.id plafond_id,
          pa.annee_debut_id annee_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          r.plafond heures,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_referentiel r
          JOIN plafond p ON p.numero = 17
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
          LEFT JOIN plafond_referentiel pr ON pr.fonction_referentiel_id = r.id AND pr.plafond_id = p.id AND pr.annee_id = pa.annee_debut_id
        WHERE
          r.histo_destruction IS NULL
          AND r.plafond IS NOT NULL
          AND pr.id IS NULL
          AND pa.plafond_etat_id > 1
        ";
        $data = [];
        $qr   = $bdd->select($sql);
        foreach ($qr as $r) {
            $anneeDebut = (int)$r['ANNEE_ID'];
            $anneeFin   = (int)$r['ANNEE_FIN_ID'];
            if ($anneeFin == 0) $anneeFin = 2099;

            $plafondId = (int)$r['PLAFOND_ID'];
            $entiteId  = (int)$r['ENTITE_ID'];

            $etatPrevuId   = $r['PLAFOND_ETAT_PREVU_ID'] ? (int)$r['PLAFOND_ETAT_PREVU_ID'] : null;
            $etatRealiseId = $r['PLAFOND_ETAT_REALISE_ID'] ? (int)$r['PLAFOND_ETAT_REALISE_ID'] : null;
            $heures        = $r['HEURES'] ? (int)$r['HEURES'] : 0;

            for ($a = $anneeDebut; $a <= $anneeFin; $a++) {
                if (!isset($data[$a][$plafondId][$entiteId])) {
                    $data[$a][$plafondId][$entiteId] = [
                        'saisie'                  => $a == $anneeDebut,
                        'PLAFOND_ETAT_PREVU_ID'   => 1, // désactivé
                        'PLAFOND_ETAT_REALISE_ID' => 1, // désactivé
                        'HEURES'                  => 0,
                    ];
                }

                if ($etatPrevuId !== null) {
                    $data[$a][$plafondId][$entiteId]['PLAFOND_ETAT_PREVU_ID'] = $etatPrevuId;
                }

                if ($etatRealiseId !== null) {
                    $data[$a][$plafondId][$entiteId]['PLAFOND_ETAT_REALISE_ID'] = $etatRealiseId;
                }

                if ($heures !== null) {
                    $data[$a][$plafondId][$entiteId]['HEURES'] = $heures;
                }
            }
        }

        /* On supprime ce qui a déjà été créé */
        $sql = "SELECT * FROM PLAFOND_REFERENTIEL WHERE histo_destruction IS NULL";
        $des = $bdd->select($sql);
        foreach ($des as $de) {
            $a = (int)$de['ANNEE_ID'];
            $p = (int)$de['PLAFOND_ID'];
            $e = (int)$de['FONCTION_REFERENTIEL_ID'];
            unset($data[$a][$p][$e]);
        }

        $inserts = [];
        foreach ($data as $anneeId => $data2) {
            foreach ($data2 as $plafondId => $data3) {
                foreach ($data3 as $entiteId => $d) {
                    $insert                          = $d;
                    $insert['ANNEE_ID']              = $anneeId;
                    $insert['PLAFOND_ID']            = $plafondId;
                    $insert['ENTITE_ID']             = $entiteId;
                    $insert['HISTO_CREATION']        = new \DateTime();
                    $insert['HISTO_CREATEUR_ID']     = $this->manager->getOseAdmin()->getOseAppliId();
                    $insert['HISTO_MODIFICATION']    = new \DateTime();
                    $insert['HISTO_MODIFICATEUR_ID'] = $d['saisie'] ? $this->manager->getOseAdmin()->getOseAppliId() : null;
                    unset($insert['saisie']);
                    $inserts[] = $insert;
                }
            }
        }


        $c->begin('Convertion des paramètres de plafonds pour les fonctions référentielles');
        $count = count($inserts);
        foreach ($inserts as $current => $insert) {
            $insert['FONCTION_REFERENTIEL_ID'] = $insert['ENTITE_ID'];
            unset($insert['ENTITE_ID']);
            $c->msg('Ajout du paramètre ' . ($current + 1) . ' sur ' . $count . ' ...', true);
            $bdd->getTable('PLAFOND_REFERENTIEL')->insert($insert);
        }
        $c->end('Fin de la convertion des paramètres de plafonds pour les fonctions référentielles');
    }



    public function migrationParamsStatut()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $sql  = "
        SELECT
          s.code entite_id,
          p.id plafond_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          os.plafond_hc_remu_fc heures,
          pa.annee_debut_id annee_id,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_statut os
          JOIN plafond p ON p.numero = 12
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN statut s ON s.code = os.code AND s.annee_id = pa.annee_debut_id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
          LEFT JOIN plafond_statut ps ON ps.statut_id = s.id AND ps.plafond_id = p.id AND ps.annee_id = pa.annee_debut_id
        WHERE
          os.histo_destruction IS NULL
          AND os.plafond_hc_remu_fc IS NOT NULL
          AND ps.id IS NULL
          AND pa.plafond_etat_id > 1
        
        UNION ALL
        
        SELECT
          s.code entite_id,
          p.id plafond_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          os.plafond_referentiel heures,
          pa.annee_debut_id annee_id,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_statut os
          JOIN plafond p ON p.numero = 18
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN statut s ON s.code = os.code AND s.annee_id = pa.annee_debut_id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
          LEFT JOIN plafond_statut ps ON ps.statut_id = s.id AND ps.plafond_id = p.id AND ps.annee_id = pa.annee_debut_id
        WHERE
          os.histo_destruction IS NULL
          AND os.plafond_referentiel IS NOT NULL
          AND ps.id IS NULL
          AND pa.plafond_etat_id > 1
        
        UNION ALL
        
        SELECT
          s.code entite_id,
          p.id plafond_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          os.maximum_hetd heures,
          pa.annee_debut_id annee_id,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_statut os
          JOIN plafond p ON p.numero = 11
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN statut s ON s.code = os.code AND s.annee_id = pa.annee_debut_id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
          LEFT JOIN plafond_statut ps ON ps.statut_id = s.id AND ps.plafond_id = p.id AND ps.annee_id = pa.annee_debut_id
        WHERE
          os.histo_destruction IS NULL
          AND os.maximum_hetd IS NOT NULL
          AND ps.id IS NULL    
          AND pa.plafond_etat_id > 1              

        UNION ALL
        
        SELECT
          s.code entite_id,
          p.id plafond_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          os.plafond_hc_hors_remu_fc heures,
          pa.annee_debut_id annee_id,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_statut os
          JOIN plafond p ON p.numero = 10
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN statut s ON s.code = os.code AND s.annee_id = pa.annee_debut_id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
          LEFT JOIN plafond_statut ps ON ps.statut_id = s.id AND ps.plafond_id = p.id AND ps.annee_id = pa.annee_debut_id
        WHERE
          os.histo_destruction IS NULL
          AND os.plafond_hc_hors_remu_fc IS NOT NULL
          AND ps.id IS NULL
          AND pa.plafond_etat_id > 1
        
        UNION ALL
        
        SELECT
          s.code entite_id,
          p.id plafond_id,
          CASE WHEN tvh.code = 'PREVU' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_prevu_id,
          CASE WHEN tvh.code = 'REALISE' THEN pa.plafond_etat_id ELSE NULL END plafond_etat_realise_id,
          os.plafond_hc_fi_hors_ead heures,
          pa.annee_debut_id annee_id,
          pa.annee_fin_id annee_fin_id
        FROM
          save_v18_statut os
          JOIN plafond p ON p.numero = 13
          JOIN save_v18_plafond_app pa ON pa.plafond_id = p.id
          JOIN statut s ON s.code = os.code AND s.annee_id = pa.annee_debut_id
          JOIN type_volume_horaire tvh ON tvh.id = pa.type_volume_horaire_id
          LEFT JOIN plafond_statut ps ON ps.statut_id = s.id AND ps.plafond_id = p.id AND ps.annee_id = pa.annee_debut_id
        WHERE
          os.histo_destruction IS NULL
          AND os.plafond_hc_fi_hors_ead IS NOT NULL
          AND ps.id IS NULL
          AND pa.plafond_etat_id > 1
        ";
        $data = [];
        $qr   = $bdd->select($sql);
        foreach ($qr as $r) {
            $anneeDebut = (int)$r['ANNEE_ID'];
            $anneeFin   = (int)$r['ANNEE_FIN_ID'];
            if ($anneeFin == 0) $anneeFin = 2099;

            $plafondId = (int)$r['PLAFOND_ID'];
            $entiteId  = $r['ENTITE_ID'];

            $etatPrevuId   = $r['PLAFOND_ETAT_PREVU_ID'] ? (int)$r['PLAFOND_ETAT_PREVU_ID'] : null;
            $etatRealiseId = $r['PLAFOND_ETAT_REALISE_ID'] ? (int)$r['PLAFOND_ETAT_REALISE_ID'] : null;
            $heures        = $r['HEURES'] ? (int)$r['HEURES'] : 0;

            for ($a = $anneeDebut; $a <= $anneeFin; $a++) {
                if (!isset($data[$a][$plafondId][$entiteId])) {
                    $data[$a][$plafondId][$entiteId] = [
                        'saisie'                  => $a == $anneeDebut,
                        'PLAFOND_ETAT_PREVU_ID'   => 1, // désactivé
                        'PLAFOND_ETAT_REALISE_ID' => 1, // désactivé
                        'HEURES'                  => 0,
                    ];
                }

                if ($etatPrevuId !== null) {
                    $data[$a][$plafondId][$entiteId]['PLAFOND_ETAT_PREVU_ID'] = $etatPrevuId;
                }

                if ($etatRealiseId !== null) {
                    $data[$a][$plafondId][$entiteId]['PLAFOND_ETAT_REALISE_ID'] = $etatRealiseId;
                }

                if ($heures !== null) {
                    $data[$a][$plafondId][$entiteId]['HEURES'] = $heures;
                }
            }
        }

        /* On supprime ce qui a déjà été créé */
        $sql = "SELECT * FROM PLAFOND_STATUT WHERE histo_destruction IS NULL";
        $des = $bdd->select($sql);
        foreach ($des as $de) {
            $a = (int)$de['ANNEE_ID'];
            $p = (int)$de['PLAFOND_ID'];
            $e = (int)$de['STATUT_ID'];
            unset($data[$a][$p][$e]);
        }

        /* On récupère les statuts */
        $sql     = "SELECT code, annee_id, id FROM statut WHERE histo_destruction IS NULL";
        $statuts = [];
        $rs      = $bdd->select($sql);
        foreach ($rs as $r) {
            $statuts[$r['CODE']][(int)$r['ANNEE_ID']] = (int)$r['ID'];
        }

        $inserts = [];
        foreach ($data as $anneeId => $data2) {
            foreach ($data2 as $plafondId => $data3) {
                foreach ($data3 as $entiteId => $d) {
                    $statutId = $statuts[$entiteId][$anneeId] ?? null;

                    $insert                          = $d;
                    $insert['ANNEE_ID']              = $anneeId;
                    $insert['PLAFOND_ID']            = $plafondId;
                    $insert['ENTITE_ID']             = $statutId;
                    $insert['HISTO_CREATION']        = new \DateTime();
                    $insert['HISTO_CREATEUR_ID']     = $this->manager->getOseAdmin()->getOseAppliId();
                    $insert['HISTO_MODIFICATION']    = new \DateTime();
                    $insert['HISTO_MODIFICATEUR_ID'] = $d['saisie'] ? $this->manager->getOseAdmin()->getOseAppliId() : null;
                    unset($insert['saisie']);

                    if ($statutId) {
                        $inserts[] = $insert;
                    }
                }
            }
        }


        $c->begin('Convertion des paramètres de plafonds pour les statuts');
        $count = count($inserts);
        foreach ($inserts as $current => $insert) {
            $insert['STATUT_ID'] = $insert['ENTITE_ID'];
            unset($insert['ENTITE_ID']);
            $c->msg('Ajout du paramètre ' . ($current + 1) . ' sur ' . $count . ' ...', true);
            $bdd->getTable('PLAFOND_STATUT')->insert($insert);
        }
        $c->end('Fin de la convertion des paramètres de plafonds pour les statuts');
    }
}