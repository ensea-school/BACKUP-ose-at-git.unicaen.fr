CREATE OR REPLACE FORCE VIEW SRC_CHEMIN_PEDAGOGIQUE AS
SELECT DISTINCT
  e.id                                                        etape_id,
  ep.id                                                       element_pedagogique_id,
  ROW_NUMBER() OVER (PARTITION BY e.id, a.id ORDER BY ROWNUM) ordre,
  s.id                                                        source_id,
  pcd.source_code || '_' || a.id                              source_code
FROM
  peg_chemin_pedagogique pcd
  JOIN annee a ON a.id BETWEEN pcd.annee_debut AND pcd.annee_fin
  JOIN source s ON s.code = 'Pegase'
  LEFT JOIN element_pedagogique ep ON ep.source_code = pcd.z_element_pedagogique_id
                                    AND ep.annee_id = a.id
  LEFT JOIN etape e ON e.source_code = pcd.z_etape_id
                    AND e.annee_id = a.id
