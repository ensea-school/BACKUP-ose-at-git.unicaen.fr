CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_CLOTURE AS
SELECT
  'cloture_realise' etape_code,
  c.intervenant_id  intervenant_id,
  null              structure_id,
  1                 objectif,
  c.cloture         partiel,
  c.cloture         realisation
FROM
  tbl_cloture_realise c
WHERE
  c.actif = 1
  /*@intervenant_id=c.intervenant_id*/
  /*@annee_id=c.annee_id*/