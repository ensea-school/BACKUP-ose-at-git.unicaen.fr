CREATE OR REPLACE FORCE VIEW V_INDICATEUR_440 AS
SELECT
  s.intervenant_id,
  s.structure_id
FROM
  tbl_service s
  JOIN tbl_workflow w ON w.intervenant_id = s.intervenant_id AND (w.structure_id = s.structure_id OR w.structure_id is NULL)
WHERE
  s.type_intervenant_code = 'V'
  AND s.type_volume_horaire_code = 'PREVU'
  AND nbvh <> valide
  AND w.etape_code = 'contrat'
  AND w.atteignable = 1
  AND w.objectif > 0
  AND w.realisation = w.objectif