SELECT
  i.id i_id,
  i.nom_usuel || ' ' || i.prenom i_nom,
  imd.*
FROM
  indic_modif_dossier imd
  JOIN intervenant i ON i.id = imd.intervenant_id
WHERE
  i.id = 4554;
  
  
SELECT
  *
FROM 
  dossier d
WHERE
  d.intervenant_id = 4554;
  
  
