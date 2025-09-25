CREATE OR REPLACE FORCE VIEW V_INDICATEUR_530 AS
SELECT DISTINCT
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN intervenant i ON i.id = w.intervenant_id
  JOIN tbl_workflow w2 ON w2.intervenant_id = w.intervenant_id
WHERE
  w.etape_code = 'cloture_realise'
  AND w2.etape_code = 'enseignement_saisie_realise'
  AND w.type_intervenant_code = 'P'
  AND w.atteignable = 1
  AND w2.atteignable = 1
  AND w.objectif > w.realisation