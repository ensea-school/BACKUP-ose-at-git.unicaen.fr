CREATE OR REPLACE FORCE VIEW V_REF_INTERVENANT AS
SELECT DISTINCT
  i.source_code C_INTERVENANT
FROM
  tbl_service s
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN annee a ON a.id = s.annee_id
WHERE
  s.nbvh > 0
  AND SYSDATE BETWEEN a.date_debut AND a.date_fin