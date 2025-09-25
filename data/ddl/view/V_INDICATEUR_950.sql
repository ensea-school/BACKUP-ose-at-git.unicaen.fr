CREATE OR REPLACE FORCE VIEW V_INDICATEUR_950 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  Max(vhm.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  LEFT JOIN type_volume_horaire tvh ON tvh.code = 'REALISE'
  LEFT JOIN mission m ON m.intervenant_id = w.intervenant_id
  LEFT JOIN volume_horaire_mission vhm ON vhm.mission_id = m.id
WHERE
  w.etape_code = 'demande_mep'
  AND w.type_intervenant_code = 'S'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.intervenant_id, w.structure_id