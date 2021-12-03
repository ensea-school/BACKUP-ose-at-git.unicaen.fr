CREATE
OR REPLACE FORCE VIEW SRC_AFFECTATION_RECHERCHE AS
WITH affectation_recherche AS (
 	SELECT
 		indaff.individu_id           z_code,
 		s.id     					 z_structure_recherche_id,
 		indaff.id                    source_code
 	from
 		octo.individu_affectation@octoprod indaff
 	    JOIN octo.v_structure@octoprod s ON s.id = indaff.structure_id
 	WHERE
 		indaff.type_id = 3--Uniquement les affectations de type recherche
 		AND SYSDATE BETWEEN indaff.date_debut AND COALESCE(indaff.date_fin + 184, SYSDATE)
 		AND indaff.source_id = 'SIHAM'
 		)
SELECT DISTINCT i.id                intervenant_id,
                s.id                structure_id,
                src.id              source_id,
                i.id || '_' || s.id source_code,
                s.libelle_court    labo_libelle
FROM affectation_recherche affrech
         JOIN source src ON src.code = 'Octopus'
         JOIN intervenant i ON i.code = CAST(affrech.z_code AS varchar(255))  AND i.annee_id = unicaen_import.get_current_annee
         JOIN octo.v_structure@octoprod s ON affrech.z_structure_recherche_id = s.id