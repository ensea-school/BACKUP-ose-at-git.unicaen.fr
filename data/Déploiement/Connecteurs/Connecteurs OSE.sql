
  ---------------------------------------------------------------
  --|                 *** Connecteurs OSE ***                 |--
  ---------------------------------------------------------------
  --|                                                         |--
  --|  Auteur : Laurent Lécluse (laurent.lecluse@unicaen.fr)  |--
  --|  Actualisé le 14 février 2018                           |--
  --|                                                         |--
  ---------------------------------------------------------------



--
-- Informations diverses.
-- ______________________
--
-- Le module Import de OSE se charge se faire le lien avec la base de données du logiciel.
-- Pour cela, il génère des vues différentielles.
-- Ces dernières permettent de déterminer les différences entre les données fournies par les vues sources et les tables correspondantes.
-- Il gérère également des procédures de mise à jour qui vont se baser sur les vues différentielles pour mettre à jour OSE.
-- En cas de modification d'une vue source, il faut donc procéder à la mise à jour des vues et procédures d'import.
-- Une interface d'administration (menu Administration / Import) vous permettra de :
-- - visualiser le différentiel des données entre vos sources et OSE, et de mettre à jour l'application au cas par cas
-- - gérer vos différentes sources de données
-- - visualiser (page Branchement) les tables synchronisables de OSE et leurs spécifications (utile de nouveaux connecteurs une l'adaptation de ceux existants)
-- - mettre à jour les vues et les procédures d'import
--
-- Le présent fichier s'organise en plusieurs parties:
-- 1 : RH et divers avec HARPEGE
-- 2 : l'offre de formation avec APOGEE et FCA MANAGER.
-- 3 : comptabilité analytique avec SIFAC
--
-- Chaque requête est à adapter selon vos besoins.
-- Les connecteurs ne seront pas "écrasés" ou impactés par les futures mises à jour de OSE (sauf évolution de l'architecture
-- du logiciel, auquel cas vous serez prévenu et invité à adapter votre connecteur avant tout nouveau déploiement).
--
-- Certaines tables peuvent avoir plusieurs sources de données. Par exemple, pour l'offre de formation, des éléments
-- peuvent aussi bien venir d'Apogée que de FCA Manager, mais aussi être créées en local dans OSE.
-- Certaines tables contiennent des données calculées sur la base d'autres données présentes dans OSE.
-- La source 'Calcul' a été créée pour identifier ces données.
-- C'est par exemple le cas pour les tables TYPE_INTERVENTION_EP, TYPE_MODULATEUR_EP et CENTRE_COUT_STRUCTURE
-- Les données partent donc de OSE pour retourner vers OSE, après transformation
-- Source de données à ne pas confondre avec la source OSE qui concerne les données saisies directement dans les tables
-- correspondantes. Ces données ne sont jamais synchronisées puisques saisies localement.
--
--
--
-- Informations sur l'architecture des connecteurs.
-- ________________________________________________
--
-- Un connecteur est composé d'au moins deux parties :
-- 1 : la requête qui va permettre de remonter les données selon le schéma OSE
--     Cette requête peut s'apppuyer le cas échéant sur d'autres dispositifs (vues matérialisées, scripts de peuplement de tables, etc)
--     Pour les identifiants, si le champ fait référence à une autre table, alors on pourra fournir une valeur qui permettra de retrouver ensuite l'identifiant OSE.
--     On utilisera donc pour convention z_ + nom du champ pour signaler que la données transmise n'est pas celle attendue.
--     Cette requête peut éventuellement être intégrée directement dans la vue source.
--
-- 2 : la vue source, qui fournit à OSE les données nécessaires.
--     Si des champs z_* existent, il convient alors de les exploiter pour retrouver l'identifiant OSE correspondant à leur contenu.
--     Cela se fait le plus souvent à l'aide d'une jointure.
--     Par exemple, on donne U10 dans z_structure_id. Or U10 est le code de la composante IAE.
--     Donc on retourne structure.id si structure.source_code = z_structure_id à l'aide d'une jointure à gauche.
--
--
-- Informations sur le connecteur Apogée.
-- ______________________________________
--
-- Le connecteur pour l'offre de formation est composé de trois parties :
-- 1 : la partie Apogée, avec un script et des tables spécifiques fournis séparément
-- 2 : la partie FCA manager, avec des vues à créer dans FCA Manager et fournies séparément
-- 3 : la partie OSE du connecteur qui est fournie ci-dessous et qui repose sur les deux parties précédentes.


-- Création des sources de données (à adapter à vos besoins)
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'Harpege','Harpège','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'Apogee','Apogée','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'Calcul','Calculée','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'SIFAC','SIFAC','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'FCAManager','FCA Manager','1');



-----------------------------------------------------
-- Pour la partie RH et divers avec HARPEGE
-----------------------------------------------------

-- Vues matérialisées diverses
-- Ces vues matérialisées seront exploitées ensuite par les vues sources
-- Leur but est de rapatrier des donnée en amont dans OSE pour que les vues sources s'exécutent ensuite plus rapidement

-- Pour les intervenants
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
            WHEN ct.c_type_contrat_trav IN ('GI','PN','ED')           THEN 'ENS_CONTRACT'
            WHEN ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
            WHEN ct.c_type_contrat_trav IN ('MB','MP')                THEN 'MAITRE_LANG'
            WHEN ct.c_type_contrat_trav IN ('PT')                     THEN 'HOSPITALO_UNIV'
            WHEN ct.c_type_contrat_trav IN ('C3','CA','CB','CD','CS','HA','HD','HS','MA','S3','SX','SW','SY','SZ','VA') THEN 'BIATSS'
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
          LEFT JOIN periodes_sp_cnu@harpprod    psc                ON psc.no_dossier_pers = a.no_dossier_pers AND psc.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(psc.d_deb,SYSDATE) AND COALESCE(psc.d_fin,SYSDATE)
          LEFT JOIN periodes_sp_sd_deg@harpprod pss                ON pss.no_dossier_pers = a.no_dossier_pers AND pss.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(pss.d_deb,SYSDATE) AND COALESCE(pss.d_fin,SYSDATE)
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
  individu.c_dept_naissance                                   z_dep_naissance_id,
  individu.c_commune_naissance                                ville_naissance_code_insee,
  COALESCE(commune.libelle_commune,individu.ville_de_naissance) ville_naissance_libelle,
  individu.c_pays_nationnalite                                z_pays_nationalite_id,
  individu_telephone.no_telephone                             tel_pro,
  individu.no_tel_portable                                    tel_mobile,
  CASE -- Si le mail n'est pas renseigné dans Harpège, alors on va le chercher dans notre LDAP
    WHEN INDIVIDU_E_MAIL.NO_E_MAIL IS NULL THEN
      UCBN_LDAP.hid2mail(individu.no_individu) -- (à adapter en fonction de l'établissement)
    ELSE
      INDIVIDU_E_MAIL.NO_E_MAIL
  END                                                         email,
  i.statut                                                    z_statut_id,
  sc.c_structure_n2                                           z_structure_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             source_code,
  code_insee.no_insee                                         numero_insee,
  TO_CHAR(code_insee.cle_insee)                               numero_insee_cle,
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
  LEFT JOIN MV_UNICAEN_STRUCTURE_CODES  sc                 ON sc.c_structure                 = pbs_divers__cicg.c_structure_globale@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) )
  LEFT JOIN commune@harpprod            commune            ON individu.c_commune_naissance   = commune.c_commune
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail    ON individu_e_mail.no_individu    = i.code
  LEFT JOIN individu_telephone@harpprod individu_telephone ON individu_telephone.no_individu = i.code AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O'
  LEFT JOIN code_insee@harpprod         code_insee         ON code_insee.no_dossier_pers     = i.code
  LEFT JOIN                             comptes            ON comptes.no_individu            = i.code AND comptes.rank_compte = comptes.nombre_comptes;



-- AFFECTATIONS
CREATE MATERIALIZED VIEW MV_AFFECTATION AS
WITH tmp AS (

  SELECT
    i.nom_usuel || ' ' || INITCAP(i.prenom)         display_name,
    UCBN_LDAP.HID2MAIL(i.no_individu)               email,
    'ldap'                                          password,
    1                                               state,
    UCBN_LDAP.HID2ALIAS(i.no_individu)              username,

    CASE WHEN c_structure = 'UNIV' THEN NULL ELSE c_structure END z_structure_id,
    CASE
      WHEN lc_fonction LIKE '_D30%' OR t.lc_fonction LIKE '_P71%' THEN 'directeur-composante'
      WHEN lc_fonction LIKE '_R00'  OR t.lc_fonction LIKE '_R40%' THEN 'responsable-composante'
      WHEN lc_fonction LIKE '_R00c' OR t.lc_fonction LIKE '_R40%' THEN 'responsable-recherche-labo'
      WHEN c_structure = 'UNIV' AND t.lc_fonction = '_P00' OR t.lc_fonction LIKE '_P10%' OR t.lc_fonction LIKE '_P50%' THEN 'superviseur-etablissement'
      ELSE NULL
    END z_role_id,
    t.c_structure || '_' || t.no_individu || '_' || t.lc_fonction source_code,

    lc_fonction,
    nom_complet, lc_structure, ll_fonction, t.*
  FROM
         ucbn_d2a_respons_struct@harpprod t
    JOIN individu@harpprod                i ON i.no_individu = t.no_individu
  WHERE
    niveau_structure <= 2
    AND SYSDATE BETWEEN t.date_deb_exerc_resp AND NVL(t.date_fin_exerc_resp + 1,SYSDATE)

)
SELECT DISTINCT
  display_name,
  email,
  password,
  state,
  username,
  z_structure_id,
  z_role_id,
  'Harpege' z_source_id,
  MIN( source_code ) source_code
FROM
  tmp
WHERE
  tmp.z_role_id IS NOT NULL
GROUP BY
  display_name,
  email,
  password,
  state,
  username,
  z_structure_id,
  z_role_id;



-- Structures
-- Cette vue matérialisée sert à établir la correspondance entre n'importe qu'elle structure Harpège et sa correspondante de niveau 2 ou 1
-- On "applatit" ainsi les structures pour n'avoir que du niveau 2
CREATE MATERIALIZED VIEW MV_UNICAEN_STRUCTURE_CODES AS
SELECT
  s9.c_structure c_structure,
  COALESCE(s4.c_structure, s5.c_structure, s6.c_structure, s7.c_structure, s8.c_structure, s9.c_structure) c_structure_n2
FROM
  structure@harpprod s9
  LEFT JOIN structure@harpprod s8 ON s8.c_structure = s9.c_structure_pere AND s8.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s7 ON s7.c_structure = s8.c_structure_pere AND s7.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s6 ON s6.c_structure = s7.c_structure_pere AND s6.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s5 ON s5.c_structure = s6.c_structure_pere AND s5.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s4 ON s4.c_structure = s5.c_structure_pere AND s4.c_structure <> 'UNIV';




-- SRC_ADRESSE_INTERVENANT
CREATE OR REPLACE FORCE VIEW SRC_ADRESSE_INTERVENANT AS
WITH harpege_query AS (
  SELECT
    LTRIM(to_char(no_individu,'99999999'))                  z_intervenant_id,
    TRIM(telephone_domicile)                                tel_domicile,
    TRIM(UPPER(habitant_chez))                              mention_complementaire,
    no_voie || CASE bis_ter
      WHEN 'B' THEN ' BIS'
      WHEN 'T' THEN ' TER'
      WHEN 'Q' THEN ' QUATER'
      WHEN 'C' THEN ' QUINQUIES'
      ELSE ''
    END                                                     no_voie,
    UPPER(TRIM(v.l_voie) || ' ' || TRIM(nom_voie))          nom_voie,
    localite                                                localite,
    coalesce( cp_etranger, code_postal )                    code_postal,
    trim(ville)                                             ville,
    pays.c_pays                                             pays_code_insee,
    pays.ll_pays                                            pays_libelle,
    'Harpege'                                               z_source_id,
    to_char(id_adresse_perso)                               source_code
  FROM
              adresse_personnelle@harpprod adresse
    LEFT JOIN pays@harpprod                   pays ON pays.c_pays = adresse.c_pays
    LEFT JOIN voirie@harpprod                    v ON v.c_voie = adresse.c_voie
  WHERE
    adresse.d_creation <= sysdate
    AND tem_adr_pers_princ = 'O' -- on n'importe que les adresses principales
)
SELECT
  i.id                                                      intervenant_id,
  hq.tel_domicile                                           tel_domicile,
  hq.mention_complementaire                                 mention_complementaire,
  hq.no_voie                                                no_voie,
  hq.nom_voie                                               nom_voie,
  hq.localite                                               localite,
  hq.code_postal                                            code_postal,
  hq.ville                                                  ville,
  hq.pays_code_insee                                        pays_code_insee,
  hq.pays_libelle                                           pays_libelle,
  src.id                                                    source_id,
  hq.source_code || '_' || unicaen_import.get_current_annee source_code
FROM
            harpege_query  hq
       JOIN source        src ON src.code = hq.z_source_id
  LEFT JOIN intervenant     i ON i.source_code = hq.z_intervenant_id
                             AND i.annee_id = unicaen_import.get_current_annee;



-- SRC_ADRESSE_STRUCTURE
CREATE OR REPLACE FORCE VIEW SRC_ADRESSE_STRUCTURE AS
WITH harpege_query AS (
  SELECT
    z_structure_id,
    principale,
    telephone,
    no_voie,
    nom_voie,
    localite,
    code_postal,
    ville,
    pays_code_insee,
    pays_libelle,
    'Harpege' z_source_id,
    source_code
  FROM (

    SELECT DISTINCT
      ls.c_structure                                                  z_structure_id,
      CASE ls.tem_local_principal WHEN 'O' THEN 1 ELSE 0 END          principale,
      ls.no_telephone                                                 telephone,
      no_voie_a || CASE bis_ter_a
        WHEN 'B' THEN ' BIS'
        WHEN 'T' THEN ' TER'
        WHEN 'Q' THEN ' QUATER'
        WHEN 'C' THEN ' QUINQUIES'
        ELSE ''
      END                                                             no_voie,
      UPPER(TRIM(V.l_voie) || ' ' || TRIM(nom_voie_a))                nom_voie,
      localite_a                                                      localite,
      COALESCE( cp_etranger_admin, code_postal_a )                    code_postal,
      TRIM(ville_a)                                                   ville,
      pays.c_pays                                                     pays_code_insee,
      pays.ll_pays                                                    pays_libelle,
      to_char(aa.id_adresse_admin) || '_' || ls.c_structure           source_code,
      COUNT(*) OVER(PARTITION BY aa.id_adresse_admin,ls.c_structure)  doublons
    FROM
                adresse_administrat@harpprod    aa
           JOIN local@harpprod                   l ON l.id_adresse_admin = aa.id_adresse_admin
           JOIN localisation_structure@harpprod ls ON ls.c_local = l.c_local
      LEFT JOIN pays@harpprod                 pays ON pays.c_pays = aa.c_pays
      LEFT JOIN voirie@harpprod                  v ON v.c_voie = aa.c_voie
    WHERE
      SYSDATE BETWEEN COALESCE(aa.d_deb_val, SYSDATE) AND COALESCE(aa.d_fin_val, SYSDATE)
    ) tmp1

  WHERE
    doublons = 1 OR principale = 1
)
SELECT
  s.id                structure_id,
  hq.principale       principale,
  hq.telephone        telephone,
  hq.no_voie          no_voie,
  hq.nom_voie         nom_voie,
  hq.localite         localite,
  hq.code_postal      code_postal,
  hq.ville            ville,
  hq.pays_code_insee  pays_code_insee,
  hq.pays_libelle     pays_libelle,
  src.id              source_id,
  hq.source_code      source_code
FROM
       harpege_query hq
  JOIN source       src ON src.code = hq.z_source_id
  JOIN structure      s ON s.source_code = hq.z_structure_id;



-- SRC_AFFECTATION
CREATE OR REPLACE FORCE VIEW SRC_AFFECTATION AS
SELECT
  s.id          structure_id,
  u.id          utilisateur_id,
  r.id          role_id,
  src.id        source_id,
  a.source_code source_code
FROM
            mv_affectation a
       JOIN source       src ON src.code = a.z_source_id
  LEFT JOIN utilisateur    u ON u.username = a.username
  LEFT JOIN structure      s ON s.source_code = a.z_structure_id
  LEFT JOIN role           r ON r.code = a.z_role_id
WHERE
  s.id IS NULL -- rôle global
  OR (
    (
      EXISTS (SELECT * FROM element_pedagogique ep WHERE ep.structure_id = s.id) -- soit une resp. dans une composante d'enseignement
      OR a.z_role_id IN ('responsable-recherche-labo')                           -- soit un responsable de labo
    )
  );



-- SRC_AFFECTATION_RECHERCHE
CREATE OR REPLACE FORCE VIEW SRC_AFFECTATION_RECHERCHE AS
WITH harpege_query AS (
  SELECT
    to_char(ar.no_dossier_pers)  z_intervenant_id,
    ar.c_structure               z_structure_id,
    'Harpege'                    z_source_id,
    to_char(ar.no_seq_affe_rech) source_code
  FROM
    affectation_recherche@harpprod ar
  WHERE
    SYSDATE BETWEEN ar.d_deb_affe_rech AND COALESCE(ar.d_fin_affe_rech + 1,SYSDATE)
)
SELECT
  i.id                                                      intervenant_id,
  s.id                                                      structure_id,
  src.id                                                    source_id,
  hq.source_code || '_' || unicaen_import.get_current_annee source_code
FROM
            harpege_query              hq
       JOIN source                    src ON src.code = 'Harpege'
  LEFT JOIN intervenant                 i ON i.source_code = hq.z_intervenant_id
                                         AND i.annee_id = unicaen_import.get_current_annee
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = hq.z_structure_id
  LEFT JOIN structure                   s ON s.source_code = sc.c_structure_n2;



-- SRC_CORPS
CREATE OR REPLACE FORCE VIEW SRC_CORPS AS
WITH harpege_query AS (
  SELECT
    c.ll_corps  libelle_long,
    c.lc_corps  libelle_court,
    'Harpege'   z_source_id,
    c.c_corps   source_code
  FROM
    corps@harpprod c
  WHERE
    SYSDATE BETWEEN COALESCE(c.d_ouverture_corps,SYSDATE) AND COALESCE(c.d_fermeture_corps+1,SYSDATE)
)
SELECT
  hq.libelle_long  libelle_long,
  hq.libelle_court libelle_court,
  s.id             source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source         s ON s.code = hq.z_source_id;



-- SRC_DEPARTEMENT
CREATE OR REPLACE FORCE VIEW SRC_DEPARTEMENT AS
WITH harpege_query AS (
  SELECT
    c_departement  code,
    ll_departement libelle_long,
    lc_departement libelle_court,
    'Harpege'      z_source_id,
    c_departement  source_code
  FROM
    departement@harpprod d
)
SELECT
  hq.code          code,
  hq.libelle_long  libelle_long,
  hq.libelle_court libelle_court,
  s.id             source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source         s ON s.code = hq.z_source_id;



-- SRC_GRADE
CREATE OR REPLACE FORCE VIEW SRC_GRADE AS
WITH harpege_query AS (
  SELECT
    g.ll_grade  libelle_long,
    g.lc_grade  libelle_court,
    'Harpege'   z_source_id,
    g.c_grade   source_code,
    g.echelle   echelle,
    g.c_corps   z_corps_id
  FROM
    grade@harpprod g
  WHERE
    SYSDATE BETWEEN COALESCE(g.d_ouverture,SYSDATE) AND COALESCE(g.d_fermeture+1,SYSDATE)
)
SELECT
  hq.libelle_long   libelle_long,
  hq.libelle_court  libelle_court,
  s.id              source_id,
  hq.source_code    source_code,
  hq.echelle        echelle,
  c.id              corps_id
FROM
       harpege_query hq
  JOIN source         s ON s.code        = hq.z_source_id
  JOIN corps          c ON c.source_code = hq.z_corps_id;



-- SRC_INTERVENANT
-- Liste de tous les intervenants pouvant potentiellement saisir des services dans OSE
-- La table "chercheur" est parcourus car chez nous les comptes d'accès au système d'information sont listés dans cette table.
-- Nous retrouvons donc ici tous les comptes d'accès au système d'information valides hormis des comptes invités pour usages spécifiques
-- car tout le monde peut potentiellement déclarer des services.
-- Dans cette vue, on synchronise toutes les données des intervenants de l'année en cours, et la plupart des onnées des intervenants de l'année prédédente
CREATE OR REPLACE FORCE VIEW SRC_INTERVENANT AS
WITH srci as (
SELECT
  i.code,
  c.id civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  COALESCE(i.date_naissance,TO_DATE('2099-01-01','YYYY-MM-DD')) date_naissance,
  pnaiss.id pays_naissance_id,
  dep.id dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  pnat.id pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  si.id statut_id, si.source_code statut_code,
  s.id structure_id,
  src.id source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  g.id grade_id,
  NVL( d.id, d99.id ) discipline_id,
  i.critere_recherche,
  COALESCE (si.ordre,99999) ordre,
  MIN(COALESCE (si.ordre,99999)) OVER (PARTITION BY i.source_code) min_ordre
FROM
            mv_intervenant i
       JOIN source        src ON src.code = 'Harpege'
  LEFT JOIN civilite        c ON c.libelle_court = i.z_civilite_id
  LEFT JOIN structure       s ON s.source_code = i.z_structure_id
  LEFT JOIN statut_intervenant si ON si.source_code = i.z_statut_id
  LEFT JOIN grade           g ON g.source_code = i.z_grade_id
  LEFT JOIN pays       pnaiss ON pnaiss.source_code = i.z_pays_naissance_id
  LEFT JOIN pays         pnat ON pnat.source_code = i.z_pays_nationalite_id
  LEFT JOIN departement   dep ON dep.source_code = i.z_dep_naissance_id
  LEFT JOIN discipline d99 ON d99.source_code = '99'
  LEFT JOIN discipline d ON
    d.histo_destruction IS NULL
    AND 1 = CASE WHEN -- si rien n'ac été défini

      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, i.z_discipline_id_spe_cnu, i.z_discipline_id_dis2deg ) IS NULL
      AND d.source_code = '00'

    THEN 1 WHEN -- si une CNU ou une spécialité a été définie...

      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, z_discipline_id_spe_cnu ) IS NOT NULL

    THEN CASE WHEN -- alors on teste par les sections CNU et spécialités

      (
           ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'') || ',%'
        OR ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'00') || ',%'
      )
      AND ',' || NVL(d.CODES_CORRESP_3,'000') || ',' LIKE  '%,' || NVL(CASE WHEN d.CODES_CORRESP_3 IS NOT NULL THEN z_discipline_id_spe_cnu ELSE NULL END,'000') || ',%'

    THEN 1 ELSE 0 END ELSE CASE WHEN -- sinon on teste par les disciplines du 2nd degré

      i.z_discipline_id_dis2deg IS NOT NULL
      AND ',' || NVL(d.CODES_CORRESP_4,'') || ',' LIKE  '%,' || i.z_discipline_id_dis2deg || ',%'

    THEN 1 ELSE 0 END END -- fin du test
)
SELECT
  i.code code, lpad(i.code, 8, '0') utilisateur_code,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(
    isai.statut_id,
    CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END
  ) statut_id,
  i. structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee
  LEFT JOIN intervenant_saisie  isai ON isai.intervenant_id = i2.id
  LEFT JOIN dossier               d  ON d.intervenant_id = i2.id AND d.histo_destruction IS NULL
WHERE
  i.ordre = i.min_ordre

UNION ALL

SELECT
  i.code code, lpad(i.code, 8, '0') utilisateur_code,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(i2.statut_id,i.statut_id) statut_id,
  COALESCE(i2.structure_id,i.structure_id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee - 1 annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee - 1
WHERE
  i.ordre = i.min_ordre;



-- SRC_PAYS
CREATE OR REPLACE FORCE VIEW SRC_PAYS AS
SELECT
  ll_pays                                                 libelle_long,
  coalesce(lc_pays,ll_pays)                               libelle_court,
  coalesce(d_deb_val, TO_DATE('1900/01/01','YYYY/MM/DD')) validite_debut,
  d_fin_val                                               validite_fin,
  decode(tem_ue, 'O', 1, 0)                               temoin_ue,
  s.id                                                    source_id,
  c_pays                                                  source_code
FROM
  pays@harpprod p
  JOIN source s ON s.code = 'Harpege';



-- SRC_STRUCTURE
CREATE OR REPLACE FORCE VIEW SRC_STRUCTURE AS
WITH harpege_query AS (
  SELECT
    str.c_structure  code,
    str.lc_structure libelle_court,
    str.ll_structure libelle_long,
    'Harpege'        z_source_id,
    str.c_structure  source_code
  FROM
    structure@harpprod str
  WHERE
    SYSDATE BETWEEN str.date_ouverture AND COALESCE( str.date_fermeture, SYSDATE )
    AND (str.c_structure = 'UNIV' OR str.c_structure_pere = 'UNIV') -- UNIV = structure "Université" de niveau 1
)
SELECT
  hq.code          code,
  hq.libelle_court libelle_court,
  hq.libelle_long  libelle_long,
  src.id           source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source       src ON src.code = hq.z_source_id;





-----------------------------------------------------
-- Pour l'offre de formation avec APOGEE et FCA MANAGER
-----------------------------------------------------

-- SRC_CHEMIN_PEDAGOGIQUE
CREATE OR REPLACE FORCE VIEW SRC_CHEMIN_PEDAGOGIQUE AS
SELECT
  elp.id                                                               element_pedagogique_id,
  etp.id                                                               etape_id,
  ROW_NUMBER() OVER (PARTITION BY etp.id, aq.annee_id ORDER BY ROWNUM) ordre,
  s.id                                                                 source_id,
  aq.source_code || '_' || aq.annee_id                                 source_code
FROM
            ose_chemin_pedagogique@apoprod aq
       JOIN source                          s ON s.code = 'Apogee'
  LEFT JOIN element_pedagogique           elp ON elp.source_code = aq.z_element_pedagogique_id
                                             AND elp.annee_id = TO_NUMBER(aq.annee_id)
  LEFT JOIN etape                         etp ON etp.source_code = aq.z_etape_id
                                             AND etp.annee_id = TO_NUMBER(aq.annee_id)

UNION

SELECT
  elp.id                                                               element_pedagogique_id,
  etp.id                                                               etape_id,
  ROW_NUMBER() OVER (PARTITION BY etp.id, fq.annee_id ORDER BY ROWNUM) ordre,
  s.id                                                                 source_id,
  fq.source_code || '_' || fq.annee_id                                 source_code
FROM
            fca.ose_chemin_pedagogique@fcaprod fq
       JOIN source                              s ON s.code = 'FCAManager'
  LEFT JOIN element_pedagogique               elp ON elp.source_code = fq.z_element_pedagogique_id
                                                 AND elp.annee_id = TO_NUMBER(fq.annee_id)
  LEFT JOIN etape                             etp ON etp.source_code = fq.z_etape_id
                                                 AND etp.annee_id = TO_NUMBER(fq.annee_id);



-- SRC_EFFECTIFS
CREATE OR REPLACE FORCE VIEW "SRC_EFFECTIFS" ("ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "FI", "FC", "FA", "SOURCE_ID", "SOURCE_CODE") AS
SELECT
  ep.id                                           element_pedagogique_id,
  to_number(e.annee_id)                           annee_id,
  e.effectif_fi                                   fi,
  e.effectif_fc                                   fc,
  e.effectif_fa                                   fa,
  s.id                                            source_id,
  e.annee_id || '-' || e.z_element_pedagogique_id source_code
FROM
       ose_element_effectifs@apoprod e
  JOIN source                        s ON s.code = 'Apogee'
  LEFT JOIN element_pedagogique     ep ON ep.source_code = e.z_element_pedagogique_id
                                      AND ep.annee_id = to_number(e.annee_id);



-- SRC_ELEMENT_PEDAGOGIQUE
CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_PEDAGOGIQUE AS
WITH apogee_query AS (
  SELECT
    ep.source_code code,
    ep.libelle,
    ep.z_etape_id,
    ep.z_structure_id,
    ep.z_periode_id,
    CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi,
    ep.fc,
    ep.fa,
    ep.taux_foad,
    'Apogee' z_source_id,
    ep.source_code,
    TO_NUMBER(ep.annee_id) annee_id,
    ep.z_discipline_id
  FROM
    ose_element_pedagogique@apoprod ep
)
SELECT
  aq.code,
  aq.libelle,
  etp.id etape_id,
  str.id structure_id,
  per.id periode_id,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fi( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fi,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fc( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fc( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fc,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fa( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fa( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fa,
  aq.taux_foad,
  aq.fc,
  aq.fi,
  aq.fa,
  s.id source_id,
  aq.source_code,
  aq.annee_id,
  NVL( d.id, d99.id ) discipline_id
FROM
            apogee_query aq
       JOIN source                      s ON s.code                     = aq.z_source_id
  LEFT JOIN etape                     etp ON etp.source_code            = aq.z_etape_id
                                         AND etp.annee_id               = aq.annee_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure             = aq.z_structure_id
  LEFT JOIN structure                 str ON str.source_code            = sc.c_structure_n2
  LEFT JOIN periode                   per ON per.libelle_court          = aq.z_periode_id
  LEFT JOIN element_pedagogique        ep ON ep.source_code             = aq.source_code
                                         AND ep.annee_id                = aq.annee_id
  LEFT JOIN element_taux_regimes      etr ON etr.element_pedagogique_id = ep.id
                                         AND etr.histo_destruction      IS NULL
  LEFT JOIN discipline                d99 ON d99.source_code            = '99'
  LEFT JOIN discipline                  d ON ',' || d.CODES_CORRESP_1 || ',' LIKE '%,' || NVL(aq.z_discipline_id,'00') || ',%'
                                         AND d.histo_destruction        IS NULL

UNION

SELECT
  ep.code,
  ep.libelle,
  etp.id etape_id,
  str.id structure_id,
  per.id periode_id,
  ep.taux_fi taux_fi,
  ep.taux_fc taux_fc,
  ep.taux_fa taux_fa,
  ep.taux_foad,
  ep.fc,
  ep.fi,
  ep.fa,
  s.id,
  ep.source_code,
  TO_NUMBER(ep.annee_id) annee_id,
  d99.id discipline_id
FROM
            FCA.OSE_element_pedagogique@fcaprod ep
       JOIN source                               s ON s.code            = 'FCAManager'
  LEFT JOIN etape                              etp ON etp.source_code   = ep.z_etape_id
                                                  AND etp.annee_id      = ep.annee_id
  LEFT JOIN MV_UNICAEN_STRUCTURE_CODES          sc ON sc.c_structure    = ep.z_structure_id
  LEFT JOIN structure                          str ON str.source_code   = sc.c_structure_n2
  LEFT JOIN periode                            per ON per.libelle_court = ep.z_periode_id
  LEFT JOIN discipline                         d99 ON d99.source_code   = '99';



-- SRC_ELEMENT_TAUX_REGIMES
CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_TAUX_REGIMES AS
WITH apogee_query AS (
  SELECT
    e.z_element_pedagogique_id  z_element_pedagogique_id,
    to_number(e.annee_id) + 1   annee_id,
    e.effectif_fi               effectif_fi,
    e.effectif_fc               effectif_fc,
    e.effectif_fa               effectif_fa,
    'Apogee'                    z_source_id,
    TO_NUMBER(e.annee_id) + 1 || '-' || e.z_element_pedagogique_id source_code
  FROM
    ose_element_effectifs@apoprod e
  WHERE
    (e.effectif_fi + e.effectif_fc + e.effectif_fa) > 0
)
SELECT
  ep.id           element_pedagogique_id,
  aq.annee_id     annee_id,
  OSE_DIVERS.CALCUL_TAUX_FI( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fa,
  s.id           source_id,
  aq.source_code source_code
FROM
       apogee_query aq
  JOIN source s ON s.code = aq.z_source_id
  JOIN ELEMENT_PEDAGOGIQUE ep ON ep.source_code = aq.z_element_pedagogique_id AND ep.annee_id = aq.annee_id
WHERE
  NOT EXISTS( -- on évite de remonter des données issus d'autres sources pour le pas risquer de les écraser!!
    SELECT * FROM element_taux_regimes aq_tbl WHERE
      aq_tbl.element_pedagogique_id = ep.id
      AND aq_tbl.source_id <> s.id
  );



-- SRC_ETABLISSEMENT
CREATE OR REPLACE FORCE VIEW SRC_ETABLISSEMENT AS
WITH apogee_query AS (
  SELECT
    e.lib_off_etb libelle,
    e.lic_etb     localisation,
    e.cod_dep     departement,
    'Apogee'      z_source_id,
    e.cod_etb     source_code
  FROM
    etablissement@apoprod e
)
SELECT
  aq.libelle      libelle,
  aq.localisation localisation,
  aq.departement  departement,
  s.id            source_id,
  aq.source_code  source_code
FROM
       apogee_query aq
  JOIN source        s ON s.code = aq.z_source_id;



-- SRC_ETAPE
CREATE OR REPLACE FORCE VIEW SRC_ETAPE AS
SELECT
  e.cod_etp || '_' || e.cod_vrs_vet   code,
  e.libelle                           libelle,
  to_number(e.annee_id)               annee_id,
  tf.id                               type_formation_id,
  to_number(e.niveau)                 niveau,
  e.specifique_echanges               specifique_echanges,
  s.id                                structure_id,
  src.id                              source_id,
  e.source_code                       source_code,
  df.id                               domaine_fonctionnel_id
FROM
            ose_etape@apoprod           e
       JOIN source                    src ON src.code       = 'Apogee'
  LEFT JOIN type_formation             tf ON tf.source_code = e.z_type_formation_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = e.z_structure_id
  LEFT JOIN structure                   s ON s.source_code  = sc.c_structure_n2
  LEFT JOIN domaine_fonctionnel        df ON df.source_code = e.domaine_fonctionnel

UNION

SELECT
  e.code                              code,
  e.libelle                           libelle,
  to_number(e.annee_id )              annee_id,
  tf.id                               type_formation_id,
  to_number(e.niveau)                 niveau,
  0                                   specifique_echanges,
  s.id                                structure_id,
  src.id                              source_id,
  e.source_code                       source_code,
  df.id                               domaine_fonctionnel_id
FROM
            fca.ose_etape@fcaprod       e
       JOIN source                    src ON src.code       = 'FCAManager'
  LEFT JOIN type_formation             tf ON tf.source_code = e.z_type_formation_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = e.z_structure_id
  LEFT JOIN structure                   s ON s.source_code  = sc.c_structure_n2
  LEFT JOIN domaine_fonctionnel        df ON df.source_code = e.z_domaine_fonctionnel_id;



-- SRC_GROUPE_TYPE_FORMATION
CREATE OR REPLACE FORCE VIEW SRC_GROUPE_TYPE_FORMATION AS
SELECT
  gtf.libelle_court     libelle_court,
  gtf.libelle_long      libelle_long,
  gtf.ordre             ordre,
  gtf.pertinence_niveau pertinence_niveau,
  s.id                  source_id,
  gtf.source_code       source_code
FROM
  ose_groupe_type_formation@apoprod gtf
  JOIN source s ON s.code = 'Apogee';



-- SRC_LIEN
CREATE OR REPLACE FORCE VIEW SRC_LIEN AS
SELECT
  nsup.id         noeud_sup_id,
  ninf.id         noeud_inf_id,
  str.id          structure_id,
  s.id            source_id,
  l.z_source_code source_code
FROM
            ose_lien@apoprod            l
       JOIN source                      s ON s.code = 'Apogee'
       JOIN noeud                    nsup ON nsup.source_code = l.noeud_sup_id
                                         AND nsup.annee_id = TO_NUMBER(l.annee_id)
       JOIN noeud                    ninf ON ninf.source_code = l.noeud_inf_id
                                         AND ninf.annee_id = TO_NUMBER(l.annee_id)
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = l.z_structure_id
  LEFT JOIN structure                 str ON str.source_code = sc.c_structure_n2;



-- SRC_NOEUD
CREATE OR REPLACE FORCE VIEW SRC_NOEUD AS
SELECT
  n.code                code,
  n.libelle_court       libelle,
  n.liste               liste,
  TO_NUMBER(n.annee_id) annee_id,
  e.id                  etape_id,
  ep.id                 element_pedagogique_id,
  str.id                structure_id,
  s.id                  source_id,
  n.z_source_code       source_code
FROM
            ose_noeud@apoprod           n
       JOIN source                      s ON s.code          = 'Apogee'
  LEFT JOIN etape                       e ON e.source_code   = n.z_etape_id
                                         AND e.annee_id      = n.annee_id
  LEFT JOIN element_pedagogique        ep ON ep.source_code  = n.z_element_pedagogique_id
                                         AND ep.annee_id     = n.annee_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure  = n.z_structure_id
  LEFT JOIN structure                 str ON str.source_code = sc.c_structure_n2;



-- SRC_SCENARIO_LIEN
CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_LIEN AS
SELECT
  s.id                            scenario_id,
  li.id                           lien_id,
  1                               actif,
  1                               poids,
  l.choix_minimum                 choix_minimum,
  l.choix_maximum                 choix_maximum,
  src.id                          source_id,
  l.z_source_code || '_s' || s.id source_code
FROM
            ose_lien@apoprod l
       JOIN source         src ON src.code             = 'Apogee'
       JOIN scenario         s ON s.histo_destruction  IS NULL
       JOIN lien            li ON li.source_code       = l.z_source_code
  LEFT JOIN scenario_lien   sl ON sl.lien_id           = li.id
                              AND sl.scenario_id       = s.id
                              AND sl.histo_destruction IS NULL
                              AND sl.source_id         <> src.id
WHERE
  (l.choix_minimum IS NOT NULL OR l.choix_maximum IS NOT NULL)
  AND sl.id IS NULL;



-- SRC_TYPE_FORMATION
CREATE OR REPLACE FORCE VIEW SRC_TYPE_FORMATION AS
SELECT
  tf.libelle_long   libelle_long,
  tf.libelle_court  libelle_court,
  gtf.id            groupe_id,
  s.id              source_id,
  tf.source_code    source_code
FROM
            ose_type_formation@apoprod tf
       JOIN source                      s ON s.code = 'Apogee'
  LEFT JOIN groupe_type_formation     gtf ON gtf.source_code = tf.z_groupe_id;



-- SRC_TYPE_INTERVENTION_EP
CREATE OR REPLACE FORCE VIEW SRC_TYPE_INTERVENTION_EP AS
WITH t AS (
SELECT
  ti.id                                                   type_intervention_id,
  ti.code                                                 type_intervention_code,
  ep.id                                                   element_pedagogique_id,
  ep.annee_id                                             annee_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id  source_code,
  COALESCE(vhe.heures,0)                                  heures,
  SUM(COALESCE(vhe.heures,0)) OVER (PARTITION BY ep.id)   total_heures
FROM
            element_pedagogique              ep

       JOIN type_intervention                ti ON ep.annee_id BETWEEN COALESCE(ti.annee_debut_id,ep.annee_id) AND COALESCE(ti.annee_fin_id, ep.annee_id)
                                               AND ti.histo_destruction IS NULL

  LEFT JOIN type_intervention_structure     tis ON tis.type_intervention_id = ti.id
                                               AND tis.structure_id = ep.structure_id
                                               AND ep.annee_id BETWEEN COALESCE(tis.annee_debut_id,ep.annee_id) AND COALESCE(tis.annee_fin_id, ep.annee_id)
                                               AND tis.histo_destruction IS NULL

  LEFT JOIN volume_horaire_ens              vhe ON vhe.element_pedagogique_id = ep.id
                                               AND vhe.type_intervention_id = COALESCE(ti.type_intervention_maquette_id, ti.id)
                                               AND vhe.histo_destruction IS NULL
WHERE
  ep.histo_destruction IS NULL
  AND COALESCE( tis.visible, ti.visible ) = 1
  AND (ti.regle_foad = 0 OR ep.taux_foad > 0)
  AND (ti.regle_fc = 0 OR ep.taux_fc > 0)
)
SELECT
  t.type_intervention_id    type_intervention_id,
  t.element_pedagogique_id  element_pedagogique_id,
  t.source_code             source_code,
  src.id                    source_id
FROM
  t
  JOIN source src ON src.code = 'Calcul'
WHERE
  heures > 0  --Soit il y a des heures de prévues
  OR total_heures = 0 -- soit on autorise tout
  OR annee_id < 2017; -- règle ne s'appliquant pas avant!



-- SRC_TYPE_MODULATEUR_EP
CREATE OR REPLACE FORCE VIEW SRC_TYPE_MODULATEUR_EP AS
SELECT
  tm.id                               type_modulateur_id,
  ep.id                               element_pedagogique_id,
  src.id                              source_id,
  tm.code || '_' || ep.source_code || '_' || ep.annee_id  source_code
FROM
  element_pedagogique             ep
  JOIN type_modulateur            tm ON tm.histo_destruction IS NULL
  JOIN structure                   s ON s.id = ep.structure_id
  JOIN type_modulateur_structure tms ON tms.type_modulateur_id = tm.id
                                    AND tms.structure_id = s.id
                                    AND tms.histo_destruction IS NULL
                                    AND ep.annee_id BETWEEN COALESCE( tms.annee_debut_id, 1 ) AND COALESCE( tms.annee_fin_id, 999999 )
  JOIN source                    src ON src.code = 'Calcul'
WHERE
  ep.histo_destruction IS NULL
  AND ep.taux_fc > 0;



-- SRC_VOLUME_HORAIRE_ENS
CREATE OR REPLACE FORCE VIEW SRC_VOLUME_HORAIRE_ENS AS
WITH apogee_fca_query AS (
  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    CASE vhe.z_type_intervention_id
      WHEN 'MEMOIR' THEN 'Mémoire'
      WHEN 'STAGE'  THEN 'Stage'
      WHEN 'PROJET' THEN 'Projet'
    ELSE
      vhe.z_type_intervention_id
    END                                     z_type_intervention_id,
    vhe.heures                              heures,
    vhe.groupes                             groupes,
    'Apogee'                                z_source_id,
    vhe.annee_id || '_' || vhe.source_code  source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM
    ose_volume_horaire_ens@apoprod vhe

  UNION

  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    vhe.z_type_intervention_id              z_type_intervention_id,
    vhe.heures                              heures,
    1                                       groupes,
    'FCAManager'                            z_source_id,
    TO_CHAR(vhe.source_code)                source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM
    fca.ose_volume_horaire_ens@fcaprod vhe
)
SELECT
  ep.id           element_pedagogique_id,
  ti.id           type_intervention_id,
  afq.heures      heures,
  afq.groupes     groupes,
  s.id            source_id,
  afq.source_code source_code
FROM
            apogee_fca_query   afq
       JOIN source               s ON s.code         = afq.z_source_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = afq.z_element_pedagogique_id
                                  AND ep.annee_id    = afq.annee_id
  LEFT JOIN type_intervention   ti ON ti.code        = afq.z_type_intervention_id;



-----------------------------------------------------
-- Pour la comptabilité analytique avec SIFAC
-----------------------------------------------------

-- Table de correspondance entre nos codes SIFAC et les codes de structure Harpège
CREATE TABLE UNICAEN_CORRESP_STRUCTURE_CC(
  "ID" NUMBER(*,0) NOT NULL ENABLE,
	"CODE_SIFAC" VARCHAR2(15 CHAR) NOT NULL ENABLE,
	"CODE_HARPEGE" VARCHAR2(250 CHAR) NOT NULL ENABLE,
	 CONSTRAINT "UNICAEN_CORRESP_STR_CC_PK" PRIMARY KEY ("ID")
);
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('1','901','U01');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('2','902','U02');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('3','903','U03');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('4','904','U04');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('5','907','U07');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('6','908','U08');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('7','909','U09');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('8','910','U10');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('9','911','I11');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('10','912','I12');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('11','913','I13');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('12','914','U14');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('13','917','M17');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('14','920','U36');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('15','924','U24');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('16','925','U25');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('17','926','U26');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('18','945','C45');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('19','950','UNIV');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('20','953','C53');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('21','961','C61');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('22','971','U55');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('23','980','E01');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('24','011','11');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('25','012','12');



-- SRC_CENTRE_COUT
-- les EOTP (deuxième partie de requête) sont considérés par OSE comme des centres de coûts dépendant d'un autre centre de coûts (hiérarchie)
-- les règle de gestion (nomenclature en fonction  de l'analyse du code du centre de coûts) sont propres à l'université de CAEN et devront être adaptées
CREATE OR REPLACE FORCE VIEW SRC_CENTRE_COUT AS
WITH sifac_query AS (
  SELECT DISTINCT
    TRIM(B.ktext) libelle,
    CASE
      WHEN a.kostl like '%A' THEN 'accueil' -- Activité (au sens compta analytique) ne devant pas permettre la saisie de référentiel
      WHEN a.kostl like '%B' THEN 'enseignement'
      WHEN a.kostl like '%M' THEN 'pilotage'
    END z_activite_id,
    CASE
      WHEN LENGTH(a.kostl) = 5 THEN 'paie-etat'
      WHEN LENGTH(a.kostl) > 5 THEN 'ressources-propres'
    END z_type_ressource_id,
    substr( A.kostl, 2, 3 ) unite_budgetaire,
    NULL z_parent_id,
    'SIFAC' z_source_id,
    A.kostl source_code

  FROM
    sapsr3.csks@sifacp A,
    sapsr3.cskt@sifacp B
  WHERE
      A.kostl=B.kostl(+)
      and A.kokrs=B.kokrs(+)
      and B.mandt(+)='500'
      and B.spras(+)='F'
      and A.kokrs='1010'
      and A.bkzkp !='X'
      and a.kostl LIKE 'P%' AND (a.kostl like '%A' OR a.kostl like '%B' OR a.kostl like '%M')
      AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD') AND to_date( NVL(A.datbi,'99991231'), 'YYYYMMDD')

  UNION

  SELECT
    TRIM(A.post1) libelle,
    CASE
      WHEN a.fkstl like '%A' THEN 'accueil'
      WHEN a.fkstl like '%B' THEN 'enseignement'
      WHEN a.fkstl like '%M' THEN 'pilotage'
    END z_activite_id,
    CASE
      WHEN LENGTH(a.fkstl) = 5 THEN 'paie-etat'
      WHEN LENGTH(a.fkstl) > 5 THEN 'ressources-propres'
    END z_type_ressource_id,
    substr( A.fkstl, 2, 3 ) unite_budgetaire,
    A.fkstl z_parent_id,
    'SIFAC' z_source_id,
    A.posid source_code
  FROM
    sapsr3.prps@sifacp A,
    sapsr3.prte@sifacp B
  WHERE
    A.pspnr=B.posnr(+)
    AND A.pkokr='1010'
    AND B.mandt(+)='500'
    AND a.fkstl LIKE 'P%' AND (a.fkstl like '%A' OR a.fkstl like '%B' OR a.fkstl like '%M')
    AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD') AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')

  UNION

  SELECT
    TRIM(A.post1) libelle,
    'enseignement' z_activite_id,
    'ressources-propres' z_type_ressource_id,
    substr( A.fkstl, 2, 3 ) unite_budgetaire,
    null z_parent_id,
    'SIFAC' z_source_id,
    A.posid source_code
  FROM
    sapsr3.prps@sifacp A,
    sapsr3.prte@sifacp B
  WHERE
    A.pspnr=B.posnr(+)
    and A.pkokr='1010'
    and B.mandt(+)='500'
    AND (
      A.posid IN ('P950FCFCR', 'P950FCFFR')
    )
    AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD') AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')
)
SELECT
  code,
  libelle,
  activite_id,
  type_ressource_id,
  unite_budgetaire,
  poids,
  parent_id,
  source_id,
  source_code
FROM
  (
  SELECT
    sq.source_code                                                      code,
    sq.libelle                                                          libelle,
    a.id                                                                activite_id,
    tr.id                                                               type_ressource_id,
    sq.unite_budgetaire                                                 unite_budgetaire,
    ROW_NUMBER() OVER (PARTITION BY sq.source_code ORDER BY sq.libelle) poids,
    cc.id                                                               parent_id,
    src.id                                                              source_id,
    sq.source_code                                                      source_code
  FROM
              sifac_query    sq
         JOIN source        src ON src.code       = sq.z_source_id
    LEFT JOIN cc_activite     a ON a.code         = sq.z_activite_id
    LEFT JOIN type_ressource tr ON tr.code        = sq.z_type_ressource_id
    LEFT JOIN centre_cout    cc ON cc.source_code = sq.z_parent_id
  WHERE
    sq.z_activite_id IS NOT NULL
) cc
WHERE
  poids = 1;



-- SRC_CENTRE_COUT_STRUCTURE
CREATE OR REPLACE FORCE VIEW SRC_CENTRE_COUT_STRUCTURE AS
WITH cc AS (

  SELECT
    cc.id id,
    cc.source_code source_code,
    cc.source_code ori_source_code
  FROM
    centre_cout cc
    LEFT JOIN centre_cout pcc ON pcc.id = cc.parent_id
  WHERE
    pcc.id IS NULL

  UNION ALL

  SELECT
    cc.id id,
    pcc.source_code source_code,
    cc.source_code ori_source_code
  FROM
    centre_cout cc
    JOIN centre_cout pcc ON pcc.id = cc.parent_id

)
SELECT
  cc.id centre_cout_id,
  s.id structure_id,
  (SELECT id FROM source WHERE code='Calcul') source_id,
  cc.ori_source_code || '_' || s.source_code source_code
FROM
  unicaen_corresp_structure_cc ucs
  JOIN cc ON substr( cc.source_code, 2, 3 ) = ucs.code_sifac
  JOIN structure s ON s.source_code = CASE
    WHEN cc.source_code = 'P950DRRA' THEN 'ECODOCT'
    WHEN cc.source_code = 'P950FCFCR' THEN 'drh-formation'
    WHEN cc.source_code = 'P950FCFFR' THEN 'drh-formation'
    ELSE ucs.code_harpege
  END;



-- SRC_DOMAINE_FONCTIONNEL
-- (récupération des libellés uniquement, la liste des codes est à adapter)
CREATE OR REPLACE FORCE VIEW SRC_DOMAINE_FONCTIONNEL AS
WITH sifac_query AS (
  SELECT
    B.fkbtx libelle,
    'SIFAC' z_source_id,
    A.fkber source_code
  FROM
    sapsr3.tfkb@sifacp A,
    sapsr3.tfkbt@sifacp B
  WHERE
    A.mandt=B.mandt
    AND A.fkber=B.fkber
    AND B.SPRAS='F'
    AND A.mandt='500'
    AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD') AND to_date( NVL(A.datbis,'99991231'), 'YYYYMMDD')
    AND a.fkber IN ('D101', 'D102', 'D103', 'D1053', 'D106', 'D107', 'D108', 'D109', 'D110', 'D111', 'D112', 'D1132', 'D1153')
)
SELECT
  sq.libelle     libelle,
  s.id           source_id,
  sq.source_code source_code
FROM
       sifac_query sq
  JOIN source       s ON s.code = sq.z_source_id;
