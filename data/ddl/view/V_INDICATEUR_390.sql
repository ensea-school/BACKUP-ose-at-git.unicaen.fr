CREATE OR REPLACE FORCE VIEW V_INDICATEUR_390 AS
SELECT DISTINCT
  w.intervenant_id,
  CASE
  WHEN w.structure_id IS NOT NULL
    THEN w.structure_id
    ELSE i.structure_id
  END structure_id
  FROM tbl_workflow w
  JOIN intervenant  i ON w.intervenant_id = i.id
  JOIN mission mi ON mi.intervenant_id = i.id
  JOIN volume_horaire_mission vhm ON vhm.mission_id = mi.id
  JOIN statut      si ON si.id = i.statut_id
  LEFT JOIN contrat c ON c.mission_id = vhm.mission_id
WHERE
  w.atteignable = 1
  AND w.type_intervenant_code = 'S'
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > w.realisation
  AND i.histo_destruction IS NULL
  AND si.histo_destruction IS NULL
  AND si.contrat = 1
  AND vhm.contrat_id IS NULL
  AND c.id IS NOT NULL