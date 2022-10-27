CREATE OR REPLACE FORCE VIEW V_INDICATEUR_920 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id,
  Max(v.HISTO_CREATION) AS "Date de modification"
FROM
  tbl_workflow w
  LEFT JOIN type_volume_horaire tvh ON tvh.code = 'REALISE'
  LEFT JOIN service s ON s.intervenant_id = w.intervenant_id
  LEFT JOIN volume_horaire vh ON s.id = vh.service_id
WHERE
  w.etape_code = 'DEMANDE_MEP'
  AND w.type_intervenant_code = 'P'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
  AND (vhr.TYPE_VOLUME_HORAIRE_ID = tvh.id OR vh.TYPE_VOLUME_HORAIRE_ID = tvh.id)
GROUP BY
  w.intervenant_id, w.structure_id