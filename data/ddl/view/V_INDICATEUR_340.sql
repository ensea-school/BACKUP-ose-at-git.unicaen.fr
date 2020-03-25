CREATE OR REPLACE FORCE VIEW V_INDICATEUR_340 AS
SELECT
  rownum id,
  s.annee_id,
  s.intervenant_id,
  s.structure_id
FROM
  tbl_service s
  JOIN tbl_workflow w ON w.intervenant_id = s.intervenant_id AND w.structure_id = s.structure_id
WHERE
  s.type_intervenant_code = 'V'
  AND s.type_volume_horaire_code = 'PREVU'
  AND nbvh <> valide
  AND w.etape_code = 'CONTRAT'
  AND w.atteignable = 1
  AND w.objectif > 0
  AND w.realisation = w.objectif