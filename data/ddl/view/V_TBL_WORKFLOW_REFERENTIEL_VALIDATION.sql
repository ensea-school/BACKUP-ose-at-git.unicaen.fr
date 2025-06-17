CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_REFERENTIEL_VALIDATION AS
SELECT
  CASE
    WHEN tvh.code = 'PREVU'   THEN 'referentiel_validation'
    WHEN tvh.code = 'REALISE' THEN 'referentiel_validation_realise'
  END                                                      etape_code,
  tvr.intervenant_id                                       intervenant_id,
  tvr.structure_id                                         structure_id,
  SUM(vhr.heures)                                          objectif,
  SUM(vhr.heures)                                          partiel,
  SUM(CASE WHEN tvr.valide = 1 THEN vhr.heures ELSE 0 END) realisation
FROM
  tbl_validation_referentiel tvr
  JOIN type_volume_horaire tvh ON tvh.id = tvr.type_volume_horaire_id
  JOIN volume_horaire_ref vhr ON vhr.id = tvr.volume_horaire_ref_id
WHERE
  tvr.auto_validation = 0
  AND vhr.histo_destruction IS NULL
  /*@intervenant_id=tvr.intervenant_id*/
  /*@annee_id=tvr.annee_id*/
GROUP BY
  tvr.intervenant_id,
  tvr.structure_id,
  tvh.code