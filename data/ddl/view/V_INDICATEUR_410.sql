CREATE OR REPLACE FORCE VIEW V_INDICATEUR_410 AS
SELECT
  d.intervenant_id,
  i.structure_id
FROM
  tbl_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
WHERE
  d.dossier_id IS NOT NULL
  /*Complétude des différents bloc dossier*/
  AND d.completude_identite = 1
  AND d.completude_identite_comp = 1
  AND d.completude_contact = 1
  AND d.completude_adresse = 1
  AND d.completude_insee = 1
  AND d.completude_iban = 1
  AND d.completude_employeur = 1
  AND d.completude_autres = 1
  AND d.completude_statut = 1
  AND d.validation_id IS NULL
  AND d.peut_saisir_dossier = 1