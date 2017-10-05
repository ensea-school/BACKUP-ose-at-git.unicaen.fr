SELECT
  i.nom_usuel,
  i.prenom,
  si.libelle
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN (select intervenant_id from tbl_workflow group by intervenant_id having sum(realisation) = 0) w ON w.intervenant_id = i.id
WHERE
  i.annee_id = 2017
  AND ti.code = 'E'
  --AND si.libelle = 'BIATSS'

;
select intervenant_id from tbl_workflow group by intervenant_id having sum(realisation) = 0