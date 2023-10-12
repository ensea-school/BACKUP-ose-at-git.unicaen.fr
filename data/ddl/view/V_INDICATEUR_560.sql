CREATE OR REPLACE FORCE VIEW V_INDICATEUR_560 AS
SELECT
  w.intervenant_id,
  i.structure_id,
  Max(his.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN TYPE_VOLUME_HORAIRE tvh ON tvh.CODE = 'REALISE'
  LEFT JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id AND wc.etape_code = 'CLOTURE_REALISE'
  JOIN intervenant i ON i.id = w.intervenant_id
  JOIN structure s ON s.id = w.structure_id
  JOIN statut s ON s.id = i.statut_id AND s.type_intervenant_id = (SELECT id FROM type_intervenant ti WHERE code = 'P')
  LEFT JOIN HISTO_INTERVENANT_SERVICE his ON his.INTERVENANT_ID = w.intervenant_id
WHERE
  w.etape_code = 'REFERENTIEL_VALIDATION_REALISE'
  AND w.objectif > w.realisation
  AND w.atteignable = 1
  AND CASE WHEN s.cloture = 1 AND wc.objectif = wc.realisation THEN 1
  		   WHEN s.cloture = 0 THEN 1
  		   ELSE 0 END  = 1
  AND w.structure_id = i.structure_id
  AND his.TYPE_VOLUME_HORAIRE_ID = tvh.ID
  AND his.REFERENTIEL = 1
GROUP BY
  w.annee_id,
  w.intervenant_id,
  i.structure_id



