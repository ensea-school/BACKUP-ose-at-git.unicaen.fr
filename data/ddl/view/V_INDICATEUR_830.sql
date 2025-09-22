CREATE OR REPLACE FORCE VIEW V_INDICATEUR_830 AS
SELECT DISTINCT
  idc.intervenant_id,
  idc.structure_id
FROM
            (
            WITH c AS (
              SELECT
                vhe.element_pedagogique_id,
                vhe.type_intervention_id,
                MAX(vhe.heures) heures,
                COALESCE( MAX(vhe.groupes), ROUND(SUM(t.groupes),10) ) groupes

              FROM
                volume_horaire_ens     vhe
                     JOIN parametre p ON p.nom = 'scenario_charges_services'
                LEFT JOIN tbl_chargens   t ON t.element_pedagogique_id = vhe.element_pedagogique_id
                                          AND t.type_intervention_id = vhe.type_intervention_id
                                          AND t.scenario_id = to_number(p.valeur)
                                          /*@annee_id=t.annee_id*/
              GROUP BY
                vhe.element_pedagogique_id,
                vhe.type_intervention_id
            ), s AS (
              SELECT
                i.annee_id,
                vh.type_volume_horaire_id,
                s.intervenant_id,
                s.element_pedagogique_id,
                vh.type_intervention_id,
                SUM(vh.heures) heures
              FROM
                volume_horaire vh
                JOIN service     s ON s.id = vh.service_id
                                  AND s.element_pedagogique_id IS NOT NULL
                                  AND s.histo_destruction IS NULL
                JOIN intervenant i ON i.id = s.intervenant_id
                                  AND i.histo_destruction IS NULL
                                  /*@annee_id=i.annee_id*/
              WHERE
                vh.histo_destruction IS NULL
              GROUP BY
                i.annee_id,
                vh.type_volume_horaire_id,
                s.intervenant_id,
                s.element_pedagogique_id,
                vh.type_intervention_id
            )
            SELECT
              rownum                                      id,
              s.annee_id                                  annee_id,
              s.type_volume_horaire_id                    type_volume_horaire_id,
              s.intervenant_id                            intervenant_id,
              ep.structure_id                             structure_id,
              s.element_pedagogique_id                    element_pedagogique_id,
              ep.periode_id                               periode_id,
              s.type_intervention_id                      type_intervention_id,
              s.heures - COALESCE(c.heures * c.groupes,0) heures
            FROM
                        s
                   JOIN type_intervention ti ON ti.id = s.type_intervention_id
                   JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
              LEFT JOIN c ON c.element_pedagogique_id = s.element_pedagogique_id
                         AND c.type_intervention_id = COALESCE(ti.type_intervention_maquette_id,ti.id)
            WHERE
                s.heures - COALESCE(c.heures * c.groupes,0) > 0
            )  idc
       JOIN type_volume_horaire     tvh ON tvh.id = idc.type_volume_horaire_id
  LEFT JOIN periode                 p ON p.id = idc.periode_id
WHERE
  (p.code = 'S1' OR p.id IS NULL)
  AND tvh.code = 'REALISE'