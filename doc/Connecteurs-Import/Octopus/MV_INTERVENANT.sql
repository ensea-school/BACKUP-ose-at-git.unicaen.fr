CREATE
MATERIALIZED VIEW MV_INTERVENANT AS

WITH i AS (

     SELECT DISTINCT code,
                    MAX(z_statut_id) OVER (PARTITION BY code, z_statut_id)               z_statut_id,
				    MAX(z_type) OVER (PARTITION BY code, z_statut_id)                    z_type,
                    MIN(source_code)    OVER (PARTITION BY code, z_statut_id)            source_code,
                    MIN(validite_debut) OVER (PARTITION BY code, z_statut_id)            validite_debut,
                    MAX(validite_fin)   OVER (PARTITION BY code, z_statut_id)            validite_fin,
                    MAX(fin_affectation_siham) OVER (PARTITION BY code, z_statut_id)     fin_affectation_siham

    FROM (
	    	 --Step 1 : On prend tous les individus qui ont ou ont eu un contrat à l'université
             SELECT icto.individu_id                                            code,
                    CASE
                        WHEN icto.code_ose = 'ENS_2ND_DEGRE' THEN 'ENS_2ND_DEG'
                        WHEN icto.code_ose IS NOT NULL THEN icto.code_ose
                        ELSE 'AUTRES' END                                       z_statut_id,
                    CASE
                    	WHEN icto.code_ose IS NOT NULL AND icto.code_ose NOT IN ('NON_AUTORISE') THEN 'permanent'
                    	ELSE 'vacataire' END                                        z_type,
                    icto.id_orig                                                source_code,
                    COALESCE(icto.d_debut, to_date('01/01/1900', 'dd/mm/YYYY')) validite_debut,
                     CASE
	                    --Si date de fin est réglée au 1er sept, on la repasse au 31/08 de la même année (Bug Octopus)
                    	WHEN to_char(icto.d_fin, 'dd/mm') = '01/09' THEN icto.d_fin-1
                    	ELSE COALESCE(icto.d_fin, to_date('01/01/9999', 'dd/mm/YYYY')) END   validite_fin,
                    NULL	   													fin_affectation_siham
             FROM octo.v_individu_contrat_type_ose@octoprod icto
                      JOIN octo.individu_unique@octoprod uni ON icto.individu_id = uni.c_individu_chaine
                      JOIN octo.v_individu_statut@octoprod vinds ON vinds.individu_id = uni.c_individu_chaine

             WHERE (COALESCE(icto.d_debut, to_date('01/01/1900', 'dd/mm/YYYY')) - 184 <= SYSDATE OR COALESCE(icto.d_fin, to_date('01/01/9999', 'dd/mm/YYYY')) >= SYSDATE)  AND icto.code_ose IS NOT NULL


             UNION ALL
             -- Step 2 : on prend tout le reste potentiel vacataire, notamment les hébergés
             SELECT uni.c_individu_chaine                                           code,
                    'AUTRES'                                                        z_statut_id,
					CASE
                    	WHEN (inds.t_titulaire='O' OR inds.t_cdi='O' OR inds.t_cdd='O') THEN 'permanent'
                    	ELSE 'vacataire' END                                        z_type,
                    uni.c_individu_chaine || '-autre'                               source_code,
                    COALESCE(inds.d_debut, to_date('01/01/1900', 'dd/mm/YYYY'))     validite_debut,
                    CASE
                    	WHEN inds.d_fin = to_date('01/09/2021', 'dd/mm/YYYY') THEN to_date('31/08/2021', 'dd/mm/YYYY')
                    	ELSE COALESCE(inds.d_fin, to_date('01/01/9999', 'dd/mm/YYYY')) END   validite_fin,
                    CASE
                        WHEN inds.c_source = 'SIHAM' THEN 	inds.d_fin
                        ELSE NULL END		                                        fin_affectation_siham

             FROM octo.individu_unique@octoprod uni
            JOIN octo.individu_statut@octoprod inds ON inds.individu_id = uni.c_individu_chaine
   					LEFT JOIN octo.v_individu_statut@octoprod vinds ON vinds.individu_id = uni.c_individu_chaine
					  LEFT JOIN octo.v_individu_contrat_type_ose@octoprod icto ON uni.c_individu_chaine = icto.individu_id AND icto.code_ose IS NULL
             WHERE inds.d_debut - 184 <= SYSDATE
               --On ne remonte pas de statut autre pour ceux qui ont déjà un certain type de contrat
	           --AND icto.individu_id IS NULL
               --Combinaison des témoins octopus pour récupérer les bonnes populations
               AND ((inds.t_enseignant = 'O' AND inds.t_vacataire = 'O')
                 OR (inds.t_enseignant = 'O' AND inds.t_heberge = 'O')
                 OR (inds.t_vacataire = 'O')
                 OR (inds.t_heberge = 'O'))
               --AND (vinds.t_doctorant='N' OR vinds.individu_id IS NULL)
               AND inds.c_source IN ('HARP', 'OCTO', 'SIHAM')

             UNION ALL

             SELECT uni.c_individu_chaine                                           code,
                    'ETUDIANT'                                                      z_statut_id,
					'etudiant'                                                      z_type,
                    uni.c_individu_chaine || '-etudiant'                            source_code,
                    COALESCE(inds.d_debut, to_date('01/01/1900', 'dd/mm/YYYY'))     validite_debut,
                    CASE
                    	WHEN inds.d_fin = to_date('01/09/2021', 'dd/mm/YYYY') THEN to_date('31/08/2021', 'dd/mm/YYYY')
                    	ELSE COALESCE(inds.d_fin, to_date('01/01/9999', 'dd/mm/YYYY')) END   validite_fin,
                    NULL 															fin_affectation_siham
             FROM octo.individu_unique@octoprod uni
                      JOIN octo.individu_statut@octoprod inds ON inds.individu_id = uni.c_individu_chaine
   					  LEFT JOIN octo.v_individu_statut@octoprod vinds ON vinds.individu_id = uni.c_individu_chaine
             WHERE inds.d_debut - 184 <= SYSDATE
               AND inds.t_etudiant = 'O'
              -- On remonte maitnenant les doctorants
              -- AND inds.t_doctorant ='N'
               AND inds.c_source IN ('APO', 'OCTO')
         ) t


),

     --Trouver le tel pro principal de l'intervenant
     telephone_pro_principal AS (
        SELECT
	        indtel.individu_id individu_id,
	        MAX(tel.numero) KEEP (DENSE_RANK FIRST ORDER BY tel.type_id ASC) numero
        FROM octo.individu_telephone@octoprod indtel
        JOIN octo.telephone@octoprod tel ON (tel.id = indtel.telephone_id AND tel.t_principal = 'O')
        GROUP BY indtel.individu_id
     ),
     --Trouver la structure d'affectation principale de l'intervenant
     structure_principale_individu AS (
         SELECT DISTINCT uni.c_individu_chaine,
                         FIRST_VALUE(aff.structure_id) OVER (PARTITION BY uni.c_individu_chaine ORDER BY aff.date_fin DESC)  z_structure_id
         FROM octo.individu_affectation@octoprod aff
                  JOIN octo.individu_unique@octoprod uni ON uni.c_individu_chaine = aff.individu_id
         WHERE aff.t_principale = 'O'
           AND aff.type_id = 4--Uniquement les affectations d'enseignement
           AND aff.date_fin + 1 >= (SYSDATE - (365 * 2))
     ),
     --Autre façon de trouver les structures d'affectation d'enseignement ou d'étude
     structure_aff_enseigne AS
         (
             -- On prend en priorite l'affectation siham, sinon l'affectation principale dans Octopus
             -- Dans le cadre d'un étudiant on prend l'affectation APOGEE
             -- Si plusieurs, alors c'est celle qui commence le + tard qui est prise en comtpe
            SELECT
                individu_id,
                MAX(structure_id) KEEP (DENSE_RANK  LAST ORDER BY CASE WHEN source_id = 'SIHAM' THEN 1  WHEN source_id = 'OCTOREFID' THEN 2  WHEN source_id = 'APO' THEN 3 ELSE 999 END DESC , t_principale, date_debut)   structure_id,
                MAX(source_id) KEEP (DENSE_RANK  LAST ORDER BY CASE WHEN source_id = 'SIHAM' THEN 1 WHEN source_id = 'OCTOREFID' THEN 2 WHEN source_id = 'APO' THEN 3 ELSE 999 END DESC,t_principale, date_debut) source_id
             FROM octo.individu_affectation@octoprod
             --WHERE type_id = 5 //Filtre pour les affectations étudiants
             WHERE type_id = 4
             AND COALESCE(date_fin, SYSDATE) + 1 >= (SYSDATE - (365 * 2))
             GROUP BY individu_id
         ),
     --CNU arrangé
     cnua AS (
         SELECT gra.individu_id                   individu_id,
                MAX(nvl(carr.c_cnu, darr.c_cnu))       code_cnu_arrange,
                MAX(nvl(carr.lib_long, darr.lib_long)) libelle_cnu_arrange,
                MAX(nvl(carr.groupe, darr.groupe))     groupe_cnu_arrange
         FROM octo.individu_grade@octoprod gra
                  LEFT JOIN octo.cnu_spec_cnu_adapte@octoprod ccarr ON (gra.cnu_id = ccarr.cnu_id AND
                                                                        (gra.cnu_specialite_id IS NULL OR
                                                                         gra.cnu_specialite_id = ccarr.cnu_specialite_id))
                  LEFT JOIN octo.cnu_adapte@octoprod carr ON (ccarr.cnu_adapte_id = carr.id)
                  LEFT JOIN octo.discipline_cnu_adapte@octoprod dcarr ON (gra.discipline_sec_id = dcarr.discipline_sec_id)
                  LEFT JOIN octo.cnu_adapte@octoprod darr ON (dcarr.cnu_adapte_id = darr.id)
         WHERE sysdate BETWEEN gra.d_debut AND nvl(gra.d_fin, sysdate)
           AND (gra.cnu_id IS NOT NULL OR gra.discipline_sec_id IS NOT NULL)
           GROUP BY gra.individu_id
     ),
    /*Individu unique pour avoir qu'un seul individu unique dans l'ordre c_source SIHAM, HARP, APO
    Car individu unique peut avoir plusieurs entrée (Harpége et Apogé par exemple) dans la table individu_unique,
    ce qui fait des doublons en sortie dans la vue. On restreint donc à un seul individu unique avec en priorité celui de SIHAM
    pui HARPEGE puis APOGEE en dernier lieu*/
     induni AS
         (
             SELECT DISTINCT FIRST_VALUE(c_individu_chaine) OVER (PARTITION BY c_individu_chaine ORDER BY ordre_source) c_individu_chaine, FIRST_VALUE(c_source) OVER (PARTITION BY c_individu_chaine ORDER BY ordre_source) c_source, FIRST_VALUE(c_src_individu) OVER (PARTITION BY c_individu_chaine ORDER BY ordre_source) c_src_individu
             FROM (
                      SELECT DISTINCT u.c_individu_chaine,
                                      u.c_source,
                                      u.c_src_individu,
                                      CASE
                                          WHEN u.c_source = 'SIHAM' THEN 1
                                          WHEN u.c_source = 'HARP' THEN 2
                                          WHEN u.c_source = 'OCTO' THEN 3
                                          WHEN u.c_source = 'APO' THEN 4
                                          END ordre_source
                      FROM octo.individu_unique@octoprod u
                  )
         ),
         ind_grade AS
         (
         	SELECT
         		indg.individu_id    individu_id,
         		indg.grade_id       grade_id
         	FROM octo.individu_grade@octoprod indg
         	WHERE COALESCE(indg.d_fin, to_date('01/01/9999', 'dd/mm/YYYY')) > SYSDATE
    	    AND COALESCE(indg.d_debut, to_date('01/01/1900', 'dd/mm/YYYY')) < SYSDATE
    	    --On retire temporairement les doubles grades des quelques individus (Historique harpege), à supprimer quand full siham
			AND indg.id NOT IN (8856,8904,9214,11735,12155,13166,14698,14731,14854,15143,15144,15201,15358,15359,15401)
         ),
         iban_dossier AS
         (
            SELECT
				i.code,
				MAX(REPLACE(d.iban, ' ', '')) iban,
				MAX(REPLACE(d.bic, ' ', ''))   bic
			FROM intervenant i
			JOIN intervenant_dossier d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
			WHERE  i.annee_id = 2020 AND i.histo_destruction IS NULL AND d.iban IS NOT NULL AND d.rib_hors_sepa = 0 AND i.source_id = '24' AND i.code_rh IS NOT NULL
			GROUP BY i.code
         ),
         compte AS
         (
	         SELECT
				MAX(indc.individu_id) individu_id,
				MAX(indc.ldap_uid) KEEP (DENSE_RANK  FIRST ORDER BY CASE WHEN regexp_like(ldap_uid, 'e[0-9]{8}') THEN 1 ELSE 0 END, indc.histo_creation)  ldap_uid,
				MAX(indc.email) KEEP (DENSE_RANK  FIRST ORDER BY CASE WHEN regexp_like(ldap_uid, 'e[0-9]{8}') THEN 1 ELSE 0 END,indc.histo_creation)  email,
				MAX(indc.histo_creation) KEEP (DENSE_RANK  FIRST ORDER BY CASE WHEN regexp_like(ldap_uid, 'e[0-9]{8}') THEN 1 ELSE 0 END,indc.histo_creation)  histo_creation,
				MAX(vindcvc.date_desactivation) KEEP (DENSE_RANK FIRST ORDER BY vindcvc.date_desactivation DESC)   date_fin
			 FROM  octo.individu_compte@octoprod indc
			 JOIN  octo.v_individu_cycle_vie_compte@octoprod vindcvc ON vindcvc.individu_id = indc.individu_id
			 WHERE indc.statut_id = 1
			 GROUP BY indc.individu_id
			 ORDER BY histo_creation ASC
         )

SELECT DISTINCT
    /*Octopus id, id unique pour un individu immuable dans le temps, remplace le code harpege*/
    ltrim(to_char(i.code, '99999999'))                                                 code,
    'Octopus'                                                                          z_source_id,
    /*Code RH si l'utilisateur est dans SIHAM*/
    ind.c_rh                                                                           code_rh,
    compte.ldap_uid                                                                    utilisateur_code,
    str2.code                                                                          z_structure_id,
    i.z_statut_id                                                                      z_statut_id,
    grade.c_grade                                                                      z_grade_id,
    CASE WHEN COALESCE(cnua.code_cnu_arrange, '00') = '12'
    	THEN '1201'
    	ELSE COALESCE(cnua.code_cnu_arrange, '00') END                                 z_discipline_id,    /* Données identifiantes de base */
    CASE COALESCE(ind.sexe_ow, ind.sexe)
        WHEN 'M' THEN 'M.'
        ELSE 'Mme'
        END                                                                            z_civilite_id,
    CAST(NULL AS varchar2(255))                                                        z_situation_matrimoniale_id,
    COALESCE(initcap(vind.nom_usage), initcap(ind.nom_famille))                        nom_usuel,
    COALESCE(initcap(vind.prenom), initcap(ind.prenom))                                prenom,
    COALESCE(ind.d_naissance_ow, ind.d_naissance, to_date('01/01/1900', 'dd/mm/YYYY')) date_naissance,
    /* Données identifiantes complémentaires */
    initcap(ind.nom_famille)                                                           nom_patronymique,
    COALESCE(ind.ville_de_naissance_ow, ind.ville_de_naissance)                        commune_naissance,
    COALESCE(ind.c_pays_naissance_ow, ind.c_pays_naissance)                            z_pays_naissance_id,
    COALESCE(ind.c_dept_naissance_ow, ind.c_dept_naissance)                            z_departement_naissance_id,
    COALESCE(ind.c_pays_nationalite_ow, ind.c_pays_nationalite)                        z_pays_nationalite_id,
    CAST(telpro.numero AS varchar2(255))                                               tel_pro,
    COALESCE(ind.tel_perso_ow, ind.tel_perso)                                          tel_perso,
    CAST(compte.email AS varchar2(255))                                                email_pro,
    CAST(NULL AS varchar2(255))                                                        email_perso,
    --COALESCE(ind.email_perso_ow, ind.email_perso)                                      email_perso,    /* Adresse */
    TRIM(adr.adresse1 ||
         CASE
             WHEN adr.adresse1 IS NOT NULL
                 AND adr.adresse2 IS NOT NULL
                 THEN chr(13)
             ELSE '' END || adr.adresse2 ||
         CASE
             WHEN adr.adresse2 IS NOT NULL
                 AND adr.adresse3 IS NOT NULL
                 THEN chr(13)
             ELSE '' END || adr.adresse3)                                              adresse_precisions,
    CAST(NULL AS varchar2(255))                                                        adresse_numero,
    CAST(NULL AS varchar2(255))                                                        z_adresse_numero_compl_id,
    CAST(NULL AS varchar2(255))                                                        z_adresse_voirie_id,
    CAST(NULL AS varchar2(255))                                                        adresse_voie,
    CAST(NULL AS varchar2(255))                                                        adresse_lieu_dit,
    adr.code_postal                                                                    adresse_code_postal,
    adr.ville_nom                                                                      adresse_commune,
    pays.code_pays                                                                     z_adresse_pays_id,
    /* INSEE */
    COALESCE(TRIM(vindinsee.no_insee), TRIM(vindinsee.no_insee_provisoire))            numero_insee,
    CASE
        WHEN vindinsee.no_insee IS NULL
            AND vindinsee.no_insee_provisoire IS NOT NULL
            THEN 1
        ELSE 0 END                                                                     numero_insee_provisoire,
    /* Banque */
    COALESCE(TRIM(vindiban.iban), ibandossier.iban)                                    iban,
    COALESCE(TRIM(vindiban.bic), ibandossier.bic)                                      bic,
    CAST(NULL AS varchar2(255))                                                        numero_pec,
    0                                                                                  rib_hors_sepa,
    /* Données complémentaires */
    CAST(NULL AS varchar2(255))                                                        autre_1,
    CAST(NULL AS varchar2(255))                                                        autre_2,
    CAST(NULL AS varchar2(255))                                                        autre_3,
    CAST(NULL AS varchar2(255))                                                        autre_4,
    CAST(NULL AS varchar2(255))                                                        autre_5,
    /* Employeur */
    CAST(NULL AS varchar2(255))                                                        z_employeur_id,
    CAST(CASE
        WHEN i.validite_debut = to_date('01/01/1900', 'dd/mm/YYYY')
            THEN NULL
        ELSE i.validite_debut
        END AS DATE)                                                                           validite_debut,
    CAST(CASE
        WHEN i.validite_fin = to_date('01/01/9999', 'dd/mm/YYYY')
            THEN NULL
        WHEN (i.z_type = 'vacataire' AND i.validite_fin < compte.date_fin AND i.validite_fin IS NOT NULL)
            THEN compte.date_fin
        WHEN (i.z_type = 'etudiant' AND i.validite_fin < compte.date_fin AND i.validite_fin IS NOT NULL)
            THEN compte.date_fin
        ELSE i.validite_fin
        END AS DATE)                                                                         validite_fin,
    CAST(i.fin_affectation_siham AS DATE)                                                           affectation_fin

FROM i
         JOIN induni
              ON i.code = induni.c_individu_chaine --AND induni.c_source IN ('HARP', 'OCTO', 'SIHAM'))
         LEFT JOIN octo.individu@octoprod ind ON ind.c_individu_chaine = induni.c_individu_chaine
         LEFT JOIN octo.v_via_individu_insee@octoprod vindinsee ON ind.c_individu_chaine = vindinsee.individu_id
         LEFT JOIN octo.v_via_individu_iban@octoprod vindiban ON vindiban.individu_id = ind.c_individu_chaine
         LEFT JOIN iban_dossier ibandossier ON ibandossier.code = ind.c_individu_chaine
         LEFT JOIN octo.v_individu@octoprod vind ON vind.c_individu_chaine = induni.c_individu_chaine
    --On récupére la structure principale de l'individu
         LEFT JOIN structure_aff_enseigne sae ON sae.individu_id = induni.c_individu_chaine
    --On récupére le code de la structure d'affectation principal de l'individu
         LEFT JOIN v_structure@octoprod str ON str.id = sae.structure_id
         LEFT JOIN v_structure@octoprod str2 ON str2.id = str.niv2_id
    --On récupére le grade de l'individu
         LEFT JOIN ind_grade indg ON induni.c_individu_chaine = indg.individu_id
         LEFT JOIN octo.grade@octoprod grade ON indg.grade_id = grade.id
    --On récupére l'adresse principale de l'individu
         LEFT JOIN octo.v_individu_adresse_perso@octoprod adr
                   ON adr.individu_id = induni.c_individu_chaine
                       AND (t_principale = 'O' AND adr.source_id IN ('HARP', 'SIHAM'))
         LEFT JOIN octo.pays@octoprod pays ON pays.id = adr.pays_id
    --On récupére le téléphone pro principal de l'indivdu
         LEFT JOIN telephone_pro_principal telpro ON telpro.individu_id = induni.c_individu_chaine
         LEFT JOIN compte ON compte.individu_id = induni.c_individu_chaine
    --On récupére la discipline adaptée directement dans Octopus
         LEFT JOIN cnua cnua ON cnua.individu_id = induni.c_individu_chaine
WHERE i.validite_fin >= (sysdate - (365 * 2)) AND  1 = (CASE WHEN str2.code IS NULL AND i.z_type = 'permanent' AND i.z_statut_id NOT IN ('BIATSS', 'PAMSU') THEN 0 ELSE 1 END )




