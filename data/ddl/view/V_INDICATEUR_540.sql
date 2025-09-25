CREATE OR REPLACE FORCE VIEW V_INDICATEUR_540 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id,
  Max(his.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN TYPE_VOLUME_HORAIRE tvh ON tvh.CODE = 'REALISE'
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  LEFT JOIN HISTO_INTERVENANT_SERVICE his ON his.INTERVENANT_ID = w.intervenant_id
WHERE
  w.etape_code = 'enseignement_validation_realise'
  AND w.type_intervenant_code = 'P'
  AND w.objectif > w.realisation
  AND w.atteignable = 1
  AND wc.etape_code = 'cloture_realise'
  AND wc.objectif = wc.realisation
  AND his.TYPE_VOLUME_HORAIRE_ID = tvh.ID
  AND his.REFERENTIEL = 0
GROUP BY
  w.intervenant_id,
  w.structure_id