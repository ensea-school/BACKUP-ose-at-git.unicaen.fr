CREATE
MATERIALIZED VIEW MV_INTERVENANT_OCTO AS
WITH i AS (
    SELECT DISTINCT
        code,
        z_statut_id,
        MIN(source_code) OVER (partition by code, z_statut_id)                                                 source_code,
        MIN(validite_debut) OVER (partition by code, z_statut_id)                                              validite_debut,
        MAX(validite_fin) OVER (partition by code, z_statut_id)                                                validite_fin
    FROM(SELECT uni.c_src_individu                                            code,
                icto.code_ose                                                 z_statut_id, --Voir comment récuperer les statuts OSE
                COALESCE(aff.date_debut, to_date('01/01/1900', 'dd/mm/YYYY')) validite_debut,
                COALESCE(aff.date_fin, to_date('01/01/1900', 'dd/mm/YYYY'))   validite_fin,
                --Voir pour le lien entre discipline et individu
                --il faut retrouver a.no_seq_affectation en attendant on met id_orig
                uni.c_individu_chaine || '-a-' || aff.id_orig                 source_code,
                aff.t_principale												  affectation_principale
         FROM individu_affectation@octodev aff
                  JOIN individu_affectation_type@octodev aft ON (aff.type_id = aft.id)
                  JOIN individu_unique@octodev uni ON (aff.individu_id = uni.c_individu_chaine AND uni.c_source = 'HARP')
                  --LEFT JOIN v_individu_contrat_type_ose icto ON icto.individu_id = uni.c_individu_chaine
         WHERE aff.date_debut - 184 <= SYSDATE
           AND aft.nom = 'AFFECTATION'
           --Uniquement si on veut que les affectations principales
           --AND aff.t_principale = 'O'

         UNION ALL
         --AFFECTATION ENSEIGNEMENT
         SELECT uni.c_src_individu                                            code,
                '**AUTRES**'                                                      z_statut_id, --Voir comment récuperer les statuts OSE
                COALESCE(aff.date_debut, to_date('01/01/1900', 'dd/mm/YYYY')) validite_debut,
                COALESCE(aff.date_fin, to_date('01/01/1900', 'dd/mm/YYYY'))   validite_fin,
                --il faut retrouver a.no_seq_affectation en attendant on met id_orig
                uni.c_individu_chaine || '-a-' || aff.id_orig                 source_code,
                aff.t_principale											  affectation_principale
         FROM individu_affectation@octodev aff
                  JOIN individu_affectation_type@octodev aft ON (aff.type_id = aft.id)
                  JOIN individu_unique@octodev uni ON (aff.individu_id = uni.c_individu_chaine AND uni.c_source = 'HARP')
         WHERE aff.date_debut - 184 <= SYSDATE
           AND aft.nom = 'ENSEIGNEMENT'
           --Uniquement si on veut que les affectations principales
           --AND aff.t_principale = 'O'

         UNION ALL
         --AFFECTATION RECHERCHE
         SELECT uni.c_src_individu                                                code,
                '**AUTRES**'                                                      z_statut_id, --Voir comment récuperer les statuts OSE
                COALESCE(aff.date_debut, to_date('01/01/1900', 'dd/mm/YYYY'))     validite_debut,
                COALESCE(aff.date_fin, to_date('01/01/1900', 'dd/mm/YYYY'))       validite_fin,
                --il faut retrouver a.no_seq_affectation en attendant on met id_orig
                uni.c_individu_chaine || '-a-' || aff.id_orig                     source_code,
                aff.t_principale												  affectation_principale
         FROM individu_affectation@octodev aff
                  JOIN individu_affectation_type@octodev aft ON (aff.type_id = aft.id)
                  JOIN individu_unique@octodev uni ON (aff.individu_id = uni.c_individu_chaine AND uni.c_source = 'HARP')
         WHERE aff.date_debut - 184 <= SYSDATE
           AND aft.nom = 'RECHERCHE'
            --Uniquement si on veut que les affectations principales
            --AND aff.t_principale = 'O'
        ) t
),
     telephone_pro_principal AS(
         SELECT
             indtel.individu_id        individu_id,
             tel.numero          numero
         FROM individu_telephone@octodev    indtel
                  JOIN telephone@octodev tel ON (tel.id = indtel.telephone_id AND tel.t_principal = 'O')
     )
SELECT
    /*Octopus id, id unique pour un individu immuable dans le temps, remplace le code harpege*/
    induni.c_individu_chaine                          code,
    /* Code RH */
    ltrim(TO_CHAR(induni.c_src_individu, '99999999')) code_rh,
    i.source_code                                     source_code,
    /* = supannempid du LDAP Unicaen */
    indc.ldap_uid                                     utilisateur_code,
    indg.structure_id                                 z_structure_id,
    i.z_statut_id                                     z_statut_id,
    /* Récupération du grade actuel */
    --pbs_divers__cicg.c_grade@harpprod(individu.no_individu, COALESCE(i.validite_fin,SYSDATE) ) z_grade_id,
    indg.grade_id                                     z_grade_id,
    /* Données nécessaires pour calculer la discipline */
    indg.cnu_id                                       z_discipline_id_cnu,
    NULL                                              z_discipline_id_sous_cnu,
    indg.cnu_specialite_id                            z_discipline_id_spe_cnu,
    indg.discipline_sec_id                            z_discipline_id_dis2deg,
    /* Données identifiantes de base */
    CASE ind.sexe WHEN 'M' THEN 'M.' ELSE 'Mme' END   z_civilite_id,
    initcap(ind.nom_usage)                            nom_usuel,
    initcap(ind.prenom)                               prenom,
    ind.d_naissance                                   date_naissance,


    /* Données identifiantes complémentaires */
    initcap(ind.nom_famille)                          nom_patronymique,
    --Pour le moment les communes ne sont pas dans OCTOPUS commune.libelle_commune
    ind.ville_de_naissance                            commune_naissance,
    ind.c_pays_naissance                              z_pays_naissance_id,
    ind.c_dept_naissance                              z_departement_naissance_id,
    ind.c_pays_nationalite                            z_pays_nationalite_id,

    telpro.numero                                     tel_pro,
    ind.tel_perso                                     tel_perso,
    indc.email                                        email_pro,
    ind.email_perso                                   email_perso,
    /* Adresse */
    '**Adr précision**'                               adresse_precisions,
    '**Adr N° voie**'                                 adresse_numero,
    '**Adr complement**'                              z_adresse_numero_compl_id,
    '**Adr voirie**'                                  z_adresse_voirie_id,
    '**Adr voie**'                                    adresse_voie,
    '**Adr lieu dit**'                                adresse_lieu_dit,
    '**Adr code postal**'                             adresse_code_postal,
    '**Adr ville**'                                   adresse_commune,
    '**Adr pays**'                                    z_adresse_pays_id,
    /* INSEE */
    '**INSEE**'                                       numero_insee,
    '**INSEE provisoire**'                            numero_insee_provisoire,

    /* Banque */
    '**iban**'                                        iban,
    '**bic**'                                         bic,
    '**RIB hors sepa**'                               rib_hors_sepa

FROM i
         JOIN individu_unique@octodev induni ON (i.code = induni.c_src_individu AND induni.c_source = 'HARP')
         LEFT JOIN individu@octodev ind ON ind.c_individu_chaine = induni.c_individu_chaine
         LEFT JOIN individu_grade@octodev indg ON induni.c_individu_chaine = indg.individu_id
         LEFT JOIN telephone_pro_principal telpro ON telpro.individu_id = induni.c_individu_chaine
         LEFT JOIN individu_compte@octodev indc ON indc.individu_id = induni.c_individu_chaine
WHERE i.validite_fin + 1 >= (SYSDATE - (365 * 2))
--AND code = 184
