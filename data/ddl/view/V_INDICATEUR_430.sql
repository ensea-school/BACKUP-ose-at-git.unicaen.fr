CREATE OR REPLACE FORCE VIEW V_INDICATEUR_430 AS
SELECT
  w.intervenant_id,
  w.structure_id
FROM
	intervenant i
	JOIN PARAMETRE p ON p.VALEUR = 'avenant'
	JOIN tbl_workflow w ON w.intervenant_id = i.id
	JOIN (
    SELECT DISTINCT
      c.intervenant_id,
      c.structure_id
    FROM
    	contrat c
    WHERE
      c.type_contrat_id = 1 --a déjà un contrat de type 'CONTRAT'
      AND c.histo_destruction IS NULL
  ) hc ON hc.intervenant_id = i.id
	LEFT JOIN contrat c ON c.intervenant_id = i.id AND c.structure_id = w.structure_id AND c.histo_destruction IS NULL
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation < w.objectif
  AND c.id IS NULL