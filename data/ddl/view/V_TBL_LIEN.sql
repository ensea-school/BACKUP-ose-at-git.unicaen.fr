CREATE OR REPLACE FORCE VIEW V_TBL_LIEN AS
SELECT
  l.id             lien_id,
  s.id             scenario_id,
  sl.id            scenario_lien_id,
  l.noeud_sup_id   noeud_sup_id,
  l.noeud_inf_id   noeud_inf_id,
  l.structure_id   structure_id,
  NVL(sl.actif,1)  actif,
  NVL(sl.poids,1)  poids,
  MAX(CASE WHEN 1 = NVL(sl.actif,1) THEN NVL(sl.poids,1) ELSE 0 END) OVER (PARTITION BY l.noeud_sup_id, s.id) max_poids,
  sl.choix_maximum choix_maximum,
  sl.choix_minimum choix_minimum,

  SUM(NVL(sl.actif,1)) OVER (PARTITION BY l.noeud_sup_id, s.id) nb_choix,
  SUM(CASE WHEN 1 = NVL(sl.actif,1) THEN NVL(sl.poids,1) ELSE 0 END) OVER (PARTITION BY l.noeud_sup_id, s.id) total_poids

FROM
  lien l
  JOIN scenario s ON s.histo_destruction IS NULL
  LEFT JOIN scenario_lien sl ON
    sl.lien_id = l.id
    AND sl.scenario_id = s.id
    AND s.histo_destruction IS NULL
WHERE
  l.histo_destruction IS NULL