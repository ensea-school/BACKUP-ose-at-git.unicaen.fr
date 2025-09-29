CREATE OR REPLACE FORCE VIEW V_INDICATEUR_110 AS
SELECT
  d.intervenant_id,
  i.structure_id,
  MAX(ido.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
  JOIN INTERVENANT_DOSSIER ido ON ido.ID = d.DOSSIER_ID
WHERE
  d.dossier_id IS NOT NULL
  AND d.avant_recrutement_attendue = d.avant_recrutement_realisee
  AND d.apres_recrutement_attendue = d.apres_recrutement_realisee
  AND d.validation_id IS NULL
  AND d.actif = 1
GROUP BY (d.intervenant_id, i.STRUCTURE_ID)