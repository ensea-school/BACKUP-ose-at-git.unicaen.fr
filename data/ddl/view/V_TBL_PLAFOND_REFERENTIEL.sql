CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_REFERENTIEL AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.FONCTION_REFERENTIEL_ID,
  p.HEURES,
  COALESCE(p.PLAFOND,ps.heures,0) PLAFOND,
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN ps.plafond_etat_prevu_id
    WHEN p.type_volume_horaire_id = 2 THEN ps.plafond_etat_realise_id
    ELSE COALESCE(p.plafond_etat_id,1)
  END plafond_etat_id,
  COALESCE(pd.heures, 0) derogation,
  CASE WHEN p.heures > COALESCE(p.PLAFOND,ps.heures,0) + COALESCE(pd.heures, 0) + 0.05 THEN 1 ELSE 0 END depassement
FROM
  (
    SELECT NULL PLAFOND_ID,NULL ANNEE_ID,NULL TYPE_VOLUME_HORAIRE_ID,NULL INTERVENANT_ID,NULL FONCTION_REFERENTIEL_ID,NULL HEURES,NULL PLAFOND,NULL PLAFOND_ETAT_ID,NULL DEROGATION FROM dual WHERE 0 = 1
  ) p
  JOIN intervenant i ON i.id = p.intervenant_id
  LEFT JOIN plafond_referentiel ps ON ps.plafond_id = p.plafond_id AND ps.fonction_referentiel_id = p.fonction_referentiel_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL
  LEFT JOIN plafond_derogation pd ON pd.plafond_id = p.plafond_id AND pd.intervenant_id = p.intervenant_id AND pd.histo_destruction IS NULL
WHERE
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN ps.plafond_etat_prevu_id
    WHEN p.type_volume_horaire_id = 2 THEN ps.plafond_etat_realise_id
  END IS NOT NULL
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/
  /*@FONCTION_REFERENTIEL_ID=p.FONCTION_REFERENTIEL_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/