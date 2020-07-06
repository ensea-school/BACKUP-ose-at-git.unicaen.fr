
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
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'Apogee','Apogée','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'Calcul','Calculée','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'SIFAC','SIFAC','1');
Insert into SOURCE (ID,CODE,LIBELLE,IMPORTABLE) values (source_id_seq.nextval,'FCAManager','FCA Manager','1');



-----------------------------------------------------
-- Pour la partie RH et divers avec HARPEGE
-----------------------------------------------------



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



-- Pour pouvoir associer des disciplines aux éléments pédagogiques dans OSE
CREATE TABLE unicaen_element_discipline (
  element_source_code      VARCHAR2(30 CHAR) NOT NULL,
  discipline_source_code   VARCHAR2(30 CHAR) NOT NULL
)
LOGGING;

ALTER TABLE unicaen_element_discipline
  ADD CONSTRAINT unicaen_element_discipline_pk
  PRIMARY KEY ( element_source_code, discipline_source_code );






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
  LEFT JOIN unicaen_element_discipline         ued ON ued.element_source_code = ep.source_code
  LEFT JOIN discipline                         d99 ON d99.source_code   = COALESCE( ued.discipline_source_code,'99');



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
  OR (total_heures = 0 AND annee_id < 2019) -- soit on autorise tout s'il n'y a pas de charges (avant 2019)
  OR annee_id < 2017 -- soit on autorise vraiment tout (avant 2017)



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
      WHEN 'SORTIE' THEN 'Terrain'
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
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('33','911','15');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('34','912','15');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('35','913','15');
Insert into UNICAEN_CORRESP_STRUCTURE_CC (ID,CODE_SIFAC,CODE_HARPEGE) values ('36','015','15');



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
