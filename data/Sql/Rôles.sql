-- liste des comptes utilisateurs
select
  tr.code role,
  s.libelle_court structure,
  p.nom_usuel nom,
  p.prenom prenom,
  src.libelle source,
  r.id,
  r.source_code
from
  affectation r
  JOIN personnel p ON p.id = r.personnel_id AND p.histo_destruction IS NULL
  JOIN role tr ON tr.id = r.role_id AND tr.histo_destruction IS NULL
  JOIN source src ON src.id = r.source_id
  LEFT JOIN structure s ON s.id = r.structure_id AND s.histo_destruction IS NULL
WHERE
  r.histo_destruction IS NULL
  --AND r.source_id = OSE_IMPORT.GET_SOURCE_ID('OSE')
ORDER BY
  structure, nom, source, role;

-- pour créer un nouveau rôle

select nom_usuel, prenom, source_code, histo_destruction from personnel where 1 = ose_divers.str_find(nom_patronymique || ' ' || nom_usuel,'%DOLLEY%' );
/*
- Chantal DENOYES
- Nathalie OZENNE
- Eric VIEVILLE
- Sandra MAHIEU
 */

select id, libelle_court, libelle_long, source_code from structure where libelle_court like '%Psy%' AND niveau = 2;

select * from type_role where histo_destruction is null;

INSERT INTO affectation (
    STRUCTURE_ID,
    PERSONNEL_ID,
    TYPE_ID,
    SOURCE_CODE,
    ID, SOURCE_ID, 
    HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID
)VALUES(
    null,--(SELECT ID FROM structure WHERE source_code = 'U01'),
    (SELECT ID FROM personnel WHERE source_code ='102416'),
    (SELECT ID FROM ROLE WHERE code = 'administrateur'),
    'administrateur-102416',
    affectation_ID_SEQ.NEXTVAL, OSE_IMPORT.GET_SOURCE_ID('OSE'), 
    (select id from utilisateur where username='lecluse'), (select id from utilisateur where username='lecluse') -- laurent
);

delete from role where source_code = 'gestionnaire-composante-3157';

-- divers




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

select * from structure@harpprod where lc_structure like 'UFR%Scien%';

select * from individu_fonct_struct@harpprod ifs where no_dossier_pers = 16956;


select * from individu@harpprod where nom_usuel = 'DENOYES';



select
r.code ressource,
p.code privilege,
p.id   p_id
from
  privilege p
  join ressource r on r.id = p.ressource_id
order by
  ressource, privilege