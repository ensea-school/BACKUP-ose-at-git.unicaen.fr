CREATE OR REPLACE FORCE VIEW SRC_LIEN AS
  SELECT
  nsup.id         noeud_sup_id,
  ninf.id         noeud_inf_id,
  str.id          structure_id,
  s.id            source_id,
  l.z_source_code source_code
FROM
            act_lien            l
       JOIN source                      s ON s.code = 'Actul'
       JOIN noeud                    nsup ON nsup.source_code = l.noeud_sup_id
                                         AND nsup.annee_id = TO_NUMBER(l.annee_id)
       JOIN noeud                    ninf ON ninf.source_code = l.noeud_inf_id
                                         AND ninf.annee_id = TO_NUMBER(l.annee_id)
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = l.z_structure_id
  LEFT JOIN structure                 str ON str.source_code = sc.c_structure_n2