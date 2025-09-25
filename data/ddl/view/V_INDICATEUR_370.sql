CREATE OR REPLACE FORCE VIEW V_INDICATEUR_370 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id,
  MAX(vhm.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN mission m ON m.intervenant_id = w.intervenant_id
  JOIN volume_horaire_mission vhm ON m.id = vhm.mission_id
WHERE
  w.etape_code = 'mission_validation_realise'
  AND w.type_intervenant_code = 'S'
  and w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.intervenant_id,
  w.structure_id