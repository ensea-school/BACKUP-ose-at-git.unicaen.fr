CREATE OR REPLACE FORCE VIEW V_INDICATEUR_570 AS
SELECT
  w.intervenant_id,
  i.structure_id,
  s.libelle_court "Composantes concernées"
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = wc.intervenant_id
  JOIN statut s ON s.id = i.statut_id
  JOIN structure s ON s.id = w.structure_id
WHERE
  w.etape_code = 'REFERENTIEL_VALIDATION_REALISE'
  AND w.objectif > w.realisation
  AND w.atteignable = 1

  AND (wc.etape_code = 'CLOTURE_REALISE' AND s.cloture =1)
  AND wc.objectif = wc.realisation
  AND w.structure_id <> i.structure_id