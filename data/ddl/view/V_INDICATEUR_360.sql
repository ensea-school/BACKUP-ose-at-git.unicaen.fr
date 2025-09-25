CREATE OR REPLACE FORCE VIEW V_INDICATEUR_360 AS
SELECT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
  JOIN tbl_mission tm ON tm.intervenant_id = w.intervenant_id
WHERE
  w.etape_code = 'mission_saisie_realise'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.intervenant_id,
  w.structure_id
HAVING
  SUM(tm.heures_realisees_saisies) = 0