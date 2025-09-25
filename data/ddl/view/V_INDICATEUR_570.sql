CREATE OR REPLACE FORCE VIEW V_INDICATEUR_570 AS
SELECT
  w.intervenant_id,
  i.annee_id,
  w.structure_id,
  s.libelle_court "Composantes concernées"
FROM
  tbl_workflow w
  LEFT JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id AND wc.etape_code = 'cloture_realise'
  JOIN intervenant i ON i.id = w.intervenant_id
  JOIN statut s ON s.id = i.statut_id AND s.type_intervenant_id = (SELECT id FROM type_intervenant ti WHERE code = 'P')
  JOIN structure s ON s.id = w.structure_id
WHERE
  w.etape_code = 'referentiel_validation_realise'
  AND w.objectif > w.realisation
  AND w.atteignable = 1
  AND CASE WHEN s.cloture = 1 AND wc.objectif = wc.realisation THEN 1
  		   WHEN s.cloture = 0 THEN 1
  		   ELSE 0 END  = 1
  AND w.structure_id <> i.structure_id