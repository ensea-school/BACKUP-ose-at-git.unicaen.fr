CREATE MATERIALIZED VIEW MV_INTERVENANT AS
WITH
i AS (
  SELECT -- permet de fusionner les données pour ne conserver qu'une des tuples (code,statut) sans doublons
    code,
    statut,
    MAX(z_discipline_id_cnu)      z_discipline_id_cnu,
    MAX(z_discipline_id_sous_cnu) z_discipline_id_sous_cnu,
    MAX(z_discipline_id_spe_cnu)  z_discipline_id_spe_cnu,
    MAX(z_discipline_id_dis2deg)  z_discipline_id_dis2deg,
    MAX(date_fin) date_fin
  FROM
  (
    SELECT
      i.*, -- permet de ne sélectionner que les données (contrats, etc) se terminant le plus tard possible ou bien sans date de fin
      CASE WHEN COUNT(*) OVER (PARTITION BY code,statut) > 1 THEN
        CASE WHEN COALESCE(date_fin,SYSDATE) = MAX(COALESCE(date_fin,SYSDATE)) OVER (PARTITION BY code,statut) THEN 1 ELSE 0 END
      ELSE 1 END ok2,
      COUNT(*) OVER (PARTITION BY code,statut,date_fin) dc
    FROM
    (
      SELECT
        i.*,
        CASE -- permet de supprimer les données obsolètes ou futures s'il y en a des actuelles (contrat en cours, etc)
          WHEN
            COUNT(*) OVER (PARTITION BY i.code) > 1
            AND MAX(i.actuel) OVER (PARTITION BY i.code) = 1
            AND i.actuel = 0
          THEN 0 ELSE 1 END ok
      FROM
      (
        SELECT
          ca.no_dossier_pers                                 code,
          CASE -- lien entre le contrat de travail Harpège et le statut d'intervenant OSE
            WHEN ct.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
            WHEN ct.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
            WHEN ct.c_type_contrat_trav IN ('GD','PN')                THEN 'ENS_CONTRACT_CDD'
            WHEN ct.c_type_contrat_trav IN ('ED')                     THEN 'ENS_CH_CONTRAT'
            WHEN ct.c_type_contrat_trav IN ('GI','EI')                THEN 'ENS_CONTRACT_CDI'
            WHEN ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
            WHEN ct.c_type_contrat_trav IN ('MB','MP')                THEN 'MAITRE_LANG'
            WHEN ct.c_type_contrat_trav IN ('PT')                     THEN 'HOSPITALO_UNIV'
            WHEN ct.c_type_contrat_trav IN ('C3','CA','CB','CD','CS','DD','HA','HD','HS','MA','S3','SX','SW','SY','SZ','VA') THEN 'BIATSS'
            WHEN ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET') THEN 'NON_AUTORISE'
            ELSE 'AUTRES'
          END                                                statut,
          ca.c_section_cnu                                   z_discipline_id_cnu,
          ca.c_sous_section_cnu                              z_discipline_id_sous_cnu,
          ca.c_specialite_cnu                                z_discipline_id_spe_cnu,
          ca.c_disc_second_degre                             z_discipline_id_dis2deg,
          COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav) date_fin,
          CASE WHEN
            SYSDATE BETWEEN ca.d_deb_contrat_trav-1 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          contrat_avenant@harpprod ca
          JOIN contrat_travail@harpprod ct ON ct.no_dossier_pers = ca.no_dossier_pers AND ct.no_contrat_travail = ca.no_contrat_travail
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN ca.d_deb_contrat_trav-184 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+184

        UNION

        SELECT
          a.no_dossier_pers                                  code,
          CASE -- lien entre le type de population Harpège et le statut d'intervenant OSE
            WHEN c.c_type_population IN ('DA','OA','DC')              THEN 'ENS_2ND_DEG'
            WHEN c.c_type_population IN ('SA')                        THEN 'ENS_CH'
            WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')    THEN 'BIATSS'
            WHEN c.c_type_population IN ('MG','SB')                   THEN 'HOSPITALO_UNIV'
            ELSE 'AUTRES'
          END                                                statut,
          psc.c_section_cnu                                  z_discipline_id_cnu,
          psc.c_sous_section_cnu                             z_discipline_id_sous_cnu,
          psc.c_specialite_cnu                               z_discipline_id_spe_cnu,
          pss.c_disc_second_degre                            z_discipline_id_dis2deg,
          a.d_fin_affectation                                date_fin,
          CASE WHEN
            SYSDATE BETWEEN a.d_deb_affectation-1 AND COALESCE(a.d_fin_affectation,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          affectation@harpprod a
          LEFT JOIN carriere@harpprod c ON c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
          LEFT JOIN periodes_sp_cnu@harpprod    psc                ON psc.no_dossier_pers = a.no_dossier_pers AND psc.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(psc.d_deb,a.d_fin_affectation,SYSDATE) AND COALESCE(psc.d_fin,a.d_fin_affectation,SYSDATE)
          LEFT JOIN periodes_sp_sd_deg@harpprod pss                ON pss.no_dossier_pers = a.no_dossier_pers AND pss.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(pss.d_deb,a.d_fin_affectation,SYSDATE) AND COALESCE(pss.d_fin,a.d_fin_affectation,SYSDATE)
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN a.d_deb_affectation-184 AND COALESCE(a.d_fin_affectation,SYSDATE)+184

        UNION

        SELECT
          ch.no_individu                                     code,
          'AUTRES'                                           statut, -- pas de statut de défini ici
          ch.c_section_cnu                                   z_discipline_id_cnu,
          ch.c_sous_section_cnu                              z_discipline_id_sous_cnu,
          NULL                                               z_discipline_id_spe_cnu,
          ch.c_disc_second_degre                             z_discipline_id_dis2deg,
          ch.d_fin_str_trav                                  date_fin,
          CASE WHEN
            SYSDATE BETWEEN ch.d_deb_str_trav-1 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          chercheur@harpprod ch
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN ch.d_deb_str_trav-184 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+184
      ) i
    ) i WHERE ok = 1
  )i WHERE ok2 = 1 GROUP BY code,statut
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
SELECT
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             code,
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END z_civilite_id,
  initcap(individu.nom_usuel)                                 nom_usuel,
  initcap(individu.prenom)                                    prenom,
  initcap(individu.nom_patronymique)                          nom_patronymique,
  individu.d_naissance                                        date_naissance,
  individu.c_pays_naissance                                   z_pays_naissance_id,
  individu.c_dept_naissance                                   z_departement_naissance_id,
  COALESCE(commune.libelle_commune,individu.ville_de_naissance) commune_naissance,
  individu.c_pays_nationnalite                                z_pays_nationalite_id,
  individu_telephone.no_telephone                             tel_pro,
  individu.no_tel_portable                                    tel_mobile,
  CASE -- Si le mail n'est pas renseigné dans Harpège, alors on va le chercher dans notre LDAP
    WHEN INDIVIDU_E_MAIL.NO_E_MAIL IS NULL THEN
      UCBN_LDAP.hid2mail(individu.no_individu) -- (à adapter en fonction de l'établissement)
    ELSE
      INDIVIDU_E_MAIL.NO_E_MAIL
  END                                                         email,
  CASE WHEN liste_noire.code IS NULL THEN i.statut ELSE 'NON_AUTORISE' END z_statut_id,
  sc.c_structure_n2                                           z_structure_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             source_code,
  code_insee.no_insee || TO_CHAR(code_insee.cle_insee)        numero_insee,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END  numero_insee_provisoire,
  comptes.iban                                                iban,
  comptes.bic                                                 bic,
  pbs_divers__cicg.c_grade@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) ) z_grade_id,
  i.z_discipline_id_cnu                                       z_discipline_id_cnu,
  i.z_discipline_id_sous_cnu                                  z_discipline_id_sous_cnu,
  i.z_discipline_id_spe_cnu                                   z_discipline_id_spe_cnu,
  i.z_discipline_id_dis2deg                                   z_discipline_id_dis2deg,
  utl_raw.cast_to_varchar2((nlssort(to_char(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom), 'nls_sort=binary_ai'))) critere_recherche,
  i.date_fin
FROM
                                        i
       JOIN individu@harpprod           individu           ON individu.no_individu           = i.code
  LEFT JOIN liste_noire                                    ON liste_noire.code               = i.code
  LEFT JOIN MV_UNICAEN_STRUCTURE_CODES  sc                 ON sc.c_structure                 = pbs_divers__cicg.c_structure_globale@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) )
  LEFT JOIN commune@harpprod            commune            ON individu.c_commune_naissance   = commune.c_commune
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail    ON individu_e_mail.no_individu    = i.code
  LEFT JOIN individu_telephone@harpprod individu_telephone ON individu_telephone.no_individu = i.code AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O'
  LEFT JOIN code_insee@harpprod         code_insee         ON code_insee.no_dossier_pers     = i.code
  LEFT JOIN                             comptes            ON comptes.no_individu            = i.code AND comptes.rank_compte = comptes.nombre_comptes;
