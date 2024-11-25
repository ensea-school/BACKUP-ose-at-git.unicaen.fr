CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_STRUCTURE AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.STRUCTURE_ID,
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
  SELECT 6 PLAFOND_ID, p.ANNEE_ID, p.TYPE_VOLUME_HORAIRE_ID, p.INTERVENANT_ID, p.STRUCTURE_ID, p.HEURES, NULL PLAFOND, NULL PLAFOND_ETAT_ID FROM (
    SELECT
        i.annee_id                 annee_id,
        vhr.type_volume_horaire_id type_volume_horaire_id,
        i.id                       intervenant_id,
        s.id                       structure_id,
        SUM(vhr.heures)            heures
      FROM
        service_referentiel       sr
        JOIN intervenant           i ON i.id = sr.intervenant_id
        JOIN structure             s ON s.id = sr.structure_id
        JOIN volume_horaire_ref  vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
      WHERE
        sr.histo_destruction IS NULL
      GROUP BY
        i.annee_id, vhr.type_volume_horaire_id, i.id, s.id
    ) p
  ) p
  LEFT JOIN plafond_structure ps ON ps.plafond_id = p.plafond_id AND ps.structure_id = p.structure_id AND ps.annee_id = p.annee_id AND ps.histo_destruction IS NULL
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
  /*@STRUCTURE_ID=p.STRUCTURE_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/