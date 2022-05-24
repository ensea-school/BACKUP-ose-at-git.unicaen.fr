CREATE OR REPLACE FORCE VIEW V_INDICATEUR_450 AS
SELECT DISTINCT
  c.intervenant_id,
  c.structure_id
FROM
  contrat                c
  JOIN contrat_fichier  cf ON cf.contrat_id = c.id
  JOIN fichier           f ON f.id = cf.fichier_id
                          AND f.histo_destruction IS NULL
WHERE
  c.histo_destruction IS NULL