CREATE OR REPLACE FORCE VIEW V_INDICATEUR_360 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
  JOIN tbl_mission tm ON tm.intervenant_id = w.intervenant_id
    LEFT JOIN ( SELECT tm2.intervenant_id, SUM(tm2.heures_realisees_saisies) heures
                FROM tbl_mission tm2
                GROUP BY tm2.intervenant_id
            ) tmh ON tm.intervenant_id = tmh.intervenant_id
WHERE
  w.etape_code = 'MISSION_SAISIE_REALISE'
  AND w.type_intervenant_code = 'S'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
  AND tmh.heures = 0
GROUP BY
  w.intervenant_id,
  w.structure_id