CREATE OR REPLACE FORCE VIEW V_INDICATEUR_920 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  Max(vh.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  LEFT JOIN type_volume_horaire tvh ON tvh.code = 'REALISE'
  LEFT JOIN contrat c ON c.intervenant_id = w.intervenant_id
  LEFT JOIN volume_horaire vh ON c.id = vh.contrat_id
WHERE
  w.etape_code = 'DEMANDE_MEP'
  AND w.type_intervenant_code = 'P'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.intervenant_id, w.structure_id