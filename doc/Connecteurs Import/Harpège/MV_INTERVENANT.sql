CREATE MATERIALIZED VIEW MV_INTERVENANT AS
WITH
i AS (
  SELECT
    ca.no_dossier_pers                                 code,
    ct.c_type_contrat_trav                             z_statut_id_contrat_trav,
    NULL                                               z_statut_id_type_pop,
    ca.c_section_cnu                                   z_discipline_id_cnu,
    ca.c_sous_section_cnu                              z_discipline_id_sous_cnu,
    ca.c_specialite_cnu                                z_discipline_id_spe_cnu,
    ca.c_disc_second_degre                             z_discipline_id_dis2deg,
    MAX(COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav)) date_fin
  FROM
    contrat_avenant@harpprod ca
    JOIN contrat_travail@harpprod ct ON ct.no_dossier_pers = ca.no_dossier_pers AND ct.no_contrat_travail = ca.no_contrat_travail
  WHERE -- on sélectionne les données même 6 mois avant et plus d'un an après
    SYSDATE BETWEEN ca.d_deb_contrat_trav-184 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+400
  GROUP BY
    ca.no_dossier_pers, ct.c_type_contrat_trav, ca.c_section_cnu, ca.c_sous_section_cnu, ca.c_specialite_cnu, ca.c_disc_second_degre

  UNION ALL

  SELECT
    a.no_dossier_pers                                  code,
    NULL                                               z_statut_id_contrat_trav,
    c.c_type_population                                z_statut_id_type_pop,
    psc.c_section_cnu                                  z_discipline_id_cnu,
    psc.c_sous_section_cnu                             z_discipline_id_sous_cnu,
    psc.c_specialite_cnu                               z_discipline_id_spe_cnu,
    pss.c_disc_second_degre                            z_discipline_id_dis2deg,
    MAX(a.d_fin_affectation)                           date_fin
  FROM
    affectation@harpprod a
    LEFT JOIN carriere@harpprod c ON c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
    LEFT JOIN periodes_sp_cnu@harpprod    psc                ON psc.no_dossier_pers = a.no_dossier_pers AND psc.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(psc.d_deb,a.d_fin_affectation,SYSDATE) AND COALESCE(psc.d_fin,a.d_fin_affectation,SYSDATE)
    LEFT JOIN periodes_sp_sd_deg@harpprod pss                ON pss.no_dossier_pers = a.no_dossier_pers AND pss.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(pss.d_deb,a.d_fin_affectation,SYSDATE) AND COALESCE(pss.d_fin,a.d_fin_affectation,SYSDATE)
  WHERE -- on sélectionne les données même 6 mois avant et plus d'un an après
    SYSDATE BETWEEN a.d_deb_affectation-184 AND COALESCE(a.d_fin_affectation,SYSDATE)+400
  GROUP BY
    a.no_dossier_pers, c.c_type_population, psc.c_section_cnu, psc.c_sous_section_cnu, psc.c_specialite_cnu, pss.c_disc_second_degre

  UNION ALL

  SELECT
    ch.no_individu                                     code,
    NULL                                               z_statut_id_contrat_trav,
    NULL                                               z_statut_id_type_pop,
    ch.c_section_cnu                                   z_discipline_id_cnu,
    ch.c_sous_section_cnu                              z_discipline_id_sous_cnu,
    NULL                                               z_discipline_id_spe_cnu,
    ch.c_disc_second_degre                             z_discipline_id_dis2deg,
    ch.d_fin_str_trav                                  date_fin
  FROM
    chercheur@harpprod ch
  WHERE -- on sélectionne les données même 6 mois avant et plus d'un an après
    SYSDATE BETWEEN ch.d_deb_str_trav-184 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+400
),
comptes (no_individu, rank_compte, nombre_comptes, IBAN, BIC) AS (
  SELECT -- récupération des comptes en banque
    i.no_dossier_pers no_individu,
    dense_rank() over(partition by i.no_dossier_pers order by d_creation) rank_compte,
    count(*) over(partition by i.no_dossier_pers)                   nombre_comptes,
    CASE WHEN i.no_dossier_pers IS NOT NULL THEN
      trim( NVL(i.c_pays_iso || i.cle_controle,'FR00') || ' ' ||
      substr(i.c_banque,0,4) || ' ' ||
      substr(i.c_banque,5,1) || substr(i.c_guichet,0,3) || ' ' ||
      substr(i.c_guichet,4,2) || substr(i.no_compte,0,2) || ' ' ||
      substr(i.no_compte,3,4) || ' ' ||
      substr(i.no_compte,7,4) || ' ' ||
      substr(i.no_compte,11) || i.cle_rib) ELSE NULL END            IBAN,
    CASE WHEN i.no_dossier_pers IS NOT NULL THEN i.c_banque_bic || ' ' || i.c_pays_bic || ' ' || i.c_emplacement || ' ' || i.c_branche ELSE NULL END BIC
  from
    individu_banque@harpprod i
)
SELECT DISTINCT
  /* Code de l'intervenant = numéro Harpège */
  ltrim(TO_CHAR(individu.no_individu,'99999999'))               code,

  /* = supannempid du LDAP Unicaen */
  lpad(ltrim(TO_CHAR(individu.no_individu,'99999999')), 8, '0') utilisateur_code,

  /* Code structure Harpège (il sera plus tard transformé par la vue source en ID de strucutre OSE) */
  sc.c_structure_n2                                             z_structure_id,

  /* Données nécessaires pour calculer le statut */
  i.z_statut_id_contrat_trav                                    z_statut_id_contrat_trav,
  i.z_statut_id_type_pop                                        z_statut_id_type_pop,

  /* Récupération du grade actuel */
  pbs_divers__cicg.c_grade@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) ) z_grade_id,

  /* Données nécessaires pour calculer la discipline */
  i.z_discipline_id_cnu                                         z_discipline_id_cnu,
  i.z_discipline_id_sous_cnu                                    z_discipline_id_sous_cnu,
  i.z_discipline_id_spe_cnu                                     z_discipline_id_spe_cnu,
  i.z_discipline_id_dis2deg                                     z_discipline_id_dis2deg,

  /* Données identifiantes de base */
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END   z_civilite_id,
  initcap(individu.nom_usuel)                                   nom_usuel,
  initcap(individu.prenom)                                      prenom,
  individu.d_naissance                                          date_naissance,

  /* Données identifiantes complémentaires */
  initcap(individu.nom_patronymique)                            nom_patronymique,
  COALESCE(commune.libelle_commune,individu.ville_de_naissance) commune_naissance,
  individu.c_pays_naissance                                     z_pays_naissance_id,
  individu.c_dept_naissance                                     z_dep_naissance_id,
  individu.c_pays_nationnalite                                  z_pays_nationalite_id,

  /* Coordonnées */
  individu_telephone.no_telephone                               tel_pro,
  adresse.telephone_domicile                                    tel_perso,
  INDIVIDU_E_MAIL.NO_E_MAIL                                     email_pro,
  CAST(NULL AS varchar2(255))                                   email_perso,

  /* Adresse */
  TRIM(UPPER(adresse.habitant_chez))                            adresse_precisions,
  adresse.no_voie                                               adresse_numero,
  adresse.bis_ter                                               z_adresse_numero_compl_id,
  adresse.c_voie                                                z_adresse_voirie_id,
  TRIM(adresse.nom_voie)                                        adresse_voie,
  CASE WHEN adresse.localite = adresse.ville THEN NULL ELSE adresse.localite END adresse_lieu_dit,
  coalesce( adresse.cp_etranger, adresse.code_postal )          adresse_code_postal,
  trim(adresse.ville)                                           adresse_commune,
  adresse.c_pays                                                z_adresse_pays_id,

  /* INSEE */
  TRIM(code_insee.no_insee) || TRIM(TO_CHAR(code_insee.cle_insee)) numero_insee,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END    numero_insee_provisoire,

  /* Banque */  
  comptes.iban                                                  iban,
  comptes.bic                                                   bic,
  0                                                             rib_hors_sepa,

  /* Données complémentaires */
  CAST(NULL AS varchar2(255))                                   autre_1,
  CAST(NULL AS varchar2(255))                                   autre_2,
  CAST(NULL AS varchar2(255))                                   autre_3,
  CAST(NULL AS varchar2(255))                                   autre_4,
  CAST(NULL AS varchar2(255))                                   autre_5,

  /* Employeur */
  CAST(NULL AS varchar2(255))                                   z_employeur_id,

  /* Chaîne de caractères "réduite" pour optimiser les recherches */
  utl_raw.cast_to_varchar2((nlssort(to_char(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom), 'nls_sort=binary_ai'))) critere_recherche
FROM
                                         i
       JOIN individu@harpprod            individu           ON individu.no_individu           = i.code
  LEFT JOIN adresse_personnelle@harpprod adresse            ON adresse.no_individu            = individu.no_individu AND adresse.d_creation <= sysdate AND adresse.tem_adr_pers_princ = 'O'
  LEFT JOIN SRC_HARPEGE_STRUCTURE_CODES  sc                 ON sc.c_structure                 = pbs_divers__cicg.c_structure_globale@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) )
  LEFT JOIN commune@harpprod             commune            ON individu.c_commune_naissance   = commune.c_commune
  LEFT JOIN individu_e_mail@harpprod     individu_e_mail    ON individu_e_mail.no_individu    = i.code
  LEFT JOIN individu_telephone@harpprod  individu_telephone ON individu_telephone.no_individu = i.code AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O'
  LEFT JOIN code_insee@harpprod          code_insee         ON code_insee.no_dossier_pers     = i.code
  LEFT JOIN                              comptes            ON comptes.no_individu            = i.code AND comptes.rank_compte = comptes.nombre_comptes