-- recherche d'intervenants
SELECT
  id,
  nom_patronymique, nom_usuel, prenom, code code_harpege
FROM
  intervenant 
WHERE
  annee_id = 2017
  AND ose_divers.str_reduce(nom_usuel || nom_patronymique) LIKE '%lecluse%'
  --AND code = '' -- Code Harpège
;

-- liste des statuts
SELECT
  id, libelle
FROM
  statut_intervenant si
WHERE
  si.histo_destruction IS NULL
;


INSERT INTO intervenant_saisie(
  id,
  intervenant_id,
  statut_id
) VALUES (
  intervenant_saisie_id_seq.nextval, -- id
  1, -- intervenant_id
  10, -- statut_id
);