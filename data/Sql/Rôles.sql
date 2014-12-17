-- liste des comptes utilisateurs
select
  tr.code type_role,
  s.libelle_court structure,
  p.nom_usuel nom,
  p.prenom prenom,
  src.libelle source,
  r.id,
  r.source_code
from
  role r
  JOIN personnel p ON p.id = r.personnel_id AND p.histo_destruction IS NULL
  JOIN type_role tr ON tr.id = r.type_id AND tr.histo_destruction IS NULL
  JOIN source src ON src.id = r.source_id
  LEFT JOIN structure s ON s.id = r.structure_id AND s.histo_destruction IS NULL
WHERE
  r.histo_destruction IS NULL
  AND r.source_id <> OSE_IMPORT.GET_SOURCE_ID('OSE')
ORDER BY
  structure, nom, source, type_role;



INSERT INTO ROLE (
    STRUCTURE_ID,
    PERSONNEL_ID,
    TYPE_ID,
    SOURCE_CODE,
    ID, SOURCE_ID, HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID
)VALUES(
    (SELECT ID FROM structure WHERE source_code = 'U26'),
    (SELECT ID FROM personnel WHERE source_code ='788'),
    (SELECT ID FROM TYPE_ROLE WHERE code = 'gestionnaire-composante'),
    'gest-788',
    ROLE_ID_SEQ.NEXTVAL, OSE_IMPORT.GET_SOURCE_ID('OSE'), 1, 1
);

INSERT INTO ROLE (
    STRUCTURE_ID,
    PERSONNEL_ID,
    TYPE_ID,
    SOURCE_CODE,
    ID, SOURCE_ID, HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID
)VALUES(
    (SELECT ID FROM structure WHERE source_code = 'U26'),
    (SELECT ID FROM personnel WHERE source_code ='4650'),
    (SELECT ID FROM TYPE_ROLE WHERE code = 'administrateur'),
    'gestionnaire-drh-alcalde',
    ROLE_ID_SEQ.NEXTVAL, OSE_IMPORT.GET_SOURCE_ID('OSE'), 1, 1
);

select * from personnel where nom_usuel like 'Cador%';
select * from type_role;

delete from role where id = 492;

SELECT 
  z_structure_id,
  z_personnel_id,
  z_type_id,
  source_id,
  MIN( source_code) source_code,
  MIN( validite_debut ) validite_debut,
  MAX(validite_fin ) validite_fin
FROM ( SELECT
    ifs.c_structure z_structure_id,
    ifs.no_dossier_pers z_personnel_id,
    CASE 
      when fs.lc_fonction IN ('_D30a','_D30b','_D30c','_D30d','_D30e') then 
        CASE 
          WHEN s.lc_structure = 'DRH' THEN 'responsable-drh'
          ELSE 'directeur-composante'
        END
      when fs.lc_fonction IN ('_R00','_R40','_R40b') then 'responsable-composante' -- assistant de direction
      when fs.lc_fonction = '_R00c' then 'responsable-recherche-labo'
      ELSE NULL
    END z_type_id,
    ose_import.get_source_id('Harpege') as source_id,
    to_char(ifs.no_exercice_respons) source_code,
    ifs.DT_DEB_EXERC_RESP as validite_debut,
    ifs.DT_FIN_EXERC_RESP as validite_fin
  FROM
    individu_fonct_struct@harpprod ifs
    JOIN fonction_structurelle@harpprod fs ON fs.c_fonction = ifs.c_fonction
    JOIN structure@harpprod s ON s.c_structure = ifs.c_structure
  WHERE
    OSE_IMPORT.GET_DATE_OBS BETWEEN ifs.DT_DEB_EXERC_RESP AND NVL(ifs.DT_FIN_EXERC_RESP,OSE_IMPORT.GET_DATE_OBS)
  ) tmp
WHERE
  tmp.z_type_id IS NOT NULL
GROUP BY
  z_structure_id, z_personnel_id, z_type_id,source_id;


select * from fonction_structurelle@harpprod fs;

select * from structure@harpprod where lc_structure like 'UFR%Géo%';

select * from individu_fonct_struct@harpprod ifs where no_dossier_pers = 16956;



