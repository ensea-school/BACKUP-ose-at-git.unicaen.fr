CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_AGREMENT AS
SELECT
  lower(ta.code)                                            etape_code,
  a.intervenant_id                                          intervenant_id,
  a.structure_id                                            structure_id,
  1                                                         objectif,
  CASE WHEN a.agrement_id IS NULL THEN 0 ELSE 1 END         partiel,
  CASE WHEN a.agrement_id IS NULL THEN 0 ELSE 1 END         realisation
FROM
  tbl_agrement a
  JOIN type_agrement ta ON ta.id = a.type_agrement_id
WHERE
  1=1
  /*@intervenant_id=a.intervenant_id*/
  /*@annee_id=a.annee_id*/