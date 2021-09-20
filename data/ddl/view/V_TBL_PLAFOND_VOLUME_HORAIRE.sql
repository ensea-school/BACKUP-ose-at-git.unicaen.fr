CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_VOLUME_HORAIRE AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.ELEMENT_PEDAGOGIQUE_ID,
  p.TYPE_INTERVENTION_ID,
  p.HEURES,
  p.PLAFOND,
  p.DEROGATION
FROM
(
  SELECT 9 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      i.annee_id                annee_id,
      vh.type_volume_horaire_id type_volume_horaire_id,
      i.id                      intervenant_id,
      s.element_pedagogique_id  element_pedagogique_id,
      vh.type_intervention_id   type_intervention_id,
      SUM(vh.heures)            heures,
      vhe.heures * vhe.groupes  plafond
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND s.histo_destruction IS NULL
      JOIN intervenant i ON i.id = s.intervenant_id
      JOIN volume_horaire_ens vhe ON vhe.histo_destruction IS NULL
                                 AND vhe.element_pedagogique_id = s.element_pedagogique_id
                                 AND vhe.type_intervention_id = vh.type_intervention_id
    WHERE
      vh.histo_destruction IS NULL
      AND s.element_pedagogique_id IS NOT NULL
      AND vhe.groupes IS NOT NULL AND vhe.heures IS NOT NULL
    GROUP BY
      i.annee_id, vh.type_volume_horaire_id, i.id, s.element_pedagogique_id, vh.type_intervention_id, vhe.heures, vhe.groupes
  ) p
) p
JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
WHERE
  1=1
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/
  /*@ELEMENT_PEDAGOGIQUE_ID=p.ELEMENT_PEDAGOGIQUE_ID*/
  /*@TYPE_INTERVENTION_ID=p.TYPE_INTERVENTION_ID*/