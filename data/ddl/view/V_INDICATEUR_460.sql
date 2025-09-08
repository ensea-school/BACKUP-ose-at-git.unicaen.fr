CREATE OR REPLACE FORCE VIEW V_INDICATEUR_460 AS
SELECT DISTINCT
  c.intervenant_id,
  COALESCE(c.structure_id, i.structure_id) structure_id
FROM
  contrat                c
    JOIN tbl_workflow w ON w.intervenant_id = c.intervenant_id AND (w.structure_id = c.structure_id OR w.structure_id is NULL) AND w.etape_code = 'CONTRAT' AND w.atteignable = 1
    JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
    JOIN intervenant i ON c.intervenant_id = i.id
    LEFT JOIN contrat_fichier cf ON cf.contrat_id = c.id
    LEFT JOIN fichier f ON f.id = cf.fichier_id AND f.histo_destruction IS NULL
WHERE
  c.histo_destruction IS NULL
  AND f.id IS NULL