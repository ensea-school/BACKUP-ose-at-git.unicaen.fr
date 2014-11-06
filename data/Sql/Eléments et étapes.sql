select
  ep.id id,
  ep.source_code code,
  ep.libelle libelle,
  e.id etape_id,
  cp.etape_id cp_id,
  e.libelle etape_libelle,
  CASE WHEN cp.etape_id <> ep.etape_id THEN 'SECONDAIRE' ELSE 'PRINCIPALE' END statut
from
  element_pedagogique ep
  LEFT JOIN chemin_pedagogique cp ON cp.element_pedagogique_id = ep.id AND cp.histo_destruction IS NULL
  JOIN etape e ON e.id = ep.etape_id OR e.id = cp.etape_id AND e.histo_destruction IS NULL
WHERE
  ep.histo_destruction IS NULL
  AND ep.source_code = 'TIA14AH'
order by
  code, statut, etape_libelle
  
  
