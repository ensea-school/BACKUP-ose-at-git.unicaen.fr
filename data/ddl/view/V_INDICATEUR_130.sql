CREATE OR REPLACE FORCE VIEW V_INDICATEUR_130 AS
SELECT
  rownum id,
  t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM (

SELECT DISTINCT
  s.annee_id annee_id,
  s.intervenant_id intervenant_id,
  i.structure_id structure_id
FROM
  tbl_service s
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
WHERE
  si.tem_biatss = 1
  AND s.type_volume_horaire_code = 'PREVU'
  AND s.intervenant_structure_id <> s.structure_id
  AND s.valide > 0
  AND s.structure_id IS NOT NULL

) t