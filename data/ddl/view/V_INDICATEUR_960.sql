CREATE OR REPLACE FORCE VIEW V_INDICATEUR_960 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  MAX(mep.histo_modification) AS "Date de modification"
FROM
    tbl_workflow w
    LEFT JOIN mission m ON m.intervenant_id = w.intervenant_id
    LEFT JOIN mise_en_paiement mep ON mep.mission_id = m.id
WHERE
  mep.histo_destruction IS NULL
  AND mep.periode_paiement_id IS NULL
  AND w.etape_code = 'SAISIE_MEP'
  AND w.type_intervenant_code = 'S'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  w.annee_id,
  w.intervenant_id,
  w.structure_id