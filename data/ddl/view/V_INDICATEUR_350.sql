CREATE OR REPLACE FORCE VIEW V_INDICATEUR_350 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id,
  MAX(m.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN tbl_mission tm ON tm.intervenant_id = w.intervenant_id
  JOIN mission m ON tm.mission_id = m.id
WHERE
  w.etape_code = 'mission_validation'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.intervenant_id,
  w.structure_id