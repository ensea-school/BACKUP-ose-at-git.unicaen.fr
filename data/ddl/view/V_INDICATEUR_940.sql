CREATE OR REPLACE FORCE VIEW V_INDICATEUR_940 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  MAX(mep.histo_modification) AS "Date de modification"
FROM
  tbl_paiement tm
  JOIN tbl_workflow w ON w.intervenant_id = tm.intervenant_id
  JOIN mise_en_paiement mep ON mep.id = tm.mise_en_paiement_id
WHERE
  tm.periode_paiement_id IS NULL
  AND w.etape_code = 'saisie_mep'
  AND w.type_intervenant_code = 'P'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  tm.intervenant_id,
  w.intervenant_id,
  w.structure_id