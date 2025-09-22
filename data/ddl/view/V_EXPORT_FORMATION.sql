CREATE OR REPLACE FORCE VIEW V_EXPORT_FORMATION AS
SELECT
            MAX(e.annee_id)                                       annee_id,
            MAX(s.id)												                      structure_id,
            MAX(s.ids)												                    structure_ids,
            MAX(s.libelle_court)             						          structure,
            MAX(e.code)												                    code_formation,
            MAX(e.libelle) 											                  libelle_formation,
            MAX(e.id)												                      etape_id,
            MAX(vnf.libelle_long)									                niveau,
            MAX(ep.code) 											                    code_enseignement,
            MAX(ep.libelle) 										                  libelle_enseignement,
            MAX(d.source_code)			    						              code_discipline,
            MAX(d.libelle_long)										                libelle_discipline,
            MAX(p.libelle_long)      								              periode,
            MAX(ep.taux_foad)										                  foad,
            MAX(ep.fi) 												                    fi,
            MAX(ep.fa) 												                    fa,
            MAX(ep.fc) 												                    fc,
            COALESCE(MAX(ef.fi),0)									              effectif_fi,
            COALESCE(MAX(ef.fa),0)									              effectif_fa,
            COALESCE(MAX(ef.fc),0)									              effectif_fc,
            SUM(CASE WHEN ti.code = 'CM' THEN vhe.heures END) 		heures_cm,
            SUM(CASE WHEN ti.code = 'CM' THEN vhe.groupes END) 		nb_groupe_cm,
            SUM(CASE WHEN ti.code = 'TD' THEN vhe.heures END) 		heures_td,
            SUM(CASE WHEN ti.code = 'TD' THEN vhe.groupes END) 		nb_groupe_td,
            SUM(CASE WHEN ti.code = 'TP' THEN vhe.heures END) 		heures_tp,
            SUM(CASE WHEN ti.code = 'TP' THEN vhe.groupes END) 		nb_groupe_tp
        FROM
            etape e
        LEFT JOIN STRUCTURE s ON s.id = e.structure_id
        LEFT JOIN v_etape_niveau_formation venf ON e.id = venf.etape_id
        LEFT JOIN v_niveau_formation vnf ON vnf.id = venf.niveau_formation_id
        LEFT JOIN element_pedagogique ep ON ep.etape_id = e.id AND ep.histo_destruction IS NULL
        LEFT JOIN discipline d ON d.id = ep.discipline_id
        LEFT JOIN periode p ON p.id = ep.periode_id
        LEFT JOIN effectifs ef ON ef.element_pedagogique_id = ep.id
        LEFT JOIN volume_horaire_ens vhe ON vhe.element_pedagogique_id = ep.id
        LEFT JOIN type_intervention ti ON ti.id = vhe.type_intervention_id
        WHERE 
            e.histo_destruction IS NULL
        GROUP BY 
            ep.id


