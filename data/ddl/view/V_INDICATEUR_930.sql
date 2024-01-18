CREATE OR REPLACE FORCE VIEW V_INDICATEUR_930 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  MAX(histo_modification) AS "Date de modification"
FROM
  tbl_paiement tp
  LEFT JOIN mise_en_paiement mep ON mep.id = tp.mise_en_paiement_id
  LEFT JOIN formule_resultat_service frs ON frs.id = mep.formule_res_service_id
  LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
  JOIN formule_resultat fr ON fr.id = COALESCE(frs.formule_resultat_id,frsr.formule_resultat_id)
  JOIN tbl_workflow w ON w.intervenant_id = fr.intervenant_id
WHERE
  tp.periode_paiement_id IS NULL
  AND w.etape_code = 'SAISIE_MEP'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY
  fr.intervenant_id,
  w.annee_id,
  w.intervenant_id,
  w.structure_id