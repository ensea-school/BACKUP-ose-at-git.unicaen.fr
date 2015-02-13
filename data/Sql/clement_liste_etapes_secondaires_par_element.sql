select code_enseignement, libelle_enseignement, code_formation, libelle_formation from (
select
  ep.source_code code_enseignement,
  ep.libelle libelle_enseignement,
  ech.source_code code_formation,
  ech.libelle libelle_formation,
  case when ep.etape_id = ech.id THEN 'principale' ELSE 'secondaire' END statut,
  count(*) over( partition by ep.source_code) cc
from
  element_pedagogique ep
  join chemin_pedagogique ch on 1 = ose_divers.comprise_entre(ch.histo_creation,ch.histo_destruction) and ch.element_pedagogique_id = ep.id
  join etape ech on 1 = ose_divers.comprise_entre(ech.histo_creation,ech.histo_destruction) and ech.id = ch.etape_id
where
  1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction)
) t1 where cc > 1
ORDER BY
  code_enseignement, code_formation