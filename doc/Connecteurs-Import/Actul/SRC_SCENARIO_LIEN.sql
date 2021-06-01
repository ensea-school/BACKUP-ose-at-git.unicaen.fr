CREATE OR REPLACE FORCE VIEW src_scenario_lien AS
SELECT
  sc.id                         scenario_id,
  l.id                          lien_id,
  --1                             actif,
  --1                             poids,
  al.choix_minimum              choix_minimum,
  al.choix_maximum              choix_maximum,
  s.id                          source_id,
  l.source_code || '_' || sc.id source_code
FROM
       act_noeud          an
  JOIN act_lien           al ON al.z_noeud_sup_id = an.source_code
  JOIN scenario           sc ON sc.histo_destruction  IS NULL
  LEFT JOIN lien           l on l.source_code = 'nep_' || an.source_code || '_lep_' || an.source_code
  LEFT JOIN source         s ON s.code = al.z_source_id
  LEFT JOIN scenario_lien sl ON sl.scenario_id = sc.id AND sl.lien_id = l.id AND sl.histo_destruction IS NULL
  LEFT JOIN source       sls ON sls.id = sl.source_id
WHERE
  COALESCE(sls.importable,1) <> 0 -- s'il y a déjà des data saisies en local => on les garde
  AND (al.choix_minimum IS NOT NULL OR al.choix_maximum IS NOT NULL) -- si rien n'est spécifié => pas de synchro pour rien
