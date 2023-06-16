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
  SELECT 9 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                  annee_id,
        vhm.type_volume_horaire_id  type_volume_horaire_id,
        i.id                        intervenant_id,
        SUM(vhm.heures)             heures
      FROM
        volume_horaire_mission vhm
        JOIN mission m ON m.histo_destruction IS NULL AND m.id = vhm.mission_id
        JOIN intervenant i ON i.id = m.intervenant_id
      WHERE
        vhm.histo_destruction IS NULL
      GROUP BY
        i.annee_id, vhm.type_volume_horaire_id, i.id
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