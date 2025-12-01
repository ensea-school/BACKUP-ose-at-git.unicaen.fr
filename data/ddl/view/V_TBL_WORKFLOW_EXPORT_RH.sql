CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_EXPORT_RH AS
SELECT
  'export_rh'       etape_code,
  i.id              intervenant_id,
  null              structure_id,
  1                 objectif,
  CASE WHEN i.export_date IS NULL THEN 0 ELSE 1 END partiel,
  CASE WHEN i.export_date IS NULL THEN 0 ELSE 1 END realisation
FROM
  intervenant i
WHERE
  1=1
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/