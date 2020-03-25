CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1230 AS
SELECT
  t.intervenant_id    id,
  t.annee_id          annee_id,
  t.intervenant_id    intervenant_id,
  t.structure_id      structure_id,
  t.plafond           plafond,
  t.heures            heures
FROM
  (
    SELECT DISTINCT
      i.id                              intervenant_id,
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
        JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code = 'PREVU'
    WHERE
        sr.histo_destruction IS NULL
  ) t
WHERE
    t.heures > t.plafond