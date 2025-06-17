CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_ENSEIGNEMENT_VALIDATION AS
SELECT
  CASE
    WHEN tvh.code = 'PREVU'   THEN 'enseignement_validation'
    WHEN tvh.code = 'REALISE' THEN 'enseignement_validation_realise'
  END                                                     etape_code,
  tve.intervenant_id                                      intervenant_id,
  tve.structure_id                                        structure_id,
  SUM(vh.heures)                                          objectif,
  SUM(vh.heures)                                          partiel,
  SUM(CASE WHEN tve.valide = 1 THEN vh.heures ELSE 0 END) realisation
FROM
  tbl_validation_enseignement tve
  JOIN type_volume_horaire tvh ON tvh.id = tve.type_volume_horaire_id
  JOIN volume_horaire vh ON vh.id = tve.volume_horaire_id
WHERE
  tve.auto_validation = 0
  AND vh.histo_destruction IS NULL
  /*@intervenant_id=tve.intervenant_id*/
  /*@annee_id=tve.annee_id*/
GROUP BY
  tve.intervenant_id,
  tve.structure_id,
  tvh.code