CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_PJ AS
SELECT
  e.code                                                    etape_code,
  pj.intervenant_id                                         intervenant_id,
  null                                                      structure_id,
  CASE
    WHEN e.code = 'pj_saisie' THEN pj.demandees
    WHEN e.code = 'pj_validation' THEN pj.demandees
  END                                                       objectif,
  CASE
    WHEN e.code = 'pj_saisie' THEN pj.fournies
    WHEN e.code = 'pj_validation' THEN pj.validees
  END                                                       partiel,
  CASE
    WHEN e.code = 'pj_saisie' THEN pj.fournies
    WHEN e.code = 'pj_validation' THEN pj.validees
  END                                                       realisation
FROM
  (
  SELECT
    intervenant_id,
    SUM(demandee) demandees,
    SUM(CASE WHEN obligatoire = 0 THEN 1 ELSE fournie END)  fournies,
    SUM(CASE WHEN obligatoire = 0 THEN 1 ELSE validee END)  validees,
    SUM(CASE WHEN obligatoire = 0 THEN 1 ELSE 0 END)        facultatives
  FROM
    tbl_piece_jointe tpj
  WHERE
    demandee > 0
    /*@intervenant_id=tpj.intervenant_id*/
    /*@annee_id=tpj.annee_id*/
  GROUP BY
    annee_id,
    intervenant_id
) pj
  JOIN (
          SELECT 'pj_saisie'      code FROM dual
    UNION SELECT 'pj_validation'  code FROM dual
  ) e ON (
       (e.code = 'pj_saisie'     AND pj.demandees > 0)
    OR (e.code = 'pj_validation' AND pj.fournies  > 0)
  )