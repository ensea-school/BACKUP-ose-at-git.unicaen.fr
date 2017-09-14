SELECT
  ti.code,
  si.libelle,
  i.id,
  i.source_code,
  i.nom_usuel,
  i.prenom
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN (select intervenant_id from tbl_workflow group by intervenant_id having sum(realisation) = 0) w ON w.intervenant_id = i.id
WHERE
  i.annee_id = 2017
  AND ti.code = 'P'
  --AND si.libelle = 'BIATSS'

;
select intervenant_id from tbl_workflow group by intervenant_id having sum(realisation) = 0