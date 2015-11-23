SELECT
  c.id c_id,
  c.histo_creation,
  i.prenom || ' ' || i.nom_usuel intervenant,
  s.libelle_court structure
FROM
  contrat c
  JOIN intervenant i ON i.id = c.intervenant_id
  JOIN type_contrat tc ON tc.id = c.type_contrat_id
  JOIN structure s ON s.id = c.structure_id
WHERE
  i.id = 4554;