CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_CANDIDATURE AS
SELECT
  'candidature_saisie'                                      etape_code,
  c.intervenant_id                                          intervenant_id,
  c.structure_id                                            structure_id,
  SUM(1)                                                    objectif,
  SUM(CASE WHEN c.candidature_id IS NULL THEN 0 ELSE 1 END) partiel,
  SUM(CASE WHEN c.candidature_id IS NULL THEN 0 ELSE 1 END) realisation
FROM
  tbl_candidature c
WHERE
  c.actif = 1
  /*@INTERVENANT_ID=c.intervenant_id*/
  /*@ANNEE_ID=c.annee_id*/
GROUP BY
  c.intervenant_id, c.structure_id

UNION ALL

SELECT
  'candidature_validation'                                  etape_code,
  c.intervenant_id                                          intervenant_id,
  c.structure_id                                            structure_id,
  SUM(CASE WHEN c.candidature_id IS NULL THEN 0 ELSE 1 END) objectif,
  SUM(c.acceptee)                                           partiel,
  SUM(c.acceptee)                                           realisation
FROM
  tbl_candidature c
WHERE
  c.actif = 1
  /*@INTERVENANT_ID=c.intervenant_id*/
  /*@ANNEE_ID=c.annee_id*/
GROUP BY
  c.intervenant_id, c.structure_id