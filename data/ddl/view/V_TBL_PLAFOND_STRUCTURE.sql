CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_STRUCTURE AS
SELECT
  p.PLAFOND_ID,
  p.PLAFOND_ETAT_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.STRUCTURE_ID,
  p.HEURES,
  p.PLAFOND,
  p.DEROGATION
FROM
(
  SELECT 7 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      i.annee_id                 annee_id,
      vhr.type_volume_horaire_id type_volume_horaire_id,
      i.id                       intervenant_id,
      s.id                       structure_id,
      SUM(vhr.heures)            heures,
      s.plafond_referentiel      plafond
    FROM
      service_referentiel       sr
      JOIN intervenant           i ON i.id = sr.intervenant_id
      JOIN structure             s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
      JOIN volume_horaire_ref  vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
    WHERE
      sr.histo_destruction IS NULL
    GROUP BY
      i.annee_id, vhr.type_volume_horaire_id, i.id, s.id, s.plafond_referentiel
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
  /*@STRUCTURE_ID=p.STRUCTURE_ID*/