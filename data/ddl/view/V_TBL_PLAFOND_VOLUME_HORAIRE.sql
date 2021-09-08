CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_VOLUME_HORAIRE AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.ELEMENT_PEDAGOGIQUE_ID,
  p.TYPE_INTERVENTION_ID,
  p.HEURES,
  p.PLAFOND,
  p.DEROGATION
FROM
(
  SELECT NULL PLAFOND_ID,NULL ANNEE_ID,NULL TYPE_VOLUME_HORAIRE_ID,NULL ELEMENT_PEDAGOGIQUE_ID,NULL TYPE_INTERVENTION_ID,NULL HEURES,NULL PLAFOND,NULL DEROGATION FROM dual WHERE 0 = 1
) p
JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
WHERE
  1=1
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@ELEMENT_PEDAGOGIQUE_ID=p.ELEMENT_PEDAGOGIQUE_ID*/
  /*@TYPE_INTERVENTION_ID=p.TYPE_INTERVENTION_ID*/