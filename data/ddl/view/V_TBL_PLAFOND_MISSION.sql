CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_MISSION AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.TYPE_MISSION_ID,
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
  SELECT 14 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                        annee_id,
        vhm.type_volume_horaire_id        type_volume_horaire_id,
        i.id                              intervenant_id,
        tm.id                             type_mission_id,
        SUM(vhm.heures)                   heures
      FROM
             mission       m
        JOIN intervenant                i ON i.id = m.intervenant_id
        JOIN type_mission     tm ON tm.id = m.type_mission_id
        JOIN volume_horaire_mission       vhm ON vhm.mission_id = m.id AND vhm.histo_destruction IS NULL
      WHERE
        m.histo_destruction IS NULL
      GROUP BY
        i.annee_id, vhm.type_volume_horaire_id, i.id, tm.id
    ) p
  ) p
  JOIN intervenant i ON i.id = p.intervenant_id
  LEFT JOIN plafond_mission ps ON ps.plafond_id = p.plafond_id AND ps.type_mission_id = p.type_mission_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL
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
  /*@TYPE_MISSION_ID=p.TYPE_MISSION_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/