DROP MATERIALIZED VIEW "OSE"."MV_ROLE";
CREATE MATERIALIZED VIEW "OSE"."MV_ROLE" ("Z_STRUCTURE_ID", "Z_PERSONNEL_ID", "Z_TYPE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS
SELECT DISTINCT
  z_structure_id,
  z_personnel_id,
  z_type_id,
  source_id,
  MIN( source_code) source_code,
  MIN( validite_debut ) validite_debut,
  MAX(validite_fin ) validite_fin
FROM ( SELECT
    CASE WHEN ifs.c_structure = 'UNIV' THEN NULL ELSE ifs.c_structure END z_structure_id,
    ifs.no_dossier_pers z_personnel_id,
    CASE 
      when fs.lc_fonction IN ('_D30a', '_D30b', '_D30c', '_D30d', '_D30e' ) then 'directeur-composante'
      when fs.lc_fonction IN ('_R00', '_R40', '_R40b')                      then 'responsable-composante'
      when fs.lc_fonction IN ('_R00c')                                      then 'responsable-recherche-labo'
      when ifs.c_structure = 'UNIV' AND fs.lc_fonction = '_P00' OR fs.lc_fonction LIKE '_P10%' OR fs.lc_fonction like '_P50%' then 'superviseur-etablissement'
      else NULL
    END z_type_id,
    ose_import.get_source_id('Harpege') as source_id,
    to_char(ifs.no_exercice_respons) source_code,
    ifs.DT_DEB_EXERC_RESP as validite_debut,
    ifs.DT_FIN_EXERC_RESP as validite_fin
  FROM
    individu_fonct_struct@harpprod ifs
    JOIN fonction_structurelle@harpprod fs ON fs.c_fonction = ifs.c_fonction
  WHERE
    OSE_IMPORT.GET_DATE_OBS BETWEEN ifs.DT_DEB_EXERC_RESP AND NVL(ifs.DT_FIN_EXERC_RESP,OSE_IMPORT.GET_DATE_OBS)
  ) tmp
WHERE
  tmp.z_type_id IS NOT NULL
GROUP BY
  z_structure_id, z_personnel_id, z_type_id,source_id;
  
  
  
  
  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ROLE" ("ID", "STRUCTURE_ID", "PERSONNEL_ID", "TYPE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  NULL id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  p.id personnel_id,
  tr.id type_id,
  r.source_id,
  r.source_code,
  r.validite_debut,
  r.validite_fin
FROM
  mv_role r
  LEFT JOIN personnel p ON p.source_code = r.z_personnel_id
  LEFT JOIN structure s ON s.source_code = r.z_structure_id
  LEFT JOIN type_role tr ON tr.code = r.z_type_id
WHERE
  s.id IS NULL -- rôle global
  OR (
    (
      EXISTS (SELECT * FROM element_pedagogique ep WHERE EP.STRUCTURE_ID = NVL(s.STRUCTURE_NIV2_ID,s.id)) -- soit une resp. dans une composante d'enseignement
      OR r.z_type_id IN ('responsable-recherche-labo')                                                    -- soit un responsable de labo
    )
    AND s.niveau <= 2
  );
