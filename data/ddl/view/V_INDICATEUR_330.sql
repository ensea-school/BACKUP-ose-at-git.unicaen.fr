CREATE OR REPLACE FORCE VIEW V_INDICATEUR_330 AS
WITH has_contrat AS (
  SELECT DISTINCT
    i.id            intervenant_id,
    i.annee_id      annee_id,
    c.structure_id  structure_id
  FROM
  	intervenant i
  	JOIN contrat c ON c.intervenant_id = i.iD
  WHERE
    c.type_contrat_id = 1 --a déjà un contrat de type 'CONTRAT'
    AND c.histo_destruction IS NULL
  	AND i.histo_destruction IS NULL
)
SELECT
  rownum id,
  w.annee_id,
  w.intervenant_id,
  w.structure_id
FROM
	intervenant i
	JOIN tbl_workflow w ON w.intervenant_id = i.id
	JOIN has_contrat hc ON hc.intervenant_id = i.id
	LEFT JOIN contrat c ON c.intervenant_id = i.id AND c.structure_id = w.structure_id AND c.histo_destruction IS NULL
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation < w.objectif
  AND c.id IS NULL