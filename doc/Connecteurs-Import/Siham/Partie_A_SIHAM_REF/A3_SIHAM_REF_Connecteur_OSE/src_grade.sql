  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_GRADE" ("LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE", "ECHELLE", "CORPS_ID") AS 
  SELECT gr.libelle_long libelle_long,
          gr.libelle_court libelle_court,
          gr.source_id,
          gr.source_code,
          gr.echelle,
          oc.id corps_id
     FROM um_grade gr
        , um_corps uc
        , corps oc
    WHERE     uc.id = gr.corps_id
          AND oc.source_code = uc.source_code
          AND uc.source_code not in ( 'NC','STSP','STSV')
   UNION
   SELECT gr.libelle_long libelle_long,
          gr.libelle_court libelle_court,
          gr.source_id,
          gr.source_code,
          gr.echelle,
          oc.id corps_id
     FROM um_grade gr
        , um_corps uc
        , corps oc
    WHERE     uc.source_code in ('NC','STSP')
          AND oc.source_code = uc.source_code
          AND gr.corps_id = uc.id
   UNION ALL
   SELECT decode(cat.lib_ll_cat, null,rtrim(gr.libelle_long), rtrim(cat.pip)||' - '||cat.lib_ll_cat) libelle_long,
          decode(cat.lib_ll_cat, null,rtrim(gr.libelle_court), rtrim(cat.pip)||' - '||cat.source_code) libelle_court,
          gr.source_id,
          decode(cat.source_code,null,gr.source_code,gr.source_code||'_'||cat.source_code),
          gr.echelle,
          oc.id corps_id
     FROM um_grade gr
        , um_corps uc
        , corps oc
        , (select distinct  i.grade_id
                          , o.libelle_long lib_ll_cat
                          , o.libelle_court lib_cc_cat
                          , w_statut_pip pip 
                          , o.source_code
           from um_intervenant i
              , um_orec_categorie o 
          where i.orec_lib_categorie is not null
            and o.source_code = i.orec_lib_categorie
          ) cat
    WHERE     uc.source_code in ( 'STSV')
          AND oc.source_code = uc.source_code
          AND gr.corps_id = uc.id
          and cat.grade_id(+) = gr.id;