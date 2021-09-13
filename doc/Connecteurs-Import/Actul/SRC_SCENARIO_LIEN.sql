CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_LIEN AS
WITH al AS (
  SELECT DISTINCT
    'nep_' || al.z_noeud_sup_id || '_lep_' ||al.z_noeud_sup_id z_lien_id,
    1                             actif,
    1                             poids,
    al.choix_minimum              choix_minimum,
    al.choix_maximum              choix_maximum,
    'Actul'                       z_source_id
  FROM
         act_noeud          an
    JOIN act_lien           al ON al.z_noeud_sup_id = an.source_code
  WHERE
    al.choix_minimum IS NOT NULL OR al.choix_maximum IS NOT NULL -- si rien n'est spécifié => pas de synchro pour rien
)
SELECT
  sc.id                         scenario_id,
  l.id                          lien_id,
  al.actif                      actif,
  al.poids                      poids,
  al.choix_minimum              choix_minimum,
  al.choix_maximum              choix_maximum,
  s.id                          source_id,
  l.source_code || '_' || sc.id source_code
FROM
                          al
  JOIN scenario           sc ON sc.histo_destruction  IS NULL
  LEFT JOIN lien           l on l.source_code = al.z_lien_id
  LEFT JOIN source         s ON s.code = al.z_source_id
  LEFT JOIN scenario_lien sl ON sl.scenario_id = sc.id AND sl.lien_id = l.id AND sl.histo_destruction IS NULL
  LEFT JOIN source       sls ON sls.id = sl.source_id
WHERE
  COALESCE(sls.importable,1) = 1 -- s'il y a déjà des data saisies en local => on les garde