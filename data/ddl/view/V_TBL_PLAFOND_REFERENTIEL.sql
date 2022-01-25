CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_REFERENTIEL AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.FONCTION_REFERENTIEL_ID,
  p.HEURES,
  p.PLAFOND,
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN COALESCE(ps.plafond_etat_prevu_id,pa.plafond_etat_prevu_id)
    WHEN p.type_volume_horaire_id = 2 THEN COALESCE(ps.plafond_etat_realise_id, pa.plafond_etat_realise_id)
  END plafond_etat_id,
  COALESCE(pd.heures, 0) derogation,
  CASE WHEN p.heures > p.plafond + COALESCE(pd.heures, 0) + 0.05 THEN 1 ELSE 0 END depassement
FROM
  (
  SELECT 3 PLAFOND_ID, p.* FROM (
    SELECT
        i.annee_id                        annee_id,
        vhr.type_volume_horaire_id        type_volume_horaire_id,
        i.id                              intervenant_id,
        fr.id                             fonction_referentiel_id,
        SUM(vhr.heures)                   heures,
        0                        plafond
      FROM
             service_referentiel       sr
        JOIN intervenant                i ON i.id = sr.intervenant_id
        JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
      WHERE
        sr.histo_destruction IS NULL
      GROUP BY
        i.annee_id, vhr.type_volume_horaire_id, i.id, fr.id
    ) p

    UNION ALL

  SELECT 6 PLAFOND_ID, p.* FROM (
    SELECT
        i.annee_id                 annee_id,
        vhr.type_volume_horaire_id type_volume_horaire_id,
        i.id                       intervenant_id,
        fr.id                      fonction_referentiel_id,
        SUM(vhr.heures)            heures,
        0                 plafond
      FROM
        service_referentiel       sr
        JOIN intervenant i ON i.id = sr.intervenant_id
        JOIN fonction_referentiel      frf ON frf.id = sr.fonction_id
        JOIN fonction_referentiel      fr ON fr.id = frf.parent_id
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
      WHERE
        sr.histo_destruction IS NULL
      GROUP BY
        i.annee_id, vhr.type_volume_horaire_id, i.id, fr.id
    ) p
  ) p
  JOIN intervenant i ON i.id = p.intervenant_id
  LEFT JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND p.annee_id = pa.annee_id
  LEFT JOIN plafond_referentiel ps ON ps.plafond_id = p.plafond_id AND ps.fonction_referentiel_id = p.fonction_referentiel_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL
  LEFT JOIN plafond_derogation pd ON pd.plafond_id = p.plafond_id AND pd.intervenant_id = p.intervenant_id AND pd.histo_destruction IS NULL
WHERE
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN COALESCE(ps.plafond_etat_prevu_id,pa.plafond_etat_prevu_id)
    WHEN p.type_volume_horaire_id = 2 THEN COALESCE(ps.plafond_etat_realise_id, pa.plafond_etat_realise_id)
  END IS NOT NULL
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/
  /*@FONCTION_REFERENTIEL_ID=p.FONCTION_REFERENTIEL_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/