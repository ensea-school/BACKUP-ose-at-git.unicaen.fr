CREATE OR REPLACE FORCE VIEW V_INDICATEUR_130 AS
SELECT DISTINCT
  s.intervenant_id intervenant_id,
  i.structure_id structure_id
FROM
  tbl_service s
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN statut     si ON si.id = i.statut_id
WHERE
  si.code = 'BIATSS'
  AND s.type_volume_horaire_code = 'PREVU'
  AND s.intervenant_structure_id <> s.structure_id
  AND s.valide > 0
  AND s.structure_id IS NOT NULL