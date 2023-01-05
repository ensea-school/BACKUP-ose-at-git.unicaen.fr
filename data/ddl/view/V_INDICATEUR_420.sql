CREATE OR REPLACE FORCE VIEW V_INDICATEUR_420 AS
SELECT DISTINCT
  w.intervenant_id,
  CASE
    WHEN w.structure_id IS NOT NULL
    THEN w.structure_id
    ELSE i.structure_id
  END structure_id
  FROM tbl_workflow w
  JOIN intervenant  i ON w.intervenant_id = i.id
  JOIN statut      si ON si.id = i.statut_id
  LEFT JOIN contrat c ON c.intervenant_id = w.intervenant_id AND c.histo_destruction IS NULL
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation = 0
  AND i.histo_destruction IS NULL
  AND si.histo_destruction IS NULL
  AND c.id IS NULL
  AND si.contrat = 1