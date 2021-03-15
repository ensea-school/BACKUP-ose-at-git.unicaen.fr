CREATE
MATERIALIZED VIEW "OSE"."MV_AFFECTATION" AS
WITH ind_fonction AS(
	SELECT
		ind.nom_usage || ' ' || INITCAP(ind.prenom)            display_name,
		indcom.email                                           email,
		'ldap'											       password,
		1												       state,
		indcom.login     									   username,
		CASE WHEN s.code = 'UNIV' THEN NULL ELSE s.code END    z_structure_id,
		CASE
	      WHEN fon.code LIKE 'D30%' OR fon.code LIKE 'P71%' OR fon.code LIKE 'J60%' THEN 'directeur-composante'
	      WHEN fon.code LIKE 'R00'  OR fon.code LIKE 'R40%' THEN 'responsable-composante'
	      WHEN fon.code LIKE 'R00C' OR fon.code LIKE 'R40%' THEN 'responsable-recherche-labo'
	      WHEN s.code = 'UNIV' AND fon.code = 'P00' OR fon.code LIKE 'P10%' OR fon.code LIKE 'P50%' THEN 'superviseur-etablissement'
	      ELSE NULL
	    END 													z_role_id,
        s.code || '_' || ind.c_individu_chaine || '_' || fon.code   source_code
	FROM octo.individu_fonction@octoprod indfon
	JOIN octo.fonction@octoprod fon ON fon.id = indfon.fonction_id
	JOIN octo.fonction_libelle@octoprod fonlib ON fonlib.fonction_id = fon.id
	JOIN octo.v_structure@octoprod s ON s.id = indfon.structure_id
	JOIN octo.individu@octoprod ind ON ind.c_individu_chaine = indfon.individu_id
	JOIN octo.individu_compte@octoprod indcom ON indcom.individu_id = ind.c_individu_chaine
    AND SYSDATE BETWEEN indfon.date_debut AND COALESCE(indfon.date_fin + 1, SYSDATE)
    AND s.niveau <= 2
    AND s.date_fermeture IS NULL
)
SELECT DISTINCT display_name,
                email,
                password,
                state,
                username,
                z_structure_id,
                z_role_id,
                'Octopus'        z_source_id,
                MIN(source_code) source_code
FROM ind_fonction indfon
WHERE z_role_id IS NOT NULL
GROUP BY display_name,
         email,
         password,
         state,
         username,
         z_structure_id,
         z_role_id;




