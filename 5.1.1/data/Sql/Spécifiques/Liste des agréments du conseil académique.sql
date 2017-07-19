select 
  an.libelle,
  i.nom_usuel || ' ' || i.prenom intervenant,
  i.source_code n_harpege
from 
  v_tbl_agrement a
  JOIN type_agrement ta on ta.id = a.type_agrement_id AND ta.code = 'CONSEIL_ACADEMIQUE'
  JOIN intervenant i ON i.id = a.intervenant_id
  JOIN annee an on an.id = a.annee_id
WHERE
  a.agrement_id IS NOT NULL