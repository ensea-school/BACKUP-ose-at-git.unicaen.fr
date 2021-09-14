CREATE OR REPLACE FORCE VIEW src_chemin_pedagogique AS
WITH cp as (  
  SELECT
    l.z_noeud_inf_id z_element_pedagogique_id,
    n.z_etape_id
  FROM (
    SELECT
      l.z_noeud_inf_id z_noeud_inf_id,
      CONNECT_BY_ROOT( z_noeud_sup_id ) z_noeud_sup_id
      --,n.source_code
    FROM
      act_lien l
      --JOIN act_noeud n ON n.source_code = CONNECT_BY_ROOT( z_noeud_sup_id )
    CONNECT BY
      l.z_noeud_sup_id = PRIOR l.z_noeud_inf_id
  ) l
    JOIN act_noeud n ON n.source_code = l.z_noeud_sup_id

  UNION

  SELECT
    n.source_code z_element_pedagogique_id,
    n.z_etape_id
  FROM
    act_noeud n
)
SELECT DISTINCT
  e.id                                   etape_id,
  ep.id                                  element_pedagogique_id,
  n.ordre                                ordre,
  s.id                                   source_id,
  cp.z_element_pedagogique_id || '_' || cp.z_etape_id source_code
FROM
  cp
  JOIN act_noeud n ON n.source_code = cp.z_element_pedagogique_id
  LEFT JOIN act_lien l ON l.z_noeud_sup_id = n.source_code
  LEFT JOIN element_pedagogique ep ON ep.source_code = cp.z_element_pedagogique_id
  LEFT JOIN etape e ON e.source_code = cp.z_etape_id
  JOIN source s ON s.code = 'Actul'
WHERE
  l.z_noeud_inf_id IS NULL
