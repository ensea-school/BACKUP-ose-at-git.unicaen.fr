CREATE OR REPLACE FORCE VIEW V_INDICATEUR_620 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id,
  Max(his.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN TYPE_VOLUME_HORAIRE tvh ON tvh.CODE = 'PREVU'
  LEFT JOIN HISTO_INTERVENANT_SERVICE his ON his.INTERVENANT_ID = w.intervenant_id
WHERE
  w.etape_code = 'SERVICE_VALIDATION'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
  AND his.TYPE_VOLUME_HORAIRE_ID = tvh.ID
  AND his.REFERENTIEL = 0
GROUP BY
  w.intervenant_id,
  w.structure_id