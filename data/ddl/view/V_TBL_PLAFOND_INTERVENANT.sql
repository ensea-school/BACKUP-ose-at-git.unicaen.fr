CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_INTERVENANT AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
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
  SELECT 9 PLAFOND_ID, p.ANNEE_ID, p.TYPE_VOLUME_HORAIRE_ID, p.INTERVENANT_ID, p.HEURES, NULL PLAFOND, NULL PLAFOND_ETAT_ID FROM (
    SELECT
        i.annee_id                annee_id,
        vh.type_volume_horaire_id type_volume_horaire_id,
        i.id                      intervenant_id,
        SUM(vh.heures)            heures
      FROM
        volume_horaire vh
        JOIN service s ON s.id = vh.service_id
        JOIN intervenant i ON i.id = s.intervenant_id
        JOIN statut si ON si.id = i.statut_id
      WHERE
        vh.histo_destruction IS NULL
        AND i.histo_destruction IS NULL
        AND vh.motif_non_paiement_id IS NULL
        AND si.code IN ('IMP')
      GROUP BY
        i.annee_id,
        vh.type_volume_horaire_id,
        i.id,
        i.statut_id
      HAVING
        SUM(vh.heures) >= 0
    ) p
  ) p
  JOIN intervenant i ON i.id = p.intervenant_id
  LEFT JOIN plafond_statut ps ON ps.plafond_id = p.plafond_id AND ps.statut_id = i.statut_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL
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
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/