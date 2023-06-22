CREATE OR REPLACE FORCE VIEW V_INDICATEUR_950 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  Max(vh.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  LEFT JOIN type_volume_horaire tvh ON tvh.code = 'REALISE'
  LEFT JOIN service s ON s.intervenant_id = w.intervenant_id
  LEFT JOIN volume_horaire vh ON s.id = vh.service_id
WHERE
  w.etape_code = 'DEMANDE_MEP'
  AND w.type_intervenant_code = 'S'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.intervenant_id, w.structure_id