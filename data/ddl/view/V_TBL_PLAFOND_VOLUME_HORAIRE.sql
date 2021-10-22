CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_VOLUME_HORAIRE AS
SELECT
  p.PLAFOND_ID,
  pa.PLAFOND_ETAT_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.ELEMENT_PEDAGOGIQUE_ID,
  p.TYPE_INTERVENTION_ID,
  p.HEURES,
  p.PLAFOND,
  p.DEROGATION,
  CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement
FROM
(
  SELECT 9 PLAFOND_ID, 0 DEROGATION, p.* FROM (
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
      s.annee_id                                  annee_id,
      s.type_volume_horaire_id                    type_volume_horaire_id,
      s.intervenant_id                            intervenant_id,
      s.element_pedagogique_id                    element_pedagogique_id,
      s.type_intervention_id                      type_intervention_id,
      s.heures                                    heures,
      COALESCE(c.heures * c.groupes,0)            plafond
    FROM
                s
           JOIN type_intervention ti ON ti.id = s.type_intervention_id
           JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN c ON c.element_pedagogique_id = s.element_pedagogique_id
                 AND c.type_intervention_id = COALESCE(ti.type_intervention_maquette_id,ti.id)
    WHERE
      s.heures - COALESCE(c.heures * c.groupes,0) > 0
  ) p
) p
JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
WHERE
  1=1
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/
  /*@ELEMENT_PEDAGOGIQUE_ID=p.ELEMENT_PEDAGOGIQUE_ID*/
  /*@TYPE_INTERVENTION_ID=p.TYPE_INTERVENTION_ID*/