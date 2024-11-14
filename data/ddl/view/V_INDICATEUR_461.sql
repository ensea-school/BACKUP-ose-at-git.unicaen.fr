--Indicateur listant les contrats éligibles à la signature électronique mais
--qui n'ont pas encore été envoyé dans un circuit de signature
CREATE OR REPLACE FORCE VIEW V_INDICATEUR_461 AS
SELECT DISTINCT
  c.intervenant_id,
  c.structure_id
FROM
  contrat                c
  JOIN intervenant i ON c.intervenant_id = i.id
  JOIN statut s ON s.id = i.statut_id
  JOIN etat_sortie es ON s.contrat_etat_sortie_id = es.id
  JOIN tbl_workflow w ON w.intervenant_id = c.intervenant_id AND (w.structure_id = c.structure_id OR w.structure_id is NULL) AND w.etape_code = 'CONTRAT' AND w.atteignable = 1
  JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN contrat_fichier cf ON cf.contrat_id = c.id
  LEFT JOIN fichier f ON f.id = cf.fichier_id AND f.histo_destruction IS NULL
  WHERE
  --La signature électronique doit avoit été activé sur l'état de sortie du contrat
  es.signature_activation IS NOT NULL
  --Contrat ne doit pas avoir de fichier déposé
  AND f.id IS NULL
  --Contrat ne doit pas avoir de date de retour signé
  AND c.date_retour_signe IS NULL