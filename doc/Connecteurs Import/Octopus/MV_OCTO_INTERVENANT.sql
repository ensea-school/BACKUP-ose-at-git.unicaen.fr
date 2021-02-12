CREATE
MATERIALIZED VIEW MV_INTERVENANT_OCTO AS
   WITH i AS (
        SELECT DISTINCT code,
                        z_statut_id,
                        MIN(source_code)    OVER (partition by code, z_statut_id)            source_code,
                        MIN(validite_debut) OVER (partition by code, z_statut_id)            validite_debut,
                        MAX(validite_fin)   OVER (partition by code, z_statut_id)            validite_fin
        FROM (
                --Step 1 : On prend tous les individus qui ont ou ont eu un contrat à l'université
                 SELECT icto.individu_id                                                         code,
                        CASE WHEN icto.code_ose IS NOT NULL THEN icto.code_ose ELSE 'AUTRES' END z_statut_id,
                        icto.id_orig                                                             source_code,
                        COALESCE(icto.d_debut, to_date('01/01/1900', 'dd/mm/YYYY'))              validite_debut,
                        COALESCE(icto.d_fin, to_date('01/01/9999', 'dd/mm/YYYY'))                validite_fin
                 FROM octo.v_individu_contrat_type_ose@octoprod icto
                 JOIN octo.individu_unique@octoprod uni ON icto.individu_id = uni.c_individu_chaine
                 WHERE icto.d_debut - 184 <= SYSDATE

                 UNION ALL
                 -- Step 2 : on prend tout le reste potentiel vacataire, notamment les hébergés
                 SELECT uni.c_individu_chaine                                         code,
                        'AUTRES'                                                      z_statut_id,
                        uni.c_individu_chaine || '-autre'                         source_code,
                        COALESCE(inds.d_debut, to_date('01/01/1900', 'dd/mm/YYYY')) validite_debut,
                        COALESCE(inds.d_fin, to_date('01/01/9999', 'dd/mm/YYYY'))   validite_fin
                 FROM octo.individu_unique@octoprod uni
                 JOIN octo.individu_statut@octoprod inds ON inds.individu_id = uni.c_individu_chaine
                 WHERE inds.d_debut - 184 <= SYSDATE
                 --Combinaison des témoins octopus pour récupérer les bonnes populations
                 AND  ((inds.t_enseignant = 'O' AND inds.t_vacataire = 'O')
                 		OR (inds.t_enseignant = 'O' AND inds.t_heberge = 'O')
                 		OR (inds.t_vacataire = 'O'))
                 AND inds.c_source IN ('HARP', 'OCTO', 'SIHAM')



             ) t
    ),
         --Trouver le tel pro principal de l'intervenant
         telephone_pro_principal AS (
             SELECT indtel.individu_id individu_id,
                    tel.numero         numero
             FROM octo.individu_telephone@octoprod indtel
                      JOIN octo.telephone@octoprod tel ON (tel.id = indtel.telephone_id AND tel.t_principal = 'O')
         ),
         --Trouver la structure d'affectation principale de l'intervenant
         structure_principale_individu AS (
             SELECT DISTINCT
                uni.c_individu_chaine,
                FIRST_VALUE(aff.structure_id) OVER (PARTITION BY uni.c_individu_chaine ORDER BY aff.date_fin DESC)  z_structure_id
                FROM octo.individu_affectation@octoprod aff
                JOIN octo.individu_unique@octoprod uni ON uni.c_individu_chaine = aff.individu_id
                WHERE aff.t_principale = 'O'
                AND aff.date_fin + 1 >= (SYSDATE - (365 * 2))
         )
SELECT DISTINCT
    /*Octopus id, id unique pour un individu immuable dans le temps, remplace le code harpege*/
    i.code                                                                                               code,
    /* Code RH : FIRST_VALUE pour être sûre de récupérer le code rh et non le code Apogee dans le cas ou l'individu est à la fois dans harpege/Siham et Apogee*/
    FIRST_VALUE(ltrim(TO_CHAR(induni.c_src_individu, '99999999')))                                       OVER (PARTITION BY i.code ORDER BY
    																						 CASE WHEN induni.c_source = 'SIHAM' THEN 1
    																						 	  WHEN induni.c_source = 'HARP'  THEN 2
    																						 	  WHEN induni.c_source = 'OCTO'  THEN 3
    																						 	  WHEN induni.c_source = 'APO'   THEN 4
    																						 END ASC)    code_rh,
    indc.ldap_uid                                                                                        utilisateur_code,
    str.code                                                                                             z_structure_code,
    CASE WHEN str2.code <> str.code THEN str2.code ELSE NULL END                                         z_structure_code_n2,
    i.z_statut_id                                                                                        z_statut_id,
    grade.c_grade                                                                                        z_grade_id,
    /* Données nécessaires pour calculer la discipline */
    cnu.c_cnu                                                                                            z_discipline_id_cnu,
    CAST(NULL AS varchar2(255))                                                                          z_discipline_id_sous_cnu,
    cnus.c_cnu_specialite                                                                                z_discipline_id_spe_cnu,
    dissec.c_discipline                                                                                  z_discipline_id_dis2deg,
    /* Données identifiantes de base */
    CASE ind.sexe WHEN 'M' THEN 'M.' ELSE 'Mme' END                                                      z_civilite_id,
    initcap(ind.nom_usage)                                                                               nom_usuel,
    initcap(ind.prenom)                                                                                  prenom,
    ind.d_naissance                                                                                      date_naissance,
    /* Données identifiantes complémentaires */
    initcap(ind.nom_famille)                                                                             nom_patronymique,
    ind.ville_de_naissance                                                                               commune_naissance,
    ind.c_pays_naissance                                                                                 z_pays_naissance_id,
    ind.c_dept_naissance                                                                                 z_departement_naissance_id,
    ind.c_pays_nationalite                                                                               z_pays_nationalite_id,
    telpro.numero                                                                                        tel_pro,
    ind.tel_perso                                                                                        tel_perso,
    indc.email                                                                                           email_pro,
    ind.email_perso                                                                                      email_perso,
    /* Adresse */
    trim(adr.adresse1 || ' ' || adr.adresse2 || ' ' || adresse3)                                         adresse_precisions,
    CAST(NULL AS varchar2(255))                                                                          adresse_numero,
    CAST(NULL AS varchar2(255))                                                                          z_adresse_numero_compl_id,
    CAST(NULL AS varchar2(255))                                                                          z_adresse_voirie_id,
    CAST(NULL AS varchar2(255))                                                                          adresse_voie,
    CAST(NULL AS varchar2(255))                                                                          adresse_lieu_dit,
    adr.code_postal                                                                                      adresse_code_postal,
    adr.ville_nom                                                                                        adresse_commune,
    adr.pays_id                                                                                          z_adresse_pays_id,
    /* INSEE */
    CAST(NULL AS varchar2(255))                                                                          numero_insee,
    CAST(NULL AS varchar2(255))                                                                          numero_insee_provisoire,
    /* Banque */
    CAST(NULL AS varchar2(255))                                                                          iban,
    CAST(NULL AS varchar2(255))                                                                          bic,
    CAST(NULL AS varchar2(255))                                                                          rib_hors_sepa,
    /* Données complémentaires */
    CAST(NULL AS varchar2(255))                                                                          autre_1,
    CAST(NULL AS varchar2(255))                                                                          autre_2,
    CAST(NULL AS varchar2(255))                                                                          autre_3,
    CAST(NULL AS varchar2(255))                                                                          autre_4,
    CAST(NULL AS varchar2(255))                                                                          autre_5,
    /* Employeur */
    CAST(NULL AS varchar2(255))                                                                          z_employeur_id,
    CASE WHEN i.validite_debut = to_date('01/01/1900', 'dd/mm/YYYY') THEN NULL ELSE i.validite_debut END validite_debut,
    CASE WHEN i.validite_fin = to_date('01/01/9999', 'dd/mm/YYYY') THEN NULL ELSE i.validite_fin END     validite_fin
FROM i
         JOIN octo.individu_unique@octoprod induni
              ON i.code = induni.c_individu_chaine --AND induni.c_source IN ('HARP', 'OCTO', 'SIHAM'))
--		 JOIN octo.individu_statut@octoprod inds ON inds.individu_id = induni.c_individu_chaine AND inds.c_source IN ('HARP', 'OCTO', 'SIHAM')
         LEFT JOIN octo.individu@octoprod ind ON ind.c_individu_chaine = induni.c_individu_chaine
    --On récupére la structure principale de l'individu
         LEFT JOIN structure_principale_individu spi ON spi.c_individu_chaine = induni.c_individu_chaine
    --On récupére le grade de l'individu
         LEFT JOIN octo.individu_grade@octoprod indg ON induni.c_individu_chaine = indg.individu_id
    AND COALESCE(indg.d_fin, to_date('01/01/9999', 'dd/mm/YYYY')) > SYSDATE
    AND COALESCE(indg.d_debut, to_date('01/01/1900', 'dd/mm/YYYY')) < SYSDATE
         LEFT JOIN octo.cnu@octoprod cnu ON indg.cnu_id = cnu.id
         LEFT JOIN octo.cnu_specialite@octoprod cnus ON indg.cnu_specialite_id = cnus.id
         LEFT JOIN octo.discipline_sec@octoprod dissec ON indg.discipline_sec_id = dissec.id
         LEFT JOIN octo.grade@octoprod grade ON indg.grade_id = grade.id
    --On récupére l'adresse principale de l'individu
         LEFT JOIN octo.v_individu_adresse_perso@octoprod adr
                   ON adr.individu_id = induni.c_individu_chaine
                       AND (t_principale = 'O' AND adr.source_id IN ('HARP', 'SIHAM'))
    --On récupére le téléphone pro principal de l'indivdu
         LEFT JOIN telephone_pro_principal telpro ON telpro.individu_id = induni.c_individu_chaine
    -- On ne prend que les comptes qui ne sont pas étudiants
         LEFT JOIN octo.individu_compte@octoprod indc
                   ON indc.individu_id = induni.c_individu_chaine AND not regexp_like(ldap_uid, 'e[0-9]{8}')
    --On récupére le code de la structure d'affectation principal de l'individu
         LEFT JOIN v_structure@octoprod str ON str.id = spi.z_structure_id
         LEFT JOIN v_structure@octoprod str2 ON str.niv2_id = str2.id
WHERE i.validite_fin >= (SYSDATE - (365 * 2))
--AND induni.c_individu_chaine = 101-- Filtre avec code octopus
--AND ind.nom_famille = 'DURANDY'
--AND induni.c_src_individu = 45053-- Filtre avec code harpege
--ORDER BY i.code ASC







