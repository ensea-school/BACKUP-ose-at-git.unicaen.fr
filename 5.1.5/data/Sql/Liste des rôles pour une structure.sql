select
  p.nom_usuel, p.prenom,
  tr.code, tr.libelle
from
  role
  join type_role tr on tr.id = role.type_id
  join structure s on s.id = role.structure_id
  join personnel p on p.id = role.personnel_id
where
  role.histo_destruction is null
  and s.source_code = 'U08';