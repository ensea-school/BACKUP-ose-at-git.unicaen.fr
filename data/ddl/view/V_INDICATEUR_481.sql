CREATE OR REPLACE FORCE VIEW V_INDICATEUR_481 AS
SELECT DISTINCT
  c.intervenant_id,
  COALESCE(c.structure_id, i.structure_id) structure_id
FROM
  contrat                c
  JOIN tbl_workflow w ON w.intervenant_id = c.intervenant_id AND (w.structure_id = c.structure_id OR w.structure_id is NULL) AND w.etape_code = 'CONTRAT' AND w.atteignable = 1
  JOIN unicaen_signature_process usp ON usp.id = c.process_signature_id
  JOIN intervenant i ON c.intervenant_id = i.id
WHERE
  c.histo_destruction IS NULL
  --On vérifie que le statut est en waiting
  AND usp.status = 105