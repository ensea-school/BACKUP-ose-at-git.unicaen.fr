CREATE OR REPLACE FORCE VIEW V_TBL_CANDIDATURE AS
SELECT
  i.annee_id                                annee_id,
  i.id                                      intervenant_id,
  COALESCE(oe.structure_id, i.structure_id) structure_id,
  c.offre_emploi_id                         offre_emploi_id,
  c.id                                      candidature_id,
  v.id                                      validation_id,
  1                                                            actif,
  CASE WHEN v.id IS NULL AND c.motif IS NULL THEN 0 ELSE 1 END reponse,
  CASE WHEN v.id IS NULL THEN 0 ELSE 1 END                     acceptee,
  CASE WHEN c.motif IS NULL THEN 0 ELSE 1 END                  refusee
FROM
            intervenant   i
       JOIN statut       si ON si.id = i.statut_id
  LEFT JOIN candidature   c ON c.intervenant_id = i.id AND c.histo_destruction IS NULL
  LEFT JOIN offre_emploi oe ON oe.id = c.offre_emploi_id
  LEFT JOIN validation    v ON v.id = c.validation_id AND v.histo_destruction IS NULL
WHERE
  i.histo_destruction IS NULL
  AND si.offre_emploi_postuler = 1