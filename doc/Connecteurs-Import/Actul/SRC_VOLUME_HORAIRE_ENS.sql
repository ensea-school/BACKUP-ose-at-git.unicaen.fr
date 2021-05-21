CREATE OR REPLACE FORCE VIEW "OSE"."SRC_VOLUME_HORAIRE_ENS" ("ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID", "HEURES", "GROUPES", "SOURCE_ID", "SOURCE_CODE") AS
WITH actul_query_fca_query AS (
  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    CASE vhe.z_type_intervention_id
      WHEN 'MEMOIR' THEN 'Mémoire'
      WHEN 'STAGE'  THEN 'Stage'
      WHEN 'PROJET' THEN 'Projet'
      WHEN 'TERRAI' THEN 'TD'
      WHEN 'CMTD' THEN 'TD'
    ELSE
      vhe.z_type_intervention_id
    END                                     z_type_intervention_id,
    CASE vhe.z_type_intervention_id
      WHEN 'CMTD' THEN vhe.heures * 1.25
    ELSE
      vhe.heures
    END heures,
    vhe.groupes groupes,
    'Actul'                                z_source_id,
    case vhe.z_type_intervention_id
      WHEN 'TERRAI' THEN vhe.annee_id || '_' ||substr(vhe.source_code,1,instr(vhe.source_code,'_',1,1)-1)||'_'||'TD'
      WHEN 'CMTD'   THEN vhe.annee_id || '_' ||substr(vhe.source_code,1,instr(vhe.source_code,'_',1,1)-1)||'_'||'TD'
    else vhe.annee_id || '_' || vhe.source_code
    end                                     source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM
    act_volume_horaire_ens vhe
  where vhe.z_type_intervention_id not in ('HYCM','HYTD','HYTP')

)
SELECT
  ep.id           element_pedagogique_id,
  ti.id           type_intervention_id,
  sum(afq.heures )     heures,
  sum(afq.groupes )     groupes,
  s.id            source_id,
  afq.source_code source_code
FROM
            actul_query_fca_query   afq
       JOIN source               s ON s.code         = afq.z_source_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = afq.z_element_pedagogique_id
                                  AND ep.annee_id    = afq.annee_id
  LEFT JOIN type_intervention   ti ON ti.code        = afq.z_type_intervention_id
where ep.id is not null
group by
  ep.id ,
  ti.id ,
  s.id,
  afq.source_code