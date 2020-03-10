CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1220 AS
SELECT
  i.id id,
  i.annee_id,
  i.id intervenant_id,
  i.structure_id,
  AVG(t.plafond)  plafond,
  AVG(t.heures)   heures
FROM
  (
  SELECT
    vhr.type_volume_horaire_id        type_volume_horaire_id,
    sr.intervenant_id                 intervenant_id,
    fr.plafond                        plafond,
    fr.id                             fr_id,
    SUM(vhr.heures)                   heures
  FROM
         service_referentiel       sr
    JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
    JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
    JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code= 'REALISE'
  WHERE
    sr.histo_destruction IS NULL
  GROUP BY
    vhr.type_volume_horaire_id,
    sr.intervenant_id,
    fr.plafond,
    fr.id
  ) t
  JOIN intervenant i ON i.id = t.intervenant_id
WHERE
  t.heures > t.plafond
  /*i.id*/
GROUP BY
  t.type_volume_horaire_id,
  i.annee_id,
  i.id,
  i.structure_id