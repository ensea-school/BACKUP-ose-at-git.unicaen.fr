CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_VOLUME_HORAIRE AS
SELECT
  p.ANNEE_ID,
  p.ELEMENT_PEDAGOGIQUE_ID,
  p.TYPE_INTERVENTION_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.PLAFOND,
  p.HEURES,
  p.DEROGATION,
  p.PLAFOND_ID
FROM
(
  SELECT NULL ANNEE_ID,NULL ELEMENT_PEDAGOGIQUE_ID,NULL TYPE_INTERVENTION_ID,NULL TYPE_VOLUME_HORAIRE_ID,NULL PLAFOND,NULL HEURES, 0 DEROGATION, NULL PLAFOND_ID FROM dual WHERE 0 = 1
) p
JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
WHERE
  1=1
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@ELEMENT_PEDAGOGIQUE_ID=p.ELEMENT_PEDAGOGIQUE_ID*/
  /*@TYPE_INTERVENTION_ID=p.TYPE_INTERVENTION_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/