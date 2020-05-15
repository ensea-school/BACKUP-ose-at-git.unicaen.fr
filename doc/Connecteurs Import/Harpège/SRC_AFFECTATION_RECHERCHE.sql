CREATE OR REPLACE FORCE VIEW SRC_AFFECTATION_RECHERCHE AS
  WITH harpege_query AS (
      SELECT
             to_char(ar.no_dossier_pers)  z_intervenant_id,
             ar.c_structure               z_structure_id,
             'Harpege'                    z_source_id,
             to_char(ar.no_seq_affe_rech) source_code,
             s.lc_structure               labo_libelle
      FROM
           affectation_recherche@harpprod ar
             JOIN structure@harpprod s ON s.c_structure = ar.c_structure
      WHERE
          SYSDATE BETWEEN ar.d_deb_affe_rech AND COALESCE(ar.d_fin_affe_rech + 1,SYSDATE)
  )
  SELECT
         i.id                                                      intervenant_id,
         s.id                                                      structure_id,
         src.id                                                    source_id,
         hq.source_code || '_' || unicaen_import.get_current_annee source_code,
         hq.labo_libelle                                           labo_libelle
  FROM
       harpege_query              hq
         JOIN source                    src ON src.code = 'Harpege'
         LEFT JOIN intervenant                 i ON i.source_code = hq.z_intervenant_id
                                                      AND i.annee_id = unicaen_import.get_current_annee
         LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = hq.z_structure_id
         LEFT JOIN structure                   s ON s.source_code = sc.c_structure_n2;