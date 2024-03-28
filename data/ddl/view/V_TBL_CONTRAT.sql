CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT AS
WITH services AS (

        SELECT
            s.intervenant_id                                                                intervenant_id,
            c.id                                                                            contrat_id,
            c.contrat_id                                                                    contrat_parent_id,
            str.id                                                                          structure_id,
            vh.service_id                                                                   service_id,
            NULL                                                                            service_referentiel_id,
            NULL                                                                            mission_id,
            SUM(CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END)                         heures_cm,
            SUM(CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END)                         heures_td,
            SUM(CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END)                         heures_tp,
            SUM(CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END)        heures_autres,
            SUM(vh.heures)                                                                  heures_totales,
            'ENS'                                                                           type_service_code,
            CASE p.nom
               WHEN 'contrat_ens_composante'    THEN 'er_'+s.intervenant_id+'_'+str.id
               WHEN 'contrat_ens_globale'       THEN 'er_'+s.intervenant_id
            END                                                                             uuid,
            c.type_contrat_id as                                                            avenant,
            c.debut_validite                                                                date_debut,
            c.fin_validite                                                                  date_fin,
            evh.code                                                                        etat_service,
            c.histo_creation                                                                date_creation,
            1.0                                                                             taux_conge
        FROM
            volume_horaire vh
            JOIN service s ON s.id = vh.service_id
            JOIN type_intervention ti ON ti.id = vh.type_intervention_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
            JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
            LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
            JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
            JOIN STRUCTURE str ON ep.structure_id = str.id
            LEFT JOIN contrat c ON c.id = vh.contrat_id
            JOIN parametre p on p.nom = 'contrat_ens'
            LEFT JOIN v_volume_horaire_etat vvhe on vvhe.volume_horaire_id = vh.id
            LEFT JOIN etat_volume_horaire evh on vvhe.etat_volume_horaire_id = evh.id
        WHERE
            vh.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vh.auto_validation = 1)
            /*@INTERVENANT_ID=s.intervenant_id*/
            GROUP BY
            s.intervenant_id,
            c.id,
            str.id,
            vh.service_id,
            c.type_contrat_id,
            (CASE p.nom
               WHEN 'contrat_ens_composante'    THEN 'er_'+s.intervenant_id+'_'+str.id
               WHEN 'contrat_ens_globale'       THEN 'er_'+s.intervenant_id
            END),
            c.debut_validite,
            c.fin_validite,
            c.contrat_id,
            evh.code,
            c.histo_creation


    UNION ALL


        SELECT
            sr.intervenant_id                                                                   intervenant_id,
            c.id                                                                                contrat_id,
            c.contrat_id                                                                        contrat_parent_id,
            str.id                                                                              structure_id,
            NULL                                                                                service_id,
            vhr.service_referentiel_id                                                          service_referentiel_id,
            NULL                                                                                mission_id,
            0                                                                                   heures_cm,
            0                                                                                   heures_td,
            0                                                                                   heures_tp,
            SUM(vhr.heures)                                                                     heures_autres,
            SUM(vhr.heures)                                                                     heures_totales,
            'REF'                                                                               type_service_code,
            CASE p.nom
               WHEN 'contrat_ens_composante'    THEN 'er_'+sr.intervenant_id+'_'+str.id
               WHEN 'contrat_ens_globale'       THEN 'er_'+sr.intervenant_id
            END                                                                                 uuid,
            c.type_contrat_id                                                                   avenant,
            c.debut_validite                                                                    date_debut,
            c.fin_validite                                                                      date_fin,
            evh.code                                                                            etat_service,
            c.histo_creation                                                                    date_creation,
            1.0                                                                                 taux_conge
        FROM
            volume_horaire_ref vhr
            JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
            JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
            JOIN type_volume_horaire tvh ON tvh.id = vhr.type_volume_horaire_id
            LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
            JOIN validation v ON v.id = vvhr.validation_id AND v.histo_destruction IS NULL
            LEFT JOIN contrat c ON c.id = vhr.contrat_id
            LEFT JOIN STRUCTURE str ON sr.structure_id = str.id
            JOIN parametre p on p.nom = 'contrat_ens'
            LEFT JOIN v_volume_horaire_ref_etat vvhre on vvhre.volume_horaire_ref_id = vhr.id
            LEFT JOIN etat_volume_horaire evh on vvhre.etat_volume_horaire_id = evh.id
        WHERE
            vhr.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vhr.auto_validation = 1)
            /*@INTERVENANT_ID=sr.intervenant_id*/
        GROUP BY
            sr.intervenant_id,
            c.id,
            str.id,
            vhr.service_referentiel_id,
            c.type_contrat_id,
            (CASE p.nom
               WHEN 'contrat_ens_composante'    THEN 'er_'+sr.intervenant_id+'_'+str.id
               WHEN 'contrat_ens_globale'       THEN 'er_'+sr.intervenant_id
            END),
            c.debut_validite,
            c.fin_validite,
            c.contrat_id,
            evh.code,
            c.histo_creation


    UNION ALL


        SELECT
            m.intervenant_id                                                            intervenant_id,
            c.id                                                                        contrat_id,
            c.contrat_id                                                                contrat_parent_id,
            str.id                                                                      structure_id,
            NULL                                                                        service_id,
            NULL                                                                        service_referentiel_id,
            m.id                                                                        mission_id,
            0                                                                           heures_cm,
            0                                                                           heures_td,
            0                                                                           heures_tp,
            SUM(vhm.heures)                                                             heures_autres,
            SUM(vhm.heures)                                                             heures_totales,
            'MIS'                                                                       type_service_code,
            CASE p.nom
               WHEN 'contrat_mis_mission'       THEN 'm_'+m.intervenant_id+'_'+m.id
               WHEN 'contrat_mis_composante'    THEN 'm_'+m.intervenant_id+'_'+str.id
               WHEN 'contrat_mis_globale'       THEN 'm_'+m.intervenant_id
            END                                                                         uuid,
            c.type_contrat_id                                                           avenant,
            c.debut_validite                                                            date_debut,
            c.fin_validite                                                              date_fin,
            evh.code                                                                    etat_service,
            c.histo_creation                                                            date_creation,
            CAST(p2.valeur as FLOAT)                                                    taux_conge
        FROM
            volume_horaire_mission vhm
            JOIN mission m ON m.id = vhm.mission_id
            JOIN type_mission tm ON m.type_mission_id = tm.id
            LEFT JOIN contrat c ON c.id = vhm.contrat_id
            JOIN STRUCTURE str ON m.structure_id = str.id
            LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
            JOIN validation v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
            JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id
            JOIN validation_mission vm ON vm.mission_id = m.id
            JOIN validation v2 ON v2.id = vm.validation_id AND v2.histo_destruction IS NULL
            JOIN parametre p on p.nom = 'contrat_mis'
            JOIN parametre p2 on p2.nom = 'taux_conges_payes'
            LEFT JOIN v_volume_horaire_mission_etat vvhme on vvhme.volume_horaire_mission_id = vhm.id
            LEFT JOIN etat_volume_horaire evh on vvhme.etat_volume_horaire_id = evh.id
        WHERE
            vhm.histo_destruction IS NULL
            AND tvh.code = 'PREVU'
            AND (v.id IS NOT NULL OR vhm.auto_validation = 1)
            AND m.histo_destruction IS NULL
            AND (v2.id IS NOT NULL OR vhm.auto_validation = 1)
            /*@INTERVENANT_ID=m.intervenant_id*/
        GROUP BY
            m.intervenant_id,
            c.id,
            str.id,
            m.id,
            c.type_contrat_id,
            (CASE p.nom
               WHEN 'contrat_mis_mission'       THEN 'm_'+m.intervenant_id+'_'+m.id
               WHEN 'contrat_mis_composante'    THEN 'm_'+m.intervenant_id+'_'+str.id
               WHEN 'contrat_mis_globale'       THEN 'm_'+m.intervenant_id
            END),
            c.debut_validite,
            c.fin_validite,
            c.contrat_id,
            evh.code,
            c.histo_creation,
            p2.valeur
),
contrats_et_libelles AS (
    SELECT
        c.id AS contrat_id,
        LISTAGG(distinct ti.libelle, ',') WITHIN GROUP (ORDER BY ti.libelle) AS autre_libelles
    FROM
        contrat c
        INNER JOIN volume_horaire vh ON c.id = vh.contrat_id
        INNER JOIN type_intervention ti ON vh.type_intervention_id = ti.id
    WHERE
        ti.code NOT IN ('CM', 'TD', 'TP')
    GROUP BY
        c.id
)

SELECT
    rownum  key,
    s.intervenant_id,
    i.annee_id,
    s.contrat_id,
    s.contrat_parent_id,
    ts.id                                                                                                               type_service_id,
    s.structure_id,
    s.service_id,
    s.service_referentiel_id,
    s.mission_id,
    s.heures_cm,
    s.heures_td,
    s.heures_tp,
    s.heures_autres                                                                                                     autres,
    heures_totales                                                                                                      heures,
    s.type_service_code,
    s.uuid,
    s.date_debut,
    s.date_fin,
    s.date_creation,
    CASE WHEN s.avenant IS NULL
    THEN
        CASE WHEN (SELECT count(uuid) from services s WHERE s.uuid = s.uuid AND contrat_id IS NOT NULL) > 0
            THEN 2
            ELSE 1
        END
    ELSE s.avenant
    END                                                                                                                 avenant,
    CASE WHEN s.etat_service IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END                                     edite,
    CASE WHEN s.etat_service IN ('contrat-signe')                 THEN 1 ELSE 0 END                                     signe,
    s.taux_conge                                                                                                        taux_conge,
    tr.id                                                                                                               taux_remu_id,
    trm.id                                                                                                              taux_remu_majoree_id,
    1                                                                                                                   actif,
    contrats_et_libelles.autre_libelles
FROM
    services s
    JOIN TYPE_SERVICE ts ON ts.code = s.type_service_code
    JOIN INTERVENANT i on i.id = s.intervenant_id
    JOIN statut                     si ON si.id = i.statut_id
    JOIN parametre                  p ON p.nom = 'taux-remu'
    LEFT JOIN mission               m ON s.mission_id = m.id
    LEFT JOIN service               se ON s.service_id = se.id
    LEFT JOIN element_pedagogique   ep ON se.element_pedagogique_id = ep.id
    LEFT JOIN taux_remu             tr ON COALESCE(ep.taux_remu_id,m.taux_remu_id, si.taux_remu_id, CAST(p.valeur AS INT)) = tr.id
    LEFT JOIN taux_remu             trm ON m.taux_remu_majore_id = tr.id
    LEFT JOIN                       contrats_et_libelles ON contrats_et_libelles.contrat_id = s.contrat_id