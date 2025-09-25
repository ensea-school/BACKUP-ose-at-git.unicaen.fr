CREATE OR REPLACE FORCE VIEW V_INDICATEUR_430 AS
SELECT DISTINCT
  w.intervenant_id,
  CASE
    WHEN w.structure_id IS NOT NULL
    THEN w.structure_id
    ELSE i.structure_id
  END structure_id
FROM
  intervenant i
  JOIN tbl_workflow w ON w.intervenant_id = i.id
  JOIN tbl_contrat tblc ON tblc.intervenant_id = i.id AND tblc.volume_horaire_index = 0
  JOIN type_service ts ON tblc.type_service_id = ts.id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'contrat'
  AND tblc.contrat_id IS NULL
  AND tblc.contrat_parent_id IS NOT NULL
  AND tblc.actif = 1
  AND ts.code != 'MIS'