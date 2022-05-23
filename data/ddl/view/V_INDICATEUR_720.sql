CREATE OR REPLACE FORCE VIEW V_INDICATEUR_720 AS
SELECT DISTINCT
  s.intervenant_id intervenant_id,
  s.intervenant_structure_id structure_id
FROM
  tbl_service s
WHERE
  s.type_intervenant_code = 'P'
  AND s.type_volume_horaire_code = 'PREVU'
  AND s.intervenant_structure_id <> s.structure_id
  AND s.valide > 0
  AND s.structure_id IS NOT NULL