CREATE
MATERIALIZED VIEW MV_INTERVENANT_OCTO AS
WITH i AS (
    SELECT DISTINCT code,
                    z_statut_id,
                    --On prend la structure d'affectation principale sinon la première structure saisie
                    FIRST_VALUE(structure_code) OVER (PARTITION BY code, z_statut_id ORDER BY affectation_principale DESC, id_affectation ASC)            structure_code,
                    MIN(source_code)            OVER (partition by code, z_statut_id)                                                                     source_code,
                    MIN(validite_debut)         OVER (partition by code, z_statut_id)                                                                     validite_debut,
                    MAX(validite_fin)           OVER (partition by code, z_statut_id)                                                                     validite_fin
    FROM (SELECT aff.id                                                                   id_affectation,
    			 --Le code de l'intervenant est maintenant le id unique octopus
                 uni.c_individu_chaine                                                    code,
                 s.code                                                                   structure_code,
                 CASE WHEN icto.code_ose IS NOT NULL THEN icto.code_ose ELSE 'AUTRES' END z_statut_id,
                 COALESCE(aff.date_debut, to_date('01/01/1900', 'dd/mm/YYYY'))            validite_debut,
                 COALESCE(aff.date_fin, to_date('01/01/1900', 'dd/mm/YYYY'))              validite_fin,
                 --Le source code n'est plus utilisé par SRC_INTERVENANT on met donc la même valeur que code
                 uni.c_individu_chaine || '-a'                                            source_code,
                 aff.t_principale                                                         affectation_principale
          FROM octo.individu_affectation@octoprod aff
                   JOIN octo.individu_affectation_type@octoprod aft ON (aff.type_id = aft.id)
                   JOIN octo.individu_unique@octoprod uni
                        ON (aff.individu_id = uni.c_individu_chaine AND uni.c_source IN ('HARP'))
                   LEFT JOIN octo.v_individu_contrat_type_ose@octoprod icto
                             ON icto.individu_id = uni.c_individu_chaine AND COALESCE(icto.d_fin, SYSDATE) >= SYSDATE
                   LEFT JOIN octo.STRUCTURE@octoprod s ON s.id = aff.structure_id
          WHERE aff.date_debut - 184 <= SYSDATE
            AND aft.nom = 'AFFECTATION'
            --Uniquement si on veut que les affectations principales
            --AND aff.t_principale = 'O'

          UNION ALL
          --AFFECTATION ENSEIGNEMENT
          SELECT aff.id                                                                   id_affectation,
                 uni.c_individu_chaine                                                    code,
                 s.code                                                                   structure_code,
                 CASE WHEN icto.code_ose IS NOT NULL THEN icto.code_ose ELSE 'AUTRES' END z_statut_id,
                 COALESCE(aff.date_debut, to_date('01/01/1900', 'dd/mm/YYYY'))            validite_debut,
                 COALESCE(aff.date_fin, to_date('01/01/1900', 'dd/mm/YYYY'))              validite_fin,
                 --Le source code n'est plus utilisé par SRC_INTERVENANT on met donc la même valeur que code
                 uni.c_individu_chaine || '-c'                                            source_code,
                 aff.t_principale                                                         affectation_principale
          FROM octo.individu_affectation@octoprod aff
                   JOIN octo.individu_affectation_type@octoprod aft ON (aff.type_id = aft.id)
                   JOIN octo.individu_unique@octoprod uni
                        ON (aff.individu_id = uni.c_individu_chaine AND uni.c_source IN ('HARP'))
                   LEFT JOIN octo.v_individu_contrat_type_ose@octoprod icto
                             ON icto.individu_id = uni.c_individu_chaine AND COALESCE(icto.d_fin, SYSDATE) >= SYSDATE
                   LEFT JOIN octo.STRUCTURE@octoprod s ON s.id = aff.structure_id
          WHERE aff.date_debut - 184 <= SYSDATE
            AND aft.nom = 'ENSEIGNEMENT'
            --Uniquement si on veut que les affectations principales
            -- AND aff.t_principale = 'O'

          UNION ALL
          --AFFECTATION RECHERCHE
          SELECT aff.id                                                                   id_affectation,
                 uni.c_individu_chaine                                                    code,
                 s.code                                                                   structure_code,
                 CASE WHEN icto.code_ose IS NOT NULL THEN icto.code_ose ELSE 'AUTRES' END z_statut_id,
                 COALESCE(aff.date_debut, to_date('01/01/1900', 'dd/mm/YYYY'))            validite_debut,
                 COALESCE(aff.date_fin, to_date('01/01/1900', 'dd/mm/YYYY'))              validite_fin,
                 --Le source code n'est plus utilisé par SRC_INTERVENANT on met donc la même valeur que code
                 uni.c_individu_chaine || '-r'                                            source_code,
                 aff.t_principale                                                         affectation_principale
          FROM octo.individu_affectation@octoprod aff
                   JOIN octo.individu_affectation_type@octoprod aft ON (aff.type_id = aft.id)
                   JOIN octo.individu_unique@octoprod uni
                        ON (aff.individu_id = uni.c_individu_chaine AND uni.c_source IN ('HARP'))
                   LEFT JOIN octo.v_individu_contrat_type_ose@octoprod icto
                             ON icto.individu_id = uni.c_individu_chaine AND COALESCE(icto.d_fin, SYSDATE) >= SYSDATE
                   LEFT JOIN octo.STRUCTURE@octoprod s ON s.id = aff.structure_id
          WHERE aff.date_debut - 184 <= SYSDATE
            AND aft.nom = 'RECHERCHE'
             --Uniquement si on veut que les affectations principales
             --AND aff.t_principale = 'O'
         ) t
),
     telephone_pro_principal AS (
         SELECT indtel.individu_id individu_id,
                tel.numero         numero
         FROM octo.individu_telephone@octoprod indtel
                  JOIN octo.telephone@octoprod tel ON (tel.id = indtel.telephone_id AND tel.t_principal = 'O')
     )
SELECT
    /*Octopus id, id unique pour un individu immuable dans le temps, remplace le code harpege*/
    i.code                                                                                               code,
    /* Code RH */
    ltrim(TO_CHAR(induni.c_src_individu, '99999999'))                                                    code_rh,
    i.source_code                                                                                        source_code,
    /* = supannempid du LDAP Unicaen */
    indc.ldap_uid                                                                                        utilisateur_code,

    str2.code                                                                                            z_structure_code_n2,
    i.structure_code                                                                                     z_structure_code,
    i.z_statut_id                                                                                        z_statut_id,
    indg.grade_id                                                                                        z_grade_id,
    /* Données nécessaires pour calculer la discipline */
    indg.cnu_id                                                                                          z_discipline_id_cnu,
    NULL                                                                                                 z_discipline_id_sous_cnu,
    indg.cnu_specialite_id                                                                               z_discipline_id_spe_cnu,
    indg.discipline_sec_id                                                                               z_discipline_id_dis2deg,
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
    NULL                                                                                                 adresse_numero,
    NULL                                                                                                 z_adresse_numero_compl_id,
    NULL                                                                                                 z_adresse_voirie_id,
    NULL                                                                                                 adresse_voie,
    NULL                                                                                                 adresse_lieu_dit,
    adr.code_postal                                                                                      adresse_code_postal,
    adr.ville_nom                                                                                        adresse_commune,
    adr.pays_id                                                                                          z_adresse_pays_id,
    /* INSEE */
    NULL                                                                                                 numero_insee,
    NULL                                                                                                 numero_insee_provisoire,

    /* Banque */
    NULL                                                                                                 iban,
    NULL                                                                                                 bic,
    NULL                                                                                                 rib_hors_sepa,

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
         JOIN octo.individu_unique@octoprod induni ON (i.code = induni.c_individu_chaine AND induni.c_source IN ('HARP'))
         LEFT JOIN octo.individu@octoprod ind ON ind.c_individu_chaine = induni.c_individu_chaine
         LEFT JOIN octo.individu_grade@octoprod indg ON induni.c_individu_chaine = indg.individu_id
         LEFT JOIN octo.v_individu_adresse_perso@octoprod adr
                   ON adr.individu_id = induni.c_individu_chaine AND t_principale = 'O'
         LEFT JOIN telephone_pro_principal telpro ON telpro.individu_id = induni.c_individu_chaine
    -- On ne prend que les comptes qui ne sont pas étudiants
         LEFT JOIN octo.individu_compte@octoprod indc
                   ON indc.individu_id = induni.c_individu_chaine AND not regexp_like(ldap_uid, 'e[0-9]{8}')
         LEFT JOIN v_structure@octoprod str ON str.code = i.structure_code
         LEFT JOIN v_structure@octoprod str2 ON str.niv2_id = str2.id
WHERE i.validite_fin + 1 >= (SYSDATE - (365 * 2))
--AND ind.nom_usage LIKE 'HEARD%'
--AND i.structure_code = '13A'
--AND induni.c_individu_chaine = 1239
--Filtre octopus id
--AND induni.c_src_individu = 52958 --Filtre code rh (harpége)



