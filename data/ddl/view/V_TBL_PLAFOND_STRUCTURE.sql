CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_STRUCTURE AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.STRUCTURE_ID,
  p.HEURES,
  p.PLAFOND,
  p.DEROGATION
FROM
(
  SELECT 7 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      t.type_volume_horaire_id,
      t.annee_id,
      t.structure_id,
      t.plafond,
      t.heures
    FROM
      (
        SELECT DISTINCT
          vhr.type_volume_horaire_id        type_volume_horaire_id,
          i.annee_id                        annee_id,
          s.plafond_referentiel             plafond,
          s.id                              structure_id,
          s.libelle_court                   structure_libelle,
          SUM(vhr.heures) OVER (PARTITION BY s.id,vhr.type_volume_horaire_id,i.annee_id) heures
        FROM
                 service_referentiel       sr
            JOIN intervenant                i ON i.id = sr.intervenant_id
            JOIN structure                  s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
            JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        WHERE
            sr.histo_destruction IS NULL
      ) t
  ) p
) p
JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
WHERE
  1=1
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@STRUCTURE_ID=p.STRUCTURE_ID*/