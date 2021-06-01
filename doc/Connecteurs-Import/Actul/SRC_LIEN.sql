CREATE OR REPLACE FORCE VIEW src_lien AS
-- liens entre les étapes et les listes d'étapes OK
SELECT
  nsup.id                                                     noeud_sup_id,
  ninf.id                                                     noeud_inf_id,
  nsup.structure_id                                           structure_id,
  s.id                                                        source_id,
  'net_' || ae.source_code || '_let_' || ae.source_code       source_code,
  ninf.libelle                                                ordre
FROM
        act_etape   ae
  LEFT JOIN noeud nsup ON nsup.source_code = 'net_' || ae.source_code
  LEFT JOIN noeud ninf ON ninf.source_code = 'let_' || ae.source_code
  JOIN source        s ON s.code = ae.z_source_id

UNION ALL

-- liens des listes d'étapes vers les noeuds racines OK
SELECT
  nsup.id                                                     noeud_sup_id,
  ninf.id                                                     noeud_inf_id,
  nsup.structure_id                                           structure_id,
  s.id                                                        source_id,
  'let_' || an.z_etape_id || '_nep_' || an.source_code        source_code,
  ninf.libelle                                                ordre
FROM
  act_noeud an
  LEFT JOIN act_lien al ON al.z_noeud_inf_id = an.source_code
  LEFT JOIN noeud nsup ON nsup.source_code = 'let_' || an.z_etape_id
  LEFT JOIN noeud ninf ON ninf.source_code = 'nep_' || an.source_code
  JOIN source        s ON s.code = an.z_source_id
WHERE
  al.z_noeud_sup_id IS NULL

UNION ALL

-- liens des noeuds non feuilles vers les listes de noeuds
SELECT
  nsup.id                                                     noeud_sup_id,
  ninf.id                                                     noeud_inf_id,
  nsup.structure_id                                           structure_id,
  s.id                                                        source_id,
  'nep_' || an.source_code || '_lep_' || an.source_code       source_code,
  an.libelle                                                  ordre
FROM
  act_noeud an
  JOIN (SELECT DISTINCT z_noeud_sup_id FROM act_lien) al ON al.z_noeud_sup_id = an.source_code
  LEFT JOIN noeud nsup ON nsup.source_code = 'nep_' || an.source_code
  LEFT JOIN noeud ninf ON ninf.source_code = 'lep_' || an.source_code
  JOIN source        s ON s.code = an.z_source_id

UNION ALL

-- liens entre les listes de noeuds et les noeuds sous-jacents
SELECT
  nsup.id                                                     noeud_sup_id,
  ninf.id                                                     noeud_inf_id,
  nsup.structure_id                                           structure_id,
  s.id                                                        source_id,
  'lep_' || al.z_noeud_sup_id || '_nep_' || al.z_noeud_inf_id source_code,
  ninf.libelle                                                ordre
FROM
        act_lien    al
  LEFT JOIN noeud nsup ON nsup.source_code = 'lep_' || al.z_noeud_sup_id
  LEFT JOIN noeud ninf ON ninf.source_code = 'nep_' || al.z_noeud_inf_id
  JOIN source        s ON s.code = al.z_source_id

ORDER BY
  ordre