CREATE OR REPLACE FORCE VIEW V_INDICATEUR_560 AS
SELECT
  w.intervenant_id,
  i.structure_id,
  Max(his.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN TYPE_VOLUME_HORAIRE tvh ON tvh.CODE = 'REALISE'
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = wc.intervenant_id
  JOIN structure s ON s.id = w.structure_id
  JOIN statut s ON s.id = i.statut_id
  LEFT JOIN HISTO_INTERVENANT_SERVICE his ON his.INTERVENANT_ID = w.intervenant_id
WHERE
  w.etape_code = 'REFERENTIEL_VALIDATION_REALISE'
  AND w.objectif > w.realisation
  AND w.atteignable = 1

  AND (wc.etape_code = 'CLOTURE_REALISE' AND s.cloture = 1)
  AND wc.objectif = wc.realisation
  AND w.structure_id = i.structure_id

  AND his.TYPE_VOLUME_HORAIRE_ID = tvh.ID
  AND his.REFERENTIEL = 1
GROUP BY
  w.annee_id,
  w.intervenant_id,
  i.structure_id