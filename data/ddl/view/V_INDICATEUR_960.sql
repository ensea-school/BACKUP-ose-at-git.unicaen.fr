CREATE OR REPLACE FORCE VIEW V_INDICATEUR_960 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  MAX(mep.histo_modification) AS "Date de modification"
FROM
    tbl_workflow w
    LEFT JOIN mission m ON m.intervenant_id = w.intervenant_id
    LEFT JOIN tbl_paiement tp ON tp.mission_id = m.id
    LEFT JOIN mise_en_paiement mep ON mep.id = tp.mise_en_paiement_id
WHERE
  tp.periode_paiement_id IS NULL
  AND w.etape_code = 'saisie_mep'
  AND w.type_intervenant_code = 'S'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.annee_id,
  w.intervenant_id,
  w.structure_id