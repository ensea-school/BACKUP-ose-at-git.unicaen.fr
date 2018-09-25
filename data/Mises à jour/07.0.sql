-- Script de migration de la version 6.3.2 à la 7.0

-- Import ouvert pour les services
ALTER TABLE service ADD (source_id   NUMBER(*,0) );
ALTER TABLE service ADD (source_code VARCHAR2(100 CHAR));

ALTER TRIGGER SERVICE_CK DISABLE;
ALTER TRIGGER INTERVENANT_HORO_SERVICE DISABLE;
ALTER TRIGGER INTERVENANT_HORO_VH DISABLE;
ALTER TRIGGER SERVICE_HISTO_CK DISABLE;
ALTER TRIGGER SERVICE_HISTO_CK_S DISABLE;
ALTER TRIGGER VOLUME_HORAIRE_CK DISABLE;



UPDATE service SET source_id = (SELECT id FROM source WHERE code = 'OSE');
UPDATE service SET source_code = id;

ALTER TABLE service MODIFY ( source_id NOT NULL );
ALTER TABLE service MODIFY ( source_code NOT NULL );
ALTER TABLE service ADD CONSTRAINT service_source_fk FOREIGN KEY ( source_id ) REFERENCES source ( id ) NOT DEFERRABLE;
ALTER TABLE service ADD CONSTRAINT service_source_un UNIQUE ( source_code,histo_destruction );

-- Import possible pour les volumes horaires
ALTER TABLE volume_horaire ADD (source_id   NUMBER(*,0) );
ALTER TABLE volume_horaire ADD (source_code VARCHAR2(100 CHAR));
UPDATE volume_horaire SET source_id = (SELECT id FROM source WHERE code = 'OSE'), source_code = id;

ALTER TRIGGER SERVICE_CK ENABLE;
ALTER TRIGGER INTERVENANT_HORO_SERVICE ENABLE;
ALTER TRIGGER INTERVENANT_HORO_VH ENABLE;
ALTER TRIGGER SERVICE_HISTO_CK ENABLE;
ALTER TRIGGER SERVICE_HISTO_CK_S ENABLE;
ALTER TRIGGER VOLUME_HORAIRE_CK ENABLE;

ALTER TABLE volume_horaire MODIFY ( source_id NOT NULL );
ALTER TABLE volume_horaire MODIFY ( source_code NOT NULL );
ALTER TABLE volume_horaire ADD CONSTRAINT volume_horaire_source_fk FOREIGN KEY ( source_id ) REFERENCES source ( id ) NOT DEFERRABLE;
ALTER TABLE volume_horaire ADD CONSTRAINT volume_horaire_source_un UNIQUE ( source_code,histo_destruction );

-- Import ouvert pour les services référentiels
ALTER TABLE service_referentiel ADD (source_id   NUMBER(*,0) );
ALTER TABLE service_referentiel ADD (source_code VARCHAR2(100 CHAR));

UPDATE service_referentiel SET source_id = (SELECT id FROM source WHERE code = 'OSE');
UPDATE service_referentiel SET source_code = id;

ALTER TABLE service_referentiel MODIFY ( source_id NOT NULL );
ALTER TABLE service_referentiel MODIFY ( source_code NOT NULL );
ALTER TABLE service_referentiel ADD CONSTRAINT service_referentiel_source_fk FOREIGN KEY ( source_id ) REFERENCES source ( id ) NOT DEFERRABLE;
ALTER TABLE service_referentiel ADD CONSTRAINT service_referentiel_source_un UNIQUE ( source_code,histo_destruction );

-- Import possible pour les volumes horaires référentiels
ALTER TABLE volume_horaire_ref ADD (source_id   NUMBER(*,0) );
ALTER TABLE volume_horaire_ref ADD (source_code VARCHAR2(100 CHAR));

ALTER TRIGGER VOLUME_HORAIRE_REF_CK DISABLE;
UPDATE volume_horaire_ref SET source_id = (SELECT id FROM source WHERE code = 'OSE');
UPDATE volume_horaire_ref SET source_code = id;
ALTER TRIGGER VOLUME_HORAIRE_REF_CK ENABLE;

ALTER TABLE volume_horaire_ref MODIFY ( source_id NOT NULL );
ALTER TABLE volume_horaire_ref MODIFY ( source_code NOT NULL );
ALTER TABLE volume_horaire_ref ADD CONSTRAINT volume_horaire_ref_source_fk FOREIGN KEY ( source_id ) REFERENCES source ( id ) NOT DEFERRABLE;
ALTER TABLE volume_horaire_ref ADD CONSTRAINT volume_horaire_ref_source_un UNIQUE ( source_code,histo_destruction );


ALTER TABLE volume_horaire ADD (
  auto_validation   NUMBER(1) DEFAULT 0 NOT NULL
  );

ALTER TABLE volume_horaire_ref ADD (
  auto_validation   NUMBER(1) DEFAULT 0 NOT NULL
  );

ALTER TABLE tbl_validation_enseignement ADD (
  auto_validation   NUMBER(1) DEFAULT 0 NOT NULL
  );

ALTER TABLE tbl_validation_referentiel ADD (
  auto_validation   NUMBER(1) DEFAULT 0 NOT NULL
  );

ALTER TABLE volume_horaire ADD (
  horaire_debut   DATE
  );

ALTER TABLE volume_horaire ADD (
  horaire_fin   DATE
  );

ALTER TABLE volume_horaire_ref ADD (
  horaire_debut   DATE
  );

ALTER TABLE volume_horaire_ref ADD (
  horaire_fin   DATE
  );


ALTER TABLE type_formation ADD (
  service_statutaire   NUMBER(1) DEFAULT 1 NOT NULL
  );

ALTER TABLE fonction_referentiel ADD (
  service_statutaire   NUMBER(1) DEFAULT 1 NOT NULL
  );


INSERT INTO parametre (
  id,
  nom,
  valeur,
  description,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval,
  'modalite_services_prev_ens',
  'semestriel',
  'Modalité de gestion des services (prévisionnel, enseignements)',
  sysdate,
  (select id from utilisateur where username='oseappli'),
  sysdate,
  (select id from utilisateur where username='oseappli')
);

INSERT INTO parametre (
  id,
  nom,
  valeur,
  description,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval,
  'modalite_services_real_ref',
  'semestriel',
  'Modalité de gestion des services (réalisé, référentiel)',
  sysdate,
  (select id from utilisateur where username='oseappli'),
  sysdate,
  (select id from utilisateur where username='oseappli')
);

INSERT INTO parametre (
  id,
  nom,
  valeur,
  description,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval,
  'modalite_services_prev_ref',
  'semestriel',
  'Modalité de gestion des services (prévisionnel, référentiel)',
  sysdate,
  (select id from utilisateur where username='oseappli'),
  sysdate,
  (select id from utilisateur where username='oseappli')
);

INSERT INTO parametre (
  id,
  nom,
  valeur,
  description,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval,
  'modalite_services_real_ens',
  'semestriel',
  'Modalité de gestion des services (réalisé, enseignements)',
  sysdate,
  (select id from utilisateur where username='oseappli'),
  sysdate,
  (select id from utilisateur where username='oseappli')
);

INSERT INTO parametre (
  id,
  nom,
  valeur,
  description,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval,
  'pays_france',
  null,
  'Pays "France"',
  sysdate,
  (select id from utilisateur where username='oseappli'),
  sysdate,
  (select id from utilisateur where username='oseappli')
);

INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'doc-intervenant-vacataires',
                 '',
                 'URL de la documentation OSE pour les vacataires',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );

INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'doc-intervenant-permanents',
                 '',
                 'URL de la documentation OSE pour les permanents',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT
  privilege_id_seq.nextval id,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
  t1.p CODE,
  t1.l LIBELLE,
  (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (
       SELECT 'enseignement' c, 'import-intervenant-previsionnel-agenda' p, 'Import service prévisionnel depuis agenda' l FROM dual
       UNION ALL SELECT 'enseignement' c, 'import-intervenant-realise-agenda' p, 'Import service réalisé depuis agenda' l FROM dual
     ) t1;


ALTER TABLE type_intervention ADD (
  visible_exterieur   NUMBER(1) DEFAULT 1 NOT NULL
  );
UPDATE type_intervention SET visible_exterieur = 0 WHERE code NOT IN ('CM','TD','TP','Stage','Projet','Mémoire');

update tbl set ordre =  1 where tbl_name = 'formule';
update tbl set ordre =  2 where tbl_name = 'piece_jointe_demande';
update tbl set ordre =  3 where tbl_name = 'piece_jointe_fournie';
update tbl set ordre =  4 where tbl_name = 'agrement';
update tbl set ordre =  5 where tbl_name = 'cloture_realise';
update tbl set ordre =  6 where tbl_name = 'contrat';
update tbl set ordre =  7 where tbl_name = 'dossier';
update tbl set ordre =  8 where tbl_name = 'paiement';
update tbl set ordre =  9 where tbl_name = 'piece_jointe';
update tbl set ordre = 10 where tbl_name = 'service_saisie';
update tbl set ordre = 11 where tbl_name = 'service_referentiel';
update tbl set ordre = 12 where tbl_name = 'validation_enseignement';
update tbl set ordre = 13 where tbl_name = 'validation_referentiel';
update tbl set ordre = 14 where tbl_name = 'service';
update tbl set ordre = 15 where tbl_name = 'workflow';
update tbl set ordre = 16 where tbl_name = 'chargens_seuils_def';
update tbl set ordre = 17 where tbl_name = 'chargens';





CREATE TABLE modele_contrat (
  id                      NUMBER(*,0) NOT NULL,
  libelle                 VARCHAR2(250 CHAR) NOT NULL,
  statut_intervenant_id   NUMBER(*,0),
  structure_id            NUMBER(*,0),
  fichier                 BLOB,
  requete                 VARCHAR2(4000 CHAR),
  bloc1_nom               VARCHAR2(50 CHAR),
  bloc1_zone              VARCHAR2(80 CHAR),
  bloc1_requete           VARCHAR2(4000 CHAR),
  bloc2_nom               VARCHAR2(50 CHAR),
  bloc2_zone              VARCHAR2(80 CHAR),
  bloc2_requete           VARCHAR2(4000 CHAR),
  bloc3_nom               VARCHAR2(50 CHAR),
  bloc3_zone              VARCHAR2(80 CHAR),
  bloc3_requete           VARCHAR2(4000 CHAR),
  bloc4_nom               VARCHAR2(50 CHAR),
  bloc4_zone              VARCHAR2(80 CHAR),
  bloc4_requete           VARCHAR2(4000 CHAR),
  bloc5_nom               VARCHAR2(50 CHAR),
  bloc5_zone              VARCHAR2(80 CHAR),
  bloc5_requete           VARCHAR2(4000 CHAR),
  bloc6_nom               VARCHAR2(50 CHAR),
  bloc6_zone              VARCHAR2(80 CHAR),
  bloc6_requete           VARCHAR2(4000 CHAR),
  bloc7_nom               VARCHAR2(50 CHAR),
  bloc7_zone              VARCHAR2(80 CHAR),
  bloc7_requete           VARCHAR2(4000 CHAR),
  bloc8_nom               VARCHAR2(50 CHAR),
  bloc8_zone              VARCHAR2(80 CHAR),
  bloc8_requete           VARCHAR2(4000 CHAR),
  bloc9_nom               VARCHAR2(50 CHAR),
  bloc9_zone              VARCHAR2(80 CHAR),
  bloc9_requete           VARCHAR2(4000 CHAR),
  bloc10_nom              VARCHAR2(50 CHAR),
  bloc10_zone             VARCHAR2(80 CHAR),
  bloc10_requete          VARCHAR2(4000 CHAR)
)
LOGGING;

CREATE SEQUENCE MODELE_CONTRAT_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;

ALTER TABLE modele_contrat ADD CONSTRAINT modele_contrat_pk PRIMARY KEY ( id );
ALTER TABLE modele_contrat
  ADD CONSTRAINT mct_structure_fk FOREIGN KEY ( structure_id )
REFERENCES structure ( id )
  NOT DEFERRABLE;
ALTER TABLE modele_contrat
  ADD CONSTRAINT mct_statut_intervenant_fk FOREIGN KEY ( statut_intervenant_id )
REFERENCES statut_intervenant ( id )
  NOT DEFERRABLE;


INSERT INTO modele_contrat (
    id,
    libelle
    ) VALUES (
                 modele_contrat_id_seq.nextval,
                 'Modèle par défaut'
                 );

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT
       privilege_id_seq.nextval id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
       t1.p CODE,
       t1.l LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (

     SELECT 'contrat' c, 'modeles-visualisation' p, 'Visualisation des modèles' l FROM dual
     UNION ALL SELECT 'contrat' c, 'modeles-edition' p, 'Édition des modèles' l FROM dual
     UNION ALL SELECT 'contrat' c, 'projet-generation' p, 'Génération de projet de contrat' l FROM dual
     UNION ALL SELECT 'contrat' c, 'contrat-generation' p, 'Génération de contrat' l FROM dual
     ) t1;

CREATE OR REPLACE VIEW V_CONTRAT_SERVICES AS
  SELECT
         c.id                                             contrat_id,
         str.libelle_court                                "serviceComposante",
         ep.code                                          "serviceCode",
         ep.libelle                                       "serviceLibelle",
         sum(vh.heures)                                   heures,
         replace(ltrim(to_char(sum(vh.heures), '999999.00')),'.',',') "serviceHeures"
  FROM
       contrat               c
         JOIN intervenant           i ON i.id = c.intervenant_id
         JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
         JOIN service               s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
         JOIN volume_horaire       vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL AND vh.type_volume_horaire_id = tvh.id
         JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
         JOIN validation            v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
         LEFT JOIN validation           cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
         LEFT JOIN element_pedagogique  ep ON ep.id = s.element_pedagogique_id
         JOIN structure           str ON str.id = COALESCE(ep.structure_id,i.structure_id)
  WHERE
      c.histo_destruction IS NULL
    AND (cv.id IS NULL OR vh.contrat_id = c.id)
  GROUP BY
           c.id, str.libelle_court, ep.code, ep.libelle
;

CREATE OR REPLACE VIEW V_CONTRAT_MAIN AS
  WITH hs AS (
      SELECT contrat_id, sum(heures) "serviceTotal" FROM V_CONTRAT_SERVICES GROUP BY contrat_id
  )
  SELECT
         ct.id contrat_id,
         ct."annee",
         ct."nom",
         ct."prenom",
         ct."civilite",
         ct."e",
         ct."dateNaissance",
         ct."adresse",
         ct."numInsee",
         ct."statut",
         ct."totalHETD",
         ct."tauxHoraireValeur",
         ct."tauxHoraireDate",
         ct."dateSignature",
         CASE WHEN ct.est_contrat=1 THEN 1 ELSE null END "contrat1",
         CASE WHEN ct.est_contrat=1 THEN null ELSE 1 END "avenant1",
         CASE WHEN ct.est_contrat=1 THEN '3' ELSE '2' END "n",
         to_char(SYSDATE, 'dd/mm/YYYY - hh24:mi:ss') "horodatage",
         'Exemplaire à conserver' "exemplaire1",
         'Exemplaire à retourner' || ct."exemplaire2" "exemplaire2",
         ct."serviceTotal",

         CASE ct.est_contrat
           WHEN 1 THEN -- contrat
             'Contrat de travail '
           ELSE
             'Avenant au contrat de travail initial modifiant le volume horaire initial'
               || ' de recrutement en qualité '
             END                                         "titre",
         CASE WHEN ct.est_atv = 1 THEN
             'd''agent temporaire vacataire'
              ELSE
             'de chargé' || ct."e" || ' d''enseignement vacataire'
             END                                         "qualite",

         CASE
           WHEN ct.est_projet = 1 AND ct.est_contrat = 1 THEN 'Projet de contrat'
           WHEN ct.est_projet = 0 AND ct.est_contrat = 1 THEN 'Contrat n°' || ct.id
           WHEN ct.est_projet = 1 AND ct.est_contrat = 0 THEN 'Projet d''avenant'
           WHEN ct.est_projet = 0 AND ct.est_contrat = 0 THEN 'Avenant n°' || ct.contrat_id || '.' || ct.numero_avenant
             END                                         "titreCourt"
  FROM
       (
       SELECT
              c.*,
              a.libelle                                                                                     "annee",
              COALESCE(d.nom_usuel,i.nom_usuel)                                                             "nom",
              COALESCE(d.prenom,i.prenom)                                                                   "prenom",
              civ.libelle_court                                                                             "civilite",
              CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                                 "e",
              to_char(COALESCE(d.date_naissance,i.date_naissance), 'dd/mm/YYYY')                            "dateNaissance",
              COALESCE(d.adresse,ose_divers.formatted_adresse(
                                   ai.NO_VOIE, ai.NOM_VOIE, ai.BATIMENT, ai.MENTION_COMPLEMENTAIRE, ai.LOCALITE,
                                   ai.CODE_POSTAL, ai.VILLE, ai.PAYS_LIBELLE))                                               "adresse",
              COALESCE(d.numero_insee,i.numero_insee || ' ' || COALESCE(LPAD(i.numero_insee_cle,2,'0'),'')) "numInsee",
              si.libelle                                                                                    "statut",
              replace(ltrim(to_char(COALESCE(fr.total,0), '999999.00')),'.',',')                                        "totalHETD",
              replace(ltrim(to_char(COALESCE(th.valeur,0), '999999.00')),'.',',')                                       "tauxHoraireValeur",
              COALESCE(to_char(th.histo_creation, 'dd/mm/YYYY'), 'TAUX INTROUVABLE')                        "tauxHoraireDate",
              to_char(COALESCE(v.histo_creation, c.histo_creation), 'dd/mm/YYYY')                           "dateSignature",
              CASE WHEN s.aff_adresse_contrat = 1 THEN
                  ' signé à l''adresse suivante :' || CHR(13) || CHR(10) ||
                  s.libelle_court || ' - ' || REPLACE(ose_divers.formatted_adresse(
                                                        astr.NO_VOIE, astr.NOM_VOIE, null, null, astr.LOCALITE,
                                                        astr.CODE_POSTAL, astr.VILLE, null), CHR(13), ' - ')
                   ELSE '' END                                                                                   "exemplaire2",
              replace(ltrim(to_char(COALESCE(hs."serviceTotal",0), '999999.00')),'.',',')                               "serviceTotal",
              CASE WHEN c.contrat_id IS NULL THEN 1 ELSE 0 END                                              est_contrat,
              CASE WHEN v.id IS NULL THEN 1 ELSE 0 END                                                      est_projet,
              si.tem_atv                                                                                    est_atv

       FROM
            contrat               c
              JOIN type_contrat         tc ON tc.id = c.type_contrat_id
              JOIN intervenant           i ON i.id = c.intervenant_id
              JOIN annee                 a ON a.id = i.annee_id
              JOIN statut_intervenant   si ON si.id = i.statut_id
              JOIN structure             s ON s.id = c.structure_id
              LEFT JOIN adresse_structure  astr ON astr.structure_id = s.id AND astr.principale = 1 AND astr.histo_destruction IS NULL
              LEFT JOIN dossier               d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
              JOIN civilite            civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
              LEFT JOIN validation            v ON v.id = c.validation_id AND v.histo_destruction IS NULL
              LEFT JOIN adresse_intervenant  ai ON ai.intervenant_id = i.id AND ai.histo_destruction IS NULL

              JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
              JOIN etat_volume_horaire evh ON evh.code = 'valide'
              LEFT JOIN formule_resultat     fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
              LEFT JOIN taux_horaire_hetd    th ON c.histo_creation BETWEEN th.histo_creation AND COALESCE(th.histo_destruction,SYSDATE)
              LEFT JOIN                      hs ON hs.contrat_id = c.id
       WHERE
           c.histo_destruction IS NULL
       ) ct
;



CREATE OR REPLACE FORCE VIEW V_FORMULE_SERVICE
( "ID", "INTERVENANT_ID", "TAUX_FI", "TAUX_FA", "TAUX_FC", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "PONDERATION_SERVICE_DU", "PONDERATION_SERVICE_COMPL", "SERVICE_STATUTAIRE"
)  AS
  SELECT
         s.id                                                    id,
         s.intervenant_id                                        intervenant_id,
         CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END  taux_fi,
         CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END  taux_fa,
         CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END  taux_fc,
         i.structure_id                                          structure_aff_id,
         ep.structure_id                                         structure_ens_id,
         NVL( EXP (SUM (LN (m.ponderation_service_du))), 1)      ponderation_service_du,
         NVL( EXP (SUM (LN (m.ponderation_service_compl))), 1)   ponderation_service_compl,
         COALESCE(tf.service_statutaire,1)                       service_statutaire
  FROM
       service              s
         JOIN intervenant          i ON i.id = s.intervenant_id
         LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
         LEFT JOIN etape                e ON e.id = ep.etape_id
         LEFT JOIN type_formation      tf ON tf.id = e.type_formation_id
         LEFT JOIN element_modulateur  em ON em.element_id = s.element_pedagogique_id
                                               AND em.histo_destruction IS NULL
         LEFT JOIN modulateur           m ON m.id = em.modulateur_id
  WHERE
      s.histo_destruction IS NULL
  GROUP BY
           s.id,
           s.intervenant_id,
           ep.id,
           ep.taux_fi, ep.taux_fa, ep.taux_fc,
           i.structure_id, ep.structure_id,
           tf.service_statutaire;

CREATE OR REPLACE FORCE VIEW V_FORMULE_SERVICE_REF
( "ID", "INTERVENANT_ID", "STRUCTURE_ID", "SERVICE_STATUTAIRE"
)  AS
  SELECT
         sr.id                             id,
         sr.intervenant_id                 intervenant_id,
         sr.structure_id                   structure_id,
         COALESCE(fr.service_statutaire,1) service_statutaire
  FROM
       service_referentiel  sr
         JOIN intervenant           i ON i.id = sr.intervenant_id
         JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
  WHERE
      sr.histo_destruction IS NULL
    AND i.id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, i.id );


CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE
( "ANNEE_ID", "INTERVENANT_ID", "INTERVENANT_STRUCTURE_ID", "STRUCTURE_ID", "TYPE_INTERVENANT_ID", "TYPE_INTERVENANT_CODE", "PEUT_SAISIR_SERVICE", "ELEMENT_PEDAGOGIQUE_ID", "SERVICE_ID", "ELEMENT_PEDAGOGIQUE_PERIODE_ID", "ETAPE_ID", "TYPE_VOLUME_HORAIRE_ID", "TYPE_VOLUME_HORAIRE_CODE", "ELEMENT_PEDAGOGIQUE_HISTO", "ETAPE_HISTO", "HAS_HEURES_MAUVAISE_PERIODE", "NBVH", "HEURES", "VALIDE"
)  AS
  WITH t AS (
      SELECT
             s.id                                                                                      service_id,
             s.intervenant_id                                                                          intervenant_id,
             ep.structure_id                                                                           structure_id,
             ep.id                                                                                     element_pedagogique_id,
             ep.periode_id                                                                             element_pedagogique_periode_id,
             etp.id                                                                                    etape_id,

             vh.type_volume_horaire_id                                                                 type_volume_horaire_id,
             vh.heures                                                                                 heures,
             tvh.code                                                                                  type_volume_horaire_code,

             CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
             CASE WHEN etp.histo_destruction IS NULL OR cp.id IS NOT NULL THEN 1 ELSE 0 END            etape_histo,

             CASE WHEN ep.periode_id IS NOT NULL THEN
                 SUM( CASE WHEN vh.periode_id <> ep.periode_id THEN 1 ELSE 0 END ) OVER( PARTITION BY vh.service_id, vh.periode_id, vh.type_volume_horaire_id, vh.type_intervention_id )
                  ELSE 0 END has_heures_mauvaise_periode,

             CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
      FROM
           service                                       s
             LEFT JOIN element_pedagogique                ep ON ep.id = s.element_pedagogique_id
             LEFT JOIN etape                             etp ON etp.id = ep.etape_id
             LEFT JOIN chemin_pedagogique                 cp ON cp.etape_id = etp.id
                                                                  AND cp.element_pedagogique_id = ep.id
                                                                  AND cp.histo_destruction IS NULL

             JOIN volume_horaire                     vh ON vh.service_id = s.id
                                                             AND vh.histo_destruction IS NULL

             JOIN type_volume_horaire               tvh ON tvh.id = vh.type_volume_horaire_id

             LEFT JOIN validation_vol_horaire            vvh ON vvh.volume_horaire_id = vh.id

             LEFT JOIN validation                          v ON v.id = vvh.validation_id
                                                                  AND v.histo_destruction IS NULL
      WHERE
          s.histo_destruction IS NULL
  )
  SELECT
         i.annee_id                                                                                annee_id,
         i.id                                                                                      intervenant_id,
         i.structure_id                                                                            intervenant_structure_id,
         NVL( t.structure_id, i.structure_id )                                                     structure_id,
         ti.id                                                                                     type_intervenant_id,
         ti.code                                                                                   type_intervenant_code,
         si.peut_saisir_service                                                                    peut_saisir_service,

         t.element_pedagogique_id,
         t.service_id,
         t.element_pedagogique_periode_id,
         t.etape_id,
         t.type_volume_horaire_id,
         t.type_volume_horaire_code,
         t.element_pedagogique_histo,
         t.etape_histo,

         CASE WHEN SUM(t.has_heures_mauvaise_periode) > 0 THEN 1 ELSE 0 END has_heures_mauvaise_periode,

         CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
         CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE sum(t.heures) END heures,
         sum(valide) valide
  FROM
       t
         JOIN intervenant                              i ON i.id = t.intervenant_id
         JOIN statut_intervenant                      si ON si.id = i.statut_id
         JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
  GROUP BY
           i.annee_id,
           i.id,
           i.structure_id,
           t.structure_id,
           i.structure_id,
           ti.id,
           ti.code,
           si.peut_saisir_service,
           t.element_pedagogique_id,
           t.service_id,
           t.element_pedagogique_periode_id,
           t.etape_id,
           t.type_volume_horaire_id,
           t.type_volume_horaire_code,
           t.element_pedagogique_histo,
           t.etape_histo;


CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE_REFERENTIEL
( "ANNEE_ID", "INTERVENANT_ID", "PEUT_SAISIR_SERVICE", "TYPE_VOLUME_HORAIRE_ID", "STRUCTURE_ID", "NBVH", "VALIDE"
)  AS
  WITH t AS (

      SELECT
             i.annee_id,
             i.id intervenant_id,
             si.peut_saisir_referentiel peut_saisir_service,
             vh.type_volume_horaire_id,
             s.structure_id,
             CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
      FROM
           intervenant                     i

             JOIN statut_intervenant          si ON si.id = i.statut_id

             LEFT JOIN service_referentiel          s ON s.intervenant_id = i.id
                                                           AND s.histo_destruction IS NULL

             LEFT JOIN volume_horaire_ref          vh ON vh.service_referentiel_id = s.id
                                                           AND vh.histo_destruction IS NULL

             LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id

             LEFT JOIN validation                   v ON v.id = vvh.validation_id
                                                           AND v.histo_destruction IS NULL
      WHERE
          i.histo_destruction IS NULL

  )
  SELECT
         annee_id,
         intervenant_id,
         peut_saisir_service,
         type_volume_horaire_id,
         structure_id,
         CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
         sum(valide) valide
  FROM
       t
  WHERE
      NOT (structure_id IS NOT NULL AND type_volume_horaire_id IS NULL)
  GROUP BY
           annee_id,
           intervenant_id,
           peut_saisir_service,
           type_volume_horaire_id,
           structure_id;



CREATE OR REPLACE FORCE VIEW V_TBL_VALIDATION_ENSEIGNEMENT
( "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_VOLUME_HORAIRE_ID", "SERVICE_ID", "VOLUME_HORAIRE_ID", "AUTO_VALIDATION", "VALIDATION_ID"
)  AS
  SELECT DISTINCT
                  i.annee_id,
                  i.id intervenant_id,
                  CASE WHEN rsv.priorite = 'affectation' THEN
                      COALESCE( i.structure_id, ep.structure_id )
                       ELSE
                      COALESCE( ep.structure_id, i.structure_id )
                      END structure_id,
                  vh.type_volume_horaire_id,
                  s.id service_id,
                  vh.id volume_horaire_id,
                  vh.auto_validation,
                  v.id validation_id
  FROM
       service s
         JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
         JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
         JOIN statut_intervenant si ON si.id = i.statut_id
         JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
         LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
         LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
         LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
  WHERE
      s.histo_destruction IS NULL;



CREATE OR REPLACE FORCE VIEW V_TBL_VALIDATION_REFERENTIEL
( "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_VOLUME_HORAIRE_ID", "SERVICE_REFERENTIEL_ID", "VOLUME_HORAIRE_REF_ID", "AUTO_VALIDATION", "VALIDATION_ID"
)  AS
  SELECT DISTINCT
                  i.annee_id,
                  i.id intervenant_id,
                  CASE WHEN rsv.priorite = 'affectation' THEN
                      COALESCE( i.structure_id, s.structure_id )
                       ELSE
                      COALESCE( s.structure_id, i.structure_id )
                      END structure_id,
                  vh.type_volume_horaire_id,
                  s.id service_referentiel_id,
                  vh.id volume_horaire_ref_id,
                  vh.auto_validation,
                  v.id validation_id
  FROM
       service_referentiel s
         JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND vh.histo_destruction IS NULL
         JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
         JOIN statut_intervenant si ON si.id = i.statut_id
         JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
         LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
         LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
  WHERE
      s.histo_destruction IS NULL;



CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW
( "ETAPE_CODE", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "OBJECTIF", "REALISATION"
)  AS
  WITH pj AS (
      SELECT
             annee_id,
             intervenant_id,
             SUM(demandee) demandees,
             SUM(fournie)  fournies,
             SUM(validee)  validees
      FROM
           tbl_piece_jointe
      WHERE
          1 = OSE_WORKFLOW.match_intervenant(intervenant_id)
        AND demandee > 0
      GROUP BY
               annee_id,
               intervenant_id
  ),
      mep AS (
        SELECT
               annee_id,
               intervenant_id,
               structure_id,
               SUM(heures_a_payer / heures_a_payer_pond) sap,
               SUM(heures_demandees) dmep,
               SUM(heures_payees) mep
            --COUNT(*)  sap,
            --SUM(CASE WHEN mise_en_paiement_id IS NULL THEN 0 ELSE 1 END) dmep,
            --SUM(CASE WHEN periode_paiement_id IS NULL THEN 0 ELSE 1 END) mep
        FROM
             tbl_paiement
        WHERE
            1 = OSE_WORKFLOW.match_intervenant(intervenant_id)
        GROUP BY
                 annee_id,
                 intervenant_id,
                 structure_id
    )
  SELECT
         e.code                                                    etape_code,
         d.annee_id                                                annee_id,
         d.intervenant_id                                          intervenant_id,
         null                                                      structure_id,
         1                                                         objectif,
         CASE
           WHEN e.code = 'DONNEES_PERSO_SAISIE' THEN
             CASE WHEN d.dossier_id IS NULL THEN 0 ELSE 1 END

           WHEN e.code = 'DONNEES_PERSO_VALIDATION' THEN
             CASE WHEN d.validation_id IS NULL THEN 0 ELSE 1 END

             END                                                       realisation
  FROM
       tbl_dossier d
         JOIN (
              SELECT 'DONNEES_PERSO_SAISIE'     code FROM dual
              UNION SELECT 'DONNEES_PERSO_VALIDATION' code FROM dual
              ) e ON 1=1
  WHERE
      d. peut_saisir_dossier = 1
    AND 1 = OSE_WORKFLOW.match_intervenant(d.intervenant_id)

  UNION ALL

  SELECT
         e.code                                                    etape_code,
         tss.annee_id                                              annee_id,
         tss.intervenant_id                                        intervenant_id,
         NULL                                                      structure_id,
         1                                                         objectif,
         CASE
           WHEN e.code = 'SERVICE_SAISIE' THEN
             CASE WHEN tss.heures_service_prev + tss.heures_referentiel_prev > 0 THEN 1 ELSE 0 END

           WHEN e.code = 'SERVICE_SAISIE_REALISE' THEN
             CASE WHEN tss.heures_service_real + tss.heures_referentiel_real > 0 THEN 1 ELSE 0 END

             END                                                       realisation
  FROM
       TBL_SERVICE_SAISIE tss
         JOIN (
              SELECT 'SERVICE_SAISIE'                 code FROM dual
              UNION SELECT 'SERVICE_SAISIE_REALISE'         code FROM dual
              ) e ON 1=1
  WHERE
      (tss.peut_saisir_service = 1 OR tss.peut_saisir_referentiel = 1)
    AND 1 = OSE_WORKFLOW.match_intervenant(tss.intervenant_id)

  UNION ALL

  SELECT
         CASE
           WHEN tvh.code = 'PREVU'   THEN 'SERVICE_VALIDATION'
           WHEN tvh.code = 'REALISE' THEN 'SERVICE_VALIDATION_REALISE'
             END                                                        etape_code,
         tve.annee_id                                               annee_id,
         tve.intervenant_id                                         intervenant_id,
         tve.structure_id                                           structure_id,
         COUNT(*)                                                   objectif,
         SUM(CASE WHEN tve.validation_id IS NOT NULL THEN 1 ELSE 0 END) realisation
  FROM
       tbl_validation_enseignement tve
         JOIN type_volume_horaire tvh ON tvh.id = tve.type_volume_horaire_id
  WHERE
      1 = OSE_WORKFLOW.match_intervenant(tve.intervenant_id)
    AND tve.auto_validation = 0
  GROUP BY
           tve.annee_id,
           tve.intervenant_id,
           tve.structure_id,
           tvh.code

  UNION ALL

  SELECT
         CASE
           WHEN tvh.code = 'PREVU'   THEN 'REFERENTIEL_VALIDATION'
           WHEN tvh.code = 'REALISE' THEN 'REFERENTIEL_VALIDATION_REALISE'
             END                                                        etape_code,
         tvr.annee_id                                               annee_id,
         tvr.intervenant_id                                         intervenant_id,
         tvr.structure_id                                           structure_id,
         count(*)                                                   objectif,
         SUM(CASE WHEN tvr.validation_id IS NOT NULL THEN 1 ELSE 0 END) realisation
  FROM
       tbl_validation_referentiel tvr
         JOIN type_volume_horaire tvh ON tvh.id = tvr.type_volume_horaire_id
  WHERE
      1 = OSE_WORKFLOW.match_intervenant(tvr.intervenant_id)
    AND tvr.auto_validation = 0
  GROUP BY
           tvr.annee_id,
           tvr.intervenant_id,
           tvr.structure_id,
           tvh.code

  UNION ALL

  SELECT
         e.code                                                    etape_code,
         pj.annee_id                                               annee_id,
         pj.intervenant_id                                         intervenant_id,
         null                                                      structure_id,
         CASE
           WHEN e.code = 'PJ_SAISIE' THEN pj.demandees
           WHEN e.code = 'PJ_VALIDATION' THEN pj.demandees
             END                                                       objectif,
         CASE
           WHEN e.code = 'PJ_SAISIE' THEN pj.fournies
           WHEN e.code = 'PJ_VALIDATION' THEN pj.validees
             END                                                       realisation
  FROM
       pj
         JOIN (
              SELECT 'PJ_SAISIE'      code FROM dual
              UNION SELECT 'PJ_VALIDATION'  code FROM dual
              ) e ON (
           (e.code = 'PJ_SAISIE'     AND pj.demandees > 0)
             OR (e.code = 'PJ_VALIDATION' AND pj.fournies  > 0)
           )

  UNION ALL

  SELECT
         ta.code                                                   etape_code,
         a.annee_id                                                annee_id,
         a.intervenant_id                                          intervenant_id,
         a.structure_id                                            structure_id,
         1                                                         objectif,
         CASE WHEN a.agrement_id IS NULL THEN 0 ELSE 1 END         realisation
  FROM
       tbl_agrement a
         JOIN type_agrement ta ON ta.id = a.type_agrement_id
  WHERE
      1 = OSE_WORKFLOW.match_intervenant(a.intervenant_id)

  UNION ALL

  SELECT
         'CLOTURE_REALISE'                                         etape_code,
         c.annee_id                                                annee_id,
         c.intervenant_id                                          intervenant_id,
         null                                                      structure_id,
         1                                                         objectif,
         c.cloture                                                 realisation
  FROM
       tbl_cloture_realise c
  WHERE
      c.peut_cloturer_saisie = 1
    AND 1 = OSE_WORKFLOW.match_intervenant(c.intervenant_id)

  UNION ALL

  SELECT
         e.code                                                    etape_code,
         mep.annee_id                                              annee_id,
         mep.intervenant_id                                        intervenant_id,
         mep.structure_id                                          structure_id,
         CASE
           WHEN e.code = 'DEMANDE_MEP' THEN mep.sap
           WHEN e.code = 'SAISIE_MEP' THEN mep.dmep
             END                                                       objectif,
         CASE
           WHEN e.code = 'DEMANDE_MEP' THEN mep.dmep
           WHEN e.code = 'SAISIE_MEP' THEN mep.mep
             END                                                       realisation
  FROM
       mep
         JOIN (
              SELECT 'DEMANDE_MEP'  code FROM dual
              UNION SELECT 'SAISIE_MEP'   code FROM dual
              ) e ON (
           (e.code = 'DEMANDE_MEP' AND mep.sap > 0)
             OR (e.code = 'SAISIE_MEP'  AND mep.dmep > 0)
           )


  UNION ALL

  SELECT
         'CONTRAT'                                                 etape_code,
         annee_id                                                  annee_id,
         intervenant_id                                            intervenant_id,
         structure_id                                              structure_id,
         nbvh                                                      objectif,
         edite                                                     realisation
  FROM
       tbl_contrat c
  WHERE
      peut_avoir_contrat = 1
    AND nbvh > 0
    AND 1 = OSE_WORKFLOW.match_intervenant(c.intervenant_id);



CREATE OR REPLACE FORCE VIEW V_TYPE_INTERVENTION_REGLE_EP
( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_REGLE_ID"
)  AS
  SELECT
         ep.id element_pedagogique_id,
         tir.id type_intervention_regle_id
  FROM
       element_pedagogique ep
         JOIN type_intervention_regle tir ON tir.code = 'foad'
  WHERE
      ep.taux_foad > 0

  UNION

  SELECT
         ep.id element_pedagogique_id,
         tir.id type_intervention_regle_id
  FROM
       element_pedagogique ep
         JOIN type_intervention_regle tir ON tir.code = 'fc'
  WHERE
      ep.taux_fc > 0;




CREATE OR REPLACE FORCE VIEW V_VOL_HORAIRE_ETAT_MULTI
( "VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID"
)  AS
  select vh.id VOLUME_HORAIRE_ID, evh.id ETAT_VOLUME_HORAIRE_ID
  from volume_horaire vh
         join service s on s.id = vh.service_id and s.histo_destruction IS NULL
         join etat_volume_horaire evh on evh.code = 'saisi'
  where vh.histo_destruction IS NULL
  union all
  select vh.id, evh.id
  from volume_horaire vh
         join service s on s.id = vh.service_id and s.histo_destruction IS NULL
         join etat_volume_horaire evh on evh.code = 'valide'
  where vh.histo_destruction IS NULL
          and EXISTS(
                SELECT * FROM validation v JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
                WHERE vvh.volume_horaire_id = vh.id AND v.histo_destruction IS NULL
            ) OR vh.auto_validation = 1
  union all
  select vh.id, evh.id
  from volume_horaire vh
         join service s on s.id = vh.service_id and s.histo_destruction IS NULL
         join contrat c on vh.contrat_id = c.id and c.histo_destruction IS NULL
         join etat_volume_horaire evh on evh.code = 'contrat-edite'
  where vh.histo_destruction IS NULL
  union all
  select vh.id, evh.id
  from volume_horaire vh
         join service s on s.id = vh.service_id and s.histo_destruction IS NULL
         join contrat c on vh.contrat_id = c.id and c.histo_destruction IS NULL and c.date_retour_signe is not null
         join etat_volume_horaire evh on evh.code = 'contrat-signe'
  where vh.histo_destruction IS NULL;



CREATE OR REPLACE FORCE VIEW V_VOL_HORAIRE_REF_ETAT_MULTI
( "VOLUME_HORAIRE_REF_ID", "ETAT_VOLUME_HORAIRE_ID"
)  AS
  select vh.id VOLUME_HORAIRE_REF_ID, evh.id ETAT_VOLUME_HORAIRE_ID
  from volume_horaire_ref vh
         join service_referentiel s on s.id = vh.service_referentiel_id and s.histo_destruction IS NULL
         join etat_volume_horaire evh on evh.code = 'saisi'
  where vh.histo_destruction IS NULL
  union all
  select vh.id, evh.id
  from volume_horaire_ref vh
         join service_referentiel s on s.id = vh.service_referentiel_id and s.histo_destruction IS NULL
         join etat_volume_horaire evh on evh.code = 'valide'
  where vh.histo_destruction IS NULL
          and vh.auto_validation=1 OR EXISTS(
                                        SELECT * FROM validation v JOIN validation_vol_horaire_ref vvh ON vvh.validation_id = v.id
                                        WHERE vvh.volume_horaire_ref_id = vh.id AND v.histo_destruction IS NULL
            );


CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_ETAT
( "VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID"
)  AS
  SELECT
         vh.id volume_horaire_id,
         evh.id etat_volume_horaire_id
  FROM
       volume_horaire vh
         LEFT JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
         LEFT JOIN validation cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
         JOIN etat_volume_horaire evh ON evh.code = CASE
                                                      WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
                                                      WHEN cv.id IS NOT NULL THEN 'contrat-edite'
                                                      WHEN vh.auto_validation = 1 OR EXISTS(
                                                                                       SELECT * FROM validation v JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
                                                                                       WHERE vvh.volume_horaire_id = vh.id AND v.histo_destruction IS NULL
               ) THEN 'valide'
                                                      ELSE 'saisi'
           END;



CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_REF_ETAT
( "VOLUME_HORAIRE_REF_ID", "ETAT_VOLUME_HORAIRE_ID"
)  AS
  SELECT
         vhr.id volume_horaire_ref_id,
         evh.id etat_volume_horaire_id
  FROM
       volume_horaire_ref vhr
         JOIN etat_volume_horaire evh ON evh.code = CASE
                                                      WHEN vhr.auto_validation = 1 OR EXISTS(
                                                                                        SELECT * FROM validation v JOIN validation_vol_horaire_ref vvhr ON vvhr.validation_id = v.id
                                                                                        WHERE vvhr.volume_horaire_ref_id = vhr.id AND v.histo_destruction IS NULL
               ) THEN 'valide'
                                                      ELSE 'saisi'
           END;



/

CREATE OR REPLACE PACKAGE OSE_FORMULE AS

  PACKAGE_SUJET VARCHAR2(80) DEFAULT 'OSE_FORMULE';

  TYPE t_intervenant IS RECORD (
  structure_id                   NUMERIC,
  annee_id                       NUMERIC,
  heures_decharge                FLOAT DEFAULT 0,
  heures_service_statutaire      FLOAT DEFAULT 0,
  heures_service_modifie         FLOAT DEFAULT 0,
  depassement_service_du_sans_hc FLOAT DEFAULT 0
  );

  TYPE t_type_etat_vh IS RECORD (
  type_volume_horaire_id    NUMERIC,
  etat_volume_horaire_id    NUMERIC
  );
  TYPE t_lst_type_etat_vh   IS TABLE OF t_type_etat_vh INDEX BY PLS_INTEGER;

  TYPE t_service_ref IS RECORD (
  id                        NUMERIC,
  structure_id              NUMERIC,
  service_statutaire        BOOLEAN
  );
  TYPE t_lst_service_ref      IS TABLE OF t_service_ref INDEX BY PLS_INTEGER;

  TYPE t_service IS RECORD (
  id                        NUMERIC,
  taux_fi                   FLOAT   DEFAULT 1,
  taux_fa                   FLOAT   DEFAULT 0,
  taux_fc                   FLOAT   DEFAULT 0,
  ponderation_service_du    FLOAT   DEFAULT 1,
  ponderation_service_compl FLOAT   DEFAULT 1,
  structure_aff_id          NUMERIC,
  structure_ens_id          NUMERIC,
  service_statutaire        BOOLEAN
  );
  TYPE t_lst_service          IS TABLE OF t_service INDEX BY PLS_INTEGER;

  TYPE t_volume_horaire_ref IS RECORD (
  id                        NUMERIC,
  service_referentiel_id    NUMERIC,
  type_volume_horaire_id    NUMERIC,
  etat_volume_horaire_id    NUMERIC,
  etat_volume_horaire_ordre NUMERIC,
  heures                    FLOAT   DEFAULT 0
  );
  TYPE t_lst_volume_horaire_ref   IS TABLE OF t_volume_horaire_ref INDEX BY PLS_INTEGER;

  TYPE t_volume_horaire IS RECORD (
  id                        NUMERIC,
  service_id                NUMERIC,
  type_volume_horaire_id    NUMERIC,
  etat_volume_horaire_id    NUMERIC,
  etat_volume_horaire_ordre NUMERIC,
  heures                    FLOAT   DEFAULT 0,
  taux_service_du           FLOAT   DEFAULT 1,
  taux_service_compl        FLOAT   DEFAULT 1
  );
  TYPE t_lst_volume_horaire   IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;



  TYPE t_resultat_hetd IS RECORD (
  service_fi                FLOAT DEFAULT 0,
  service_fa                FLOAT DEFAULT 0,
  service_fc                FLOAT DEFAULT 0,
  heures_compl_fi           FLOAT DEFAULT 0,
  heures_compl_fa           FLOAT DEFAULT 0,
  heures_compl_fc           FLOAT DEFAULT 0,
  heures_compl_fc_majorees  FLOAT DEFAULT 0
  );
  TYPE t_lst_resultat_hetd   IS TABLE OF t_resultat_hetd INDEX BY PLS_INTEGER;

  TYPE t_resultat_hetd_ref IS RECORD (
  service_referentiel       FLOAT DEFAULT 0,
  heures_compl_referentiel  FLOAT DEFAULT 0
  );
  TYPE t_lst_resultat_hetd_ref   IS TABLE OF t_resultat_hetd_ref INDEX BY PLS_INTEGER;

  TYPE t_resultat IS RECORD (
  intervenant_id            NUMERIC,
  type_volume_horaire_id    NUMERIC,
  etat_volume_horaire_id    NUMERIC,
  service_du                FLOAT DEFAULT 0,
  solde                     FLOAT DEFAULT 0,
  sous_service              FLOAT DEFAULT 0,
  heures_compl              FLOAT DEFAULT 0,
  volume_horaire            t_lst_resultat_hetd,
  volume_horaire_ref        t_lst_resultat_hetd_ref
  );

  d_intervenant         t_intervenant;
  d_type_etat_vh        t_lst_type_etat_vh;
  d_service_ref         t_lst_service_ref;
  d_service             t_lst_service;
  d_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_volume_horaire      t_lst_volume_horaire;
  d_resultat            t_resultat;

  FUNCTION  GET_INTERVENANT_ID RETURN NUMERIC;
  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  PROCEDURE SET_DEBUG_LEVEL( DEBUG_LEVEL NUMERIC );
  FUNCTION GET_DEBUG_LEVEL RETURN NUMERIC;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT;
  PROCEDURE UPDATE_ANNEE_TAUX_HETD;

  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL );        -- mise à jour de TOUTES les données ! ! ! !
  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS );

  PROCEDURE SET_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL);
  FUNCTION GET_INTERVENANT RETURN NUMERIC;
  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC;
END OSE_FORMULE;
/

CREATE OR REPLACE PACKAGE BODY OSE_FORMULE AS

  v_date_obs DATE;
  debug_level NUMERIC DEFAULT 0;
  d_all_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_all_volume_horaire      t_lst_volume_horaire;
  arrondi NUMERIC DEFAULT 2;

  INTERVENANT_ID NUMERIC DEFAULT NULL;

  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
    BEGIN
      RETURN INTERVENANT_ID;
    END;

  FUNCTION GET_DATE_OBS RETURN DATE AS
    BEGIN
      RETURN COALESCE( v_date_obs, SYSDATE );
    END;

  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
    BEGIN
      v_date_obs := DATE_OBS;
      RETURN v_date_obs;
    END;

  PROCEDURE SET_DEBUG_LEVEL( DEBUG_LEVEL NUMERIC ) IS
    BEGIN
      ose_formule.debug_level := SET_DEBUG_LEVEL.DEBUG_LEVEL;
    END;

  FUNCTION GET_DEBUG_LEVEL RETURN NUMERIC IS
    BEGIN
      RETURN ose_formule.debug_level;
    END;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT IS
    taux_hetd FLOAT;
    BEGIN
      SELECT valeur INTO taux_hetd
      FROM taux_horaire_hetd t
      WHERE
          DATE_OBS BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,GREATEST(SYSDATE,DATE_OBS))
        AND rownum = 1
      ORDER BY
               histo_creation DESC;
      RETURN taux_hetd;
    END;

  PROCEDURE UPDATE_ANNEE_TAUX_HETD IS
    BEGIN
      UPDATE annee SET taux_hetd = GET_TAUX_HORAIRE_HETD(date_fin);
    END;



  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
    a_id NUMERIC;
    BEGIN
      a_id := NVL(CALCULER_TOUT.ANNEE_ID, OSE_PARAMETRE.GET_ANNEE);
      FOR mp IN (
      SELECT DISTINCT
                      intervenant_id
      FROM
           service s
             JOIN intervenant i ON i.id = s.intervenant_id
      WHERE
          s.histo_destruction IS NULL
        AND i.annee_id = a_id

      UNION

      SELECT DISTINCT
                      intervenant_id
      FROM
           service_referentiel sr
             JOIN intervenant i ON i.id = sr.intervenant_id
      WHERE
          sr.histo_destruction IS NULL
        AND i.annee_id = a_id

      )
      LOOP
        CALCULER( mp.intervenant_id );
      END LOOP;
    END;



  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    ti_code VARCHAR(5);
    BEGIN

      SELECT
             ti.code INTO ti_code
      FROM
           type_intervenant        ti
             JOIN statut_intervenant si ON si.type_intervenant_id = ti.id
             JOIN intervenant         i ON i.statut_id = si.id
      WHERE
          i.id = fr.intervenant_id;



      MERGE INTO formule_resultat tfr USING dual ON (

        tfr.intervenant_id         = fr.intervenant_id
        AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
        AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id

      ) WHEN MATCHED THEN UPDATE SET

        service_du                     = ROUND( fr.service_du, arrondi ),
        service_fi                     = ROUND( fr.service_fi, arrondi ),
        service_fa                     = ROUND( fr.service_fa, arrondi ),
        service_fc                     = ROUND( fr.service_fc, arrondi ),
        service_referentiel            = ROUND( fr.service_referentiel, arrondi ),
        heures_compl_fi                = ROUND( fr.heures_compl_fi, arrondi ),
        heures_compl_fa                = ROUND( fr.heures_compl_fa, arrondi ),
        heures_compl_fc                = ROUND( fr.heures_compl_fc, arrondi ),
        heures_compl_fc_majorees       = ROUND( fr.heures_compl_fc_majorees, arrondi ),
        heures_compl_referentiel       = ROUND( fr.heures_compl_referentiel, arrondi ),
        total                          = ROUND( fr.total, arrondi ),
        solde                          = ROUND( fr.solde, arrondi ),
        sous_service                   = ROUND( fr.sous_service, arrondi ),
        heures_compl                   = ROUND( fr.heures_compl, arrondi ),
        to_delete                      = 0

      WHEN NOT MATCHED THEN INSERT (

        ID,
        INTERVENANT_ID,
        TYPE_VOLUME_HORAIRE_ID,
        ETAT_VOLUME_HORAIRE_ID,
        SERVICE_DU,
        SERVICE_FI,
        SERVICE_FA,
        SERVICE_FC,
        SERVICE_REFERENTIEL,
        HEURES_COMPL_FI,
        HEURES_COMPL_FA,
        HEURES_COMPL_FC,
        HEURES_COMPL_FC_majorees,
        HEURES_COMPL_REFERENTIEL,
        TOTAL,
        SOLDE,
        SOUS_SERVICE,
        HEURES_COMPL,
        TO_DELETE,
        type_intervenant_code

      ) VALUES (

        FORMULE_RESULTAT_ID_SEQ.NEXTVAL,
        fr.intervenant_id,
        fr.type_volume_horaire_id,
        fr.etat_volume_horaire_id,
        ROUND( fr.service_du, arrondi ),
        ROUND( fr.service_fi, arrondi ),
        ROUND( fr.service_fa, arrondi ),
        ROUND( fr.service_fc, arrondi ),
        ROUND( fr.service_referentiel, arrondi ),
        ROUND( fr.heures_compl_fi, arrondi ),
        ROUND( fr.heures_compl_fa, arrondi ),
        ROUND( fr.heures_compl_fc, arrondi ),
        ROUND( fr.heures_compl_fc_majorees, arrondi ),
        ROUND( fr.heures_compl_referentiel, arrondi ),
        ROUND( fr.total, arrondi ),
        ROUND( fr.solde, arrondi ),
        ROUND( fr.sous_service, arrondi ),
        ROUND( fr.heures_compl, arrondi ),
        0,
        ti_code
      );

      SELECT id INTO id FROM formule_resultat tfr WHERE
          tfr.intervenant_id         = fr.intervenant_id
                                                    AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
                                                    AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id;
      RETURN id;
    END;


  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    BEGIN
      MERGE INTO formule_resultat_service tfs USING dual ON (

        tfs.formule_resultat_id    = fs.formule_resultat_id
        AND tfs.service_id             = fs.service_id

      ) WHEN MATCHED THEN UPDATE SET

        service_fi                     = ROUND( fs.service_fi, arrondi ),
        service_fa                     = ROUND( fs.service_fa, arrondi ),
        service_fc                     = ROUND( fs.service_fc, arrondi ),
        heures_compl_fi                = ROUND( fs.heures_compl_fi, arrondi ),
        heures_compl_fa                = ROUND( fs.heures_compl_fa, arrondi ),
        heures_compl_fc                = ROUND( fs.heures_compl_fc, arrondi ),
        heures_compl_fc_majorees       = ROUND( fs.heures_compl_fc_majorees, arrondi ),
        total                          = ROUND( fs.total, arrondi ),
        TO_DELETE                      = 0

      WHEN NOT MATCHED THEN INSERT (

        ID,
        FORMULE_RESULTAT_ID,
        SERVICE_ID,
        SERVICE_FI,
        SERVICE_FA,
        SERVICE_FC,
        HEURES_COMPL_FI,
        HEURES_COMPL_FA,
        HEURES_COMPL_FC,
        HEURES_COMPL_FC_majorees,
        TOTAL,
        TO_DELETE

      ) VALUES (

        FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
        fs.formule_resultat_id,
        fs.service_id,
        ROUND( fs.service_fi, arrondi ),
        ROUND( fs.service_fa, arrondi ),
        ROUND( fs.service_fc, arrondi ),
        ROUND( fs.heures_compl_fi, arrondi ),
        ROUND( fs.heures_compl_fa, arrondi ),
        ROUND( fs.heures_compl_fc, arrondi ),
        ROUND( fs.heures_compl_fc_majorees, arrondi ),
        ROUND( fs.total, arrondi ),
        0

      );

      SELECT id INTO id FROM formule_resultat_service tfs WHERE
          tfs.formule_resultat_id    = fs.formule_resultat_id
                                                            AND tfs.service_id             = fs.service_id;
      RETURN id;
    END;


  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    BEGIN
      MERGE INTO formule_resultat_vh tfvh USING dual ON (

        tfvh.formule_resultat_id    = fvh.formule_resultat_id
        AND tfvh.volume_horaire_id      = fvh.volume_horaire_id

      ) WHEN MATCHED THEN UPDATE SET

        service_fi                     = ROUND( fvh.service_fi, arrondi ),
        service_fa                     = ROUND( fvh.service_fa, arrondi ),
        service_fc                     = ROUND( fvh.service_fc, arrondi ),
        heures_compl_fi                = ROUND( fvh.heures_compl_fi, arrondi ),
        heures_compl_fa                = ROUND( fvh.heures_compl_fa, arrondi ),
        heures_compl_fc                = ROUND( fvh.heures_compl_fc, arrondi ),
        heures_compl_fc_majorees       = ROUND( fvh.heures_compl_fc_majorees, arrondi ),
        total                          = ROUND( fvh.total, arrondi ),
        TO_DELETE                      = 0

      WHEN NOT MATCHED THEN INSERT (

        ID,
        FORMULE_RESULTAT_ID,
        VOLUME_HORAIRE_ID,
        SERVICE_FI,
        SERVICE_FA,
        SERVICE_FC,
        HEURES_COMPL_FI,
        HEURES_COMPL_FA,
        HEURES_COMPL_FC,
        HEURES_COMPL_FC_MAJOREES,
        TOTAL,
        TO_DELETE

      ) VALUES (

        FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
        fvh.formule_resultat_id,
        fvh.volume_horaire_id,
        ROUND( fvh.service_fi, arrondi ),
        ROUND( fvh.service_fa, arrondi ),
        ROUND( fvh.service_fc, arrondi ),
        ROUND( fvh.heures_compl_fi, arrondi ),
        ROUND( fvh.heures_compl_fa, arrondi ),
        ROUND( fvh.heures_compl_fc, arrondi ),
        ROUND( fvh.heures_compl_fc_majorees, arrondi ),
        ROUND( fvh.total, arrondi ),
        0

      );

      SELECT id INTO id FROM formule_resultat_vh tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
                                                        AND tfvh.volume_horaire_id      = fvh.volume_horaire_id;
      RETURN id;
    END;


  FUNCTION ENREGISTRER_RESULTAT_SERV_REF( fr formule_resultat_service_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    BEGIN
      MERGE INTO formule_resultat_service_ref tfr USING dual ON (

        tfr.formule_resultat_id    = fr.formule_resultat_id
        AND tfr.service_referentiel_id = fr.service_referentiel_id

      ) WHEN MATCHED THEN UPDATE SET

        service_referentiel            = ROUND( fr.service_referentiel, arrondi ),
        heures_compl_referentiel       = ROUND( fr.heures_compl_referentiel, arrondi ),
        TO_DELETE                      = 0

      WHEN NOT MATCHED THEN INSERT (

        ID,
        FORMULE_RESULTAT_ID,
        SERVICE_REFERENTIEL_ID,
        SERVICE_REFERENTIEL,
        HEURES_COMPL_REFERENTIEL,
        TOTAL,
        TO_DELETE

      ) VALUES (

        FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
        fr.formule_resultat_id,
        fr.service_referentiel_id,
        ROUND( fr.service_referentiel, arrondi ),
        ROUND( fr.heures_compl_referentiel, arrondi ),
        fr.total,
        0

      );

      SELECT id INTO id FROM formule_resultat_service_ref tfr WHERE
          tfr.formule_resultat_id    = fr.formule_resultat_id
                                                                AND tfr.service_referentiel_id = fr.service_referentiel_id;

      RETURN id;
    END;


  FUNCTION ENREGISTRER_RESULTAT_VH_REF( fvh formule_resultat_vh_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    BEGIN
      MERGE INTO formule_resultat_vh_ref tfvh USING dual ON (

        tfvh.formule_resultat_id    = fvh.formule_resultat_id
        AND tfvh.volume_horaire_ref_id      = fvh.volume_horaire_ref_id

      ) WHEN MATCHED THEN UPDATE SET

        service_referentiel            = ROUND( fvh.service_referentiel, arrondi ),
        heures_compl_referentiel       = ROUND( fvh.heures_compl_referentiel, arrondi ),
        total                          = fvh.total,
        TO_DELETE                      = 0

      WHEN NOT MATCHED THEN INSERT (

        ID,
        FORMULE_RESULTAT_ID,
        VOLUME_HORAIRE_REF_ID,
        SERVICE_REFERENTIEL,
        HEURES_COMPL_REFERENTIEL,
        TOTAL,
        TO_DELETE

      ) VALUES (

        FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
        fvh.formule_resultat_id,
        fvh.volume_horaire_ref_id,
        ROUND( fvh.service_referentiel, arrondi ),
        ROUND( fvh.heures_compl_referentiel, arrondi ),
        fvh.total,
        0

      );

      SELECT id INTO id FROM formule_resultat_vh_ref tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
                                                            AND tfvh.volume_horaire_ref_id  = fvh.volume_horaire_ref_id;
      RETURN id;
    END;


  PROCEDURE POPULATE_INTERVENANT( INTERVENANT_ID NUMERIC, d_intervenant OUT t_intervenant ) IS
    BEGIN
      SELECT
             structure_id,
             annee_id,
             heures_service_statutaire,
             depassement_service_du_sans_hc
          INTO
            d_intervenant.structure_id,
            d_intervenant.annee_id,
            d_intervenant.heures_service_statutaire,
            d_intervenant.depassement_service_du_sans_hc
      FROM
           v_formule_intervenant fi
      WHERE
          fi.id = POPULATE_INTERVENANT.INTERVENANT_ID;

      SELECT
             NVL( SUM(heures), 0),
             NVL( SUM(heures_decharge), 0)
          INTO
            d_intervenant.heures_service_modifie,
            d_intervenant.heures_decharge
      FROM
           v_formule_service_modifie fsm
      WHERE
          fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID;

      EXCEPTION WHEN NO_DATA_FOUND THEN
      d_intervenant.structure_id := null;
      d_intervenant.annee_id := null;
      d_intervenant.heures_service_statutaire := 0;
      d_intervenant.depassement_service_du_sans_hc := 0;
      d_intervenant.heures_service_modifie := 0;
      d_intervenant.heures_decharge := 0;
    END;


  PROCEDURE POPULATE_SERVICE_REF( INTERVENANT_ID NUMERIC, d_service_ref OUT t_lst_service_ref ) IS
    i PLS_INTEGER;
    BEGIN
      d_service_ref.delete;

      FOR d IN (
      SELECT
             fr.id,
             fr.structure_id,
             fr.service_statutaire
      FROM
           v_formule_service_ref fr
      WHERE
          fr.intervenant_id = POPULATE_SERVICE_REF.INTERVENANT_ID
      ) LOOP
        d_service_ref( d.id ).id                 := d.id;
        d_service_ref( d.id ).structure_id       := d.structure_id;
        d_service_ref( d.id ).service_statutaire := d.service_statutaire = 1;
      END LOOP;
    END;


  PROCEDURE POPULATE_SERVICE( INTERVENANT_ID NUMERIC, d_service OUT t_lst_service ) IS
    BEGIN
      d_service.delete;

      FOR d IN (
      SELECT
             id,
             taux_fi,
             taux_fa,
             taux_fc,
             structure_aff_id,
             structure_ens_id,
             ponderation_service_du,
             ponderation_service_compl,
             service_statutaire
      FROM
           v_formule_service fs
      WHERE
          fs.intervenant_id = POPULATE_SERVICE.INTERVENANT_ID
      ) LOOP
        d_service( d.id ).id                        := d.id;
        d_service( d.id ).taux_fi                   := d.taux_fi;
        d_service( d.id ).taux_fa                   := d.taux_fa;
        d_service( d.id ).taux_fc                   := d.taux_fc;
        d_service( d.id ).ponderation_service_du    := d.ponderation_service_du;
        d_service( d.id ).ponderation_service_compl := d.ponderation_service_compl;
        d_service( d.id ).structure_aff_id          := d.structure_aff_id;
        d_service( d.id ).structure_ens_id          := d.structure_ens_id;
        d_service( d.id ).service_statutaire        := d.service_statutaire = 1;
      END LOOP;
    END;

  PROCEDURE POPULATE_VOLUME_HORAIRE_REF( INTERVENANT_ID NUMERIC, d_volume_horaire_ref OUT t_lst_volume_horaire_ref ) IS
    BEGIN
      d_volume_horaire_ref.delete;

      FOR d IN (
      SELECT
             id,
             service_referentiel_id,
             heures,
             fvh.type_volume_horaire_id,
             fvh.etat_volume_horaire_id,
             fvh.etat_volume_horaire_ordre
      FROM
           v_formule_volume_horaire_ref fvh
      WHERE
          fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE_REF.INTERVENANT_ID
      ) LOOP
        d_volume_horaire_ref( d.id ).id                        := d.id;
        d_volume_horaire_ref( d.id ).service_referentiel_id    := d.service_referentiel_id;
        d_volume_horaire_ref( d.id ).heures                    := d.heures;
        d_volume_horaire_ref( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
        d_volume_horaire_ref( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
        d_volume_horaire_ref( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
      END LOOP;
    END;

  PROCEDURE POPULATE_VOLUME_HORAIRE( INTERVENANT_ID NUMERIC, d_volume_horaire OUT t_lst_volume_horaire ) IS
    BEGIN
      d_volume_horaire.delete;

      FOR d IN (
      SELECT
             id,
             service_id,
             heures,
             taux_service_du,
             taux_service_compl,
             fvh.type_volume_horaire_id,
             fvh.etat_volume_horaire_id,
             fvh.etat_volume_horaire_ordre
      FROM
           v_formule_volume_horaire fvh
      WHERE
          fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE.INTERVENANT_ID
      ) LOOP
        d_volume_horaire( d.id ).id                        := d.id;
        d_volume_horaire( d.id ).service_id                := d.service_id;
        d_volume_horaire( d.id ).heures                    := d.heures;
        d_volume_horaire( d.id ).taux_service_du           := d.taux_service_du;
        d_volume_horaire( d.id ).taux_service_compl        := d.taux_service_compl;
        d_volume_horaire( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
        d_volume_horaire( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
        d_volume_horaire( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
      END LOOP;
    END;


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_volume_horaire_ref t_lst_volume_horaire_ref, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
    TYPE t_ordres IS TABLE OF NUMERIC INDEX BY PLS_INTEGER;

    ordres_found t_ordres;
    ordres_exists t_ordres;
    type_volume_horaire_id PLS_INTEGER;
    etat_volume_horaire_ordre PLS_INTEGER;
    id PLS_INTEGER;
    BEGIN
      d_type_etat_vh.delete;

      -- récupération des ID et ordres de volumes horaires
      FOR evh IN (
      SELECT   id, ordre
      FROM     etat_volume_horaire evh
      ORDER BY ordre
      ) LOOP
        ordres_exists( evh.ordre ) := evh.id;
      END LOOP;

      -- récupération des ordres maximum par type d'intervention
      id := d_volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        IF NOT ordres_found.EXISTS(d_volume_horaire(id).type_volume_horaire_id) THEN
          ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
        ELSIF ordres_found( d_volume_horaire(id).type_volume_horaire_id ) < d_volume_horaire(id).etat_volume_horaire_ordre THEN
          ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
        END IF;
        id := d_volume_horaire.NEXT(id);
      END LOOP;

      id := d_volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        IF NOT ordres_found.EXISTS(d_volume_horaire_ref(id).type_volume_horaire_id) THEN
          ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) := d_volume_horaire_ref(id).etat_volume_horaire_ordre;
        ELSIF ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) < d_volume_horaire_ref(id).etat_volume_horaire_ordre THEN
          ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) := d_volume_horaire_ref(id).etat_volume_horaire_ordre;
        END IF;
        id := d_volume_horaire_ref.NEXT(id);
      END LOOP;

      -- peuplement des t_lst_type_etat_vh
      type_volume_horaire_id := ordres_found.FIRST;
      LOOP EXIT WHEN type_volume_horaire_id IS NULL;
        etat_volume_horaire_ordre := ordres_exists.FIRST;
        LOOP EXIT WHEN etat_volume_horaire_ordre IS NULL;
          IF etat_volume_horaire_ordre <= ordres_found(type_volume_horaire_id) THEN
            d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).type_volume_horaire_id := type_volume_horaire_id;
            d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).etat_volume_horaire_id := ordres_exists( etat_volume_horaire_ordre );
          END IF;
          etat_volume_horaire_ordre := ordres_exists.NEXT(etat_volume_horaire_ordre);
        END LOOP;

        type_volume_horaire_id := ordres_found.NEXT(type_volume_horaire_id);
      END LOOP;

    END;


  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC ) IS
    BEGIN
      OSE_FORMULE.INTERVENANT_ID := POPULATE.INTERVENANT_ID;

      POPULATE_INTERVENANT    ( INTERVENANT_ID, d_intervenant );
      IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
        POPULATE_SERVICE_REF        ( INTERVENANT_ID, d_service_ref         );
        POPULATE_SERVICE            ( INTERVENANT_ID, d_service             );
        POPULATE_VOLUME_HORAIRE_REF ( INTERVENANT_ID, d_all_volume_horaire_ref  );
        POPULATE_VOLUME_HORAIRE     ( INTERVENANT_ID, d_all_volume_horaire      );
        POPULATE_TYPE_ETAT_VH       ( d_all_volume_horaire, d_all_volume_horaire_ref, d_type_etat_vh );
      END IF;
    END;


  PROCEDURE POPULATE_FILTER( TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    EVH_ORDRE NUMERIC;
    id PLS_INTEGER;
    BEGIN
      d_volume_horaire.delete;
      d_volume_horaire_ref.delete;

      SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = ETAT_VOLUME_HORAIRE_ID;

      id := d_all_volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        IF
        d_all_volume_horaire(id).type_volume_horaire_id = TYPE_VOLUME_HORAIRE_ID
        AND d_all_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE
        THEN
          d_volume_horaire(id) := d_all_volume_horaire(id);
        END IF;
        id := d_all_volume_horaire.NEXT(id);
      END LOOP;

      id := d_all_volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        IF
        d_all_volume_horaire_ref(id).type_volume_horaire_id = TYPE_VOLUME_HORAIRE_ID
        AND d_all_volume_horaire_ref(id).etat_volume_horaire_ordre >= EVH_ORDRE
        THEN
          d_volume_horaire_ref(id) := d_all_volume_horaire_ref(id);
        END IF;
        id := d_all_volume_horaire_ref.NEXT(id);
      END LOOP;
    END;


  PROCEDURE INIT_RESULTAT ( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    BEGIN
      d_resultat.intervenant_id         := INTERVENANT_ID;
      d_resultat.type_volume_horaire_id := TYPE_VOLUME_HORAIRE_ID;
      d_resultat.etat_volume_horaire_id := ETAT_VOLUME_HORAIRE_ID;
      d_resultat.service_du             := 0;
      d_resultat.solde                  := 0;
      d_resultat.sous_service           := 0;
      d_resultat.heures_compl           := 0;
      d_resultat.volume_horaire.delete;
      d_resultat.volume_horaire_ref.delete;
    END;


  PROCEDURE CALC_RESULTAT IS
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
    BEGIN
      package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
      function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

      EXECUTE IMMEDIATE
      'BEGIN ' || package_name || '.' || function_name || '( :1, :2, :3 ); END;'
      USING
      d_resultat.intervenant_id, d_resultat.type_volume_horaire_id, d_resultat.etat_volume_horaire_id;

    END;


  PROCEDURE SAVE_RESULTAT IS
    res             t_resultat_hetd;
    res_ref         t_resultat_hetd_ref;
    res_service     t_lst_resultat_hetd;
    res_service_ref t_lst_resultat_hetd_ref;
    id              PLS_INTEGER;
    sid             PLS_INTEGER;
    fr              formule_resultat%rowtype;
    frs             formule_resultat_service%rowtype;
    frsr            formule_resultat_service_ref%rowtype;
    frvh            formule_resultat_vh%rowtype;
    frvhr           formule_resultat_vh_ref%rowtype;
    dev_null        PLS_INTEGER;
    BEGIN
      -- Calcul des données pour les services et le résultat global
      fr.service_fi := 0;
      fr.service_fa := 0;
      fr.service_fc := 0;
      fr.service_referentiel := 0;
      fr.heures_compl_fi := 0;
      fr.heures_compl_fa := 0;
      fr.heures_compl_fc := 0;
      fr.heures_compl_fc_majorees := 0;
      fr.heures_compl_referentiel := 0;

      id := d_resultat.volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        sid := d_volume_horaire(id).service_id;
        IF NOT res_service.exists(sid) THEN res_service(sid).service_fi := 0; END IF;

        res_service(sid).service_fi               := res_service(sid).service_fi               + d_resultat.volume_horaire(id).service_fi;
        res_service(sid).service_fa               := res_service(sid).service_fa               + d_resultat.volume_horaire(id).service_fa;
        res_service(sid).service_fc               := res_service(sid).service_fc               + d_resultat.volume_horaire(id).service_fc;
        res_service(sid).heures_compl_fi          := res_service(sid).heures_compl_fi          + d_resultat.volume_horaire(id).heures_compl_fi;
        res_service(sid).heures_compl_fa          := res_service(sid).heures_compl_fa          + d_resultat.volume_horaire(id).heures_compl_fa;
        res_service(sid).heures_compl_fc          := res_service(sid).heures_compl_fc          + d_resultat.volume_horaire(id).heures_compl_fc;
        res_service(sid).heures_compl_fc_majorees := res_service(sid).heures_compl_fc_majorees + d_resultat.volume_horaire(id).heures_compl_fc_majorees;

        fr.service_fi                             := fr.service_fi                             + d_resultat.volume_horaire(id).service_fi;
        fr.service_fa                             := fr.service_fa                             + d_resultat.volume_horaire(id).service_fa;
        fr.service_fc                             := fr.service_fc                             + d_resultat.volume_horaire(id).service_fc;
        fr.heures_compl_fi                        := fr.heures_compl_fi                        + d_resultat.volume_horaire(id).heures_compl_fi;
        fr.heures_compl_fa                        := fr.heures_compl_fa                        + d_resultat.volume_horaire(id).heures_compl_fa;
        fr.heures_compl_fc                        := fr.heures_compl_fc                        + d_resultat.volume_horaire(id).heures_compl_fc;
        fr.heures_compl_fc_majorees               := fr.heures_compl_fc_majorees               + d_resultat.volume_horaire(id).heures_compl_fc_majorees;
        id := d_resultat.volume_horaire.NEXT(id);
      END LOOP;

      id := d_resultat.volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        sid := d_volume_horaire_ref(id).service_referentiel_id;
        IF NOT res_service_ref.exists(sid) THEN res_service_ref(sid).service_referentiel := 0; END IF;

        res_service_ref(sid).service_referentiel      := res_service_ref(sid).service_referentiel      + d_resultat.volume_horaire_ref(id).service_referentiel;
        res_service_ref(sid).heures_compl_referentiel := res_service_ref(sid).heures_compl_referentiel + d_resultat.volume_horaire_ref(id).heures_compl_referentiel;

        fr.service_referentiel                        := fr.service_referentiel                        + d_resultat.volume_horaire_ref(id).service_referentiel;
        fr.heures_compl_referentiel                   := fr.heures_compl_referentiel                   + d_resultat.volume_horaire_ref(id).heures_compl_referentiel;
        id := d_resultat.volume_horaire_ref.NEXT(id);
      END LOOP;

      -- Sauvegarde du résultat global
      fr.id                       := NULL;
      fr.intervenant_id           := d_resultat.intervenant_id;
      fr.type_volume_horaire_id   := d_resultat.type_volume_horaire_id;
      fr.etat_volume_horaire_id   := d_resultat.etat_volume_horaire_id;
      fr.service_du               := d_resultat.service_du;
      fr.total                    := fr.service_fi
                                     + fr.service_fa
                                     + fr.service_fc
                                     + fr.service_referentiel
                                     + fr.heures_compl_fi
                                     + fr.heures_compl_fa
                                     + fr.heures_compl_fc
                                     + fr.heures_compl_fc_majorees
                                     + fr.heures_compl_referentiel;
      fr.solde                    := d_resultat.solde;
      fr.sous_service             := d_resultat.sous_service;
      fr.heures_compl             := d_resultat.heures_compl;
      fr.id := OSE_FORMULE.ENREGISTRER_RESULTAT( fr );

      -- sauvegarde des services
      id := res_service.FIRST;
      LOOP EXIT WHEN id IS NULL;
        frs.id                       := NULL;
        frs.formule_resultat_id      := fr.id;
        frs.service_id               := id;
        frs.service_fi               := res_service(id).service_fi;
        frs.service_fa               := res_service(id).service_fa;
        frs.service_fc               := res_service(id).service_fc;
        frs.heures_compl_fi          := res_service(id).heures_compl_fi;
        frs.heures_compl_fa          := res_service(id).heures_compl_fa;
        frs.heures_compl_fc          := res_service(id).heures_compl_fc;
        frs.heures_compl_fc_majorees := res_service(id).heures_compl_fc_majorees;
        frs.total                    := frs.service_fi
                                        + frs.service_fa
                                        + frs.service_fc
                                        + frs.heures_compl_fi
                                        + frs.heures_compl_fa
                                        + frs.heures_compl_fc
                                        + frs.heures_compl_fc_majorees;
        dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_SERVICE( frs );
        id := res_service.NEXT(id);
      END LOOP;

      -- sauvegarde des services référentiels
      id := res_service_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        frsr.id                       := NULL;
        frsr.formule_resultat_id      := fr.id;
        frsr.service_referentiel_id   := id;
        frsr.service_referentiel      := res_service_ref(id).service_referentiel;
        frsr.heures_compl_referentiel := res_service_ref(id).heures_compl_referentiel;
        frsr.total                    := res_service_ref(id).service_referentiel
                                         + res_service_ref(id).heures_compl_referentiel;
        dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_SERV_REF( frsr );
        id := res_service_ref.NEXT(id);
      END LOOP;

      -- sauvegarde des volumes horaires
      id := d_resultat.volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        frvh.id                        := NULL;
        frvh.formule_resultat_id       := fr.id;
        frvh.volume_horaire_id         := id;
        frvh.service_fi                := d_resultat.volume_horaire(id).service_fi;
        frvh.service_fa                := d_resultat.volume_horaire(id).service_fa;
        frvh.service_fc                := d_resultat.volume_horaire(id).service_fc;
        frvh.heures_compl_fi           := d_resultat.volume_horaire(id).heures_compl_fi;
        frvh.heures_compl_fa           := d_resultat.volume_horaire(id).heures_compl_fa;
        frvh.heures_compl_fc           := d_resultat.volume_horaire(id).heures_compl_fc;
        frvh.heures_compl_fc_majorees  := d_resultat.volume_horaire(id).heures_compl_fc_majorees;
        frvh.total                     := frvh.service_fi
                                          + frvh.service_fa
                                          + frvh.service_fc
                                          + frvh.heures_compl_fi
                                          + frvh.heures_compl_fa
                                          + frvh.heures_compl_fc
                                          + frvh.heures_compl_fc_majorees;
        dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_VH( frvh );
        id := d_resultat.volume_horaire.NEXT(id);
      END LOOP;

      -- sauvegarde des volumes horaires référentiels
      id := d_resultat.volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        frvhr.id                       := NULL;
        frvhr.formule_resultat_id      := fr.id;
        frvhr.volume_horaire_ref_id    := id;
        frvhr.service_referentiel      := d_resultat.volume_horaire_ref(id).service_referentiel;
        frvhr.heures_compl_referentiel := d_resultat.volume_horaire_ref(id).heures_compl_referentiel;
        frvhr.total                    := frvhr.service_referentiel
                                          + frvhr.heures_compl_referentiel;
        dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_VH_REF( frvhr );
        id := d_resultat.volume_horaire_ref.NEXT(id);
      END LOOP;
    END;

  PROCEDURE DEBUG_INTERVENANT IS
    BEGIN
      ose_test.echo('d_intervenant');
      ose_test.echo('      .structure_id                   = ' || d_intervenant.structure_id || ' (' || ose_test.get_structure_by_id(d_intervenant.structure_id).libelle_court || ')' );
      ose_test.echo('      .heures_service_statutaire      = ' || d_intervenant.heures_service_statutaire );
      ose_test.echo('      .heures_service_modifie         = ' || d_intervenant.heures_service_modifie );
      ose_test.echo('      .depassement_service_du_sans_hc = ' || d_intervenant.depassement_service_du_sans_hc );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_SERVICE( SERVICE_ID PLS_INTEGER ) IS
    BEGIN
      ose_test.echo('d_service(' || SERVICE_ID || ')' );
      ose_test.echo('      .taux_fi                   = ' || d_service(SERVICE_ID).taux_fi );
      ose_test.echo('      .taux_fa                   = ' || d_service(SERVICE_ID).taux_fa );
      ose_test.echo('      .taux_fc                   = ' || d_service(SERVICE_ID).taux_fc );
      ose_test.echo('      .ponderation_service_du    = ' || d_service(SERVICE_ID).ponderation_service_du );
      ose_test.echo('      .ponderation_service_compl = ' || d_service(SERVICE_ID).ponderation_service_compl );
      ose_test.echo('      .structure_aff_id          = ' || d_service(SERVICE_ID).structure_aff_id || ' (' || ose_test.get_structure_by_id(d_service(SERVICE_ID).structure_aff_id).libelle_court || ')' );
      ose_test.echo('      .structure_ens_id          = ' || d_service(SERVICE_ID).structure_ens_id || ' (' || CASE WHEN d_service(SERVICE_ID).structure_ens_id IS NOT NULL THEN ose_test.get_structure_by_id(d_service(SERVICE_ID).structure_ens_id).libelle_court ELSE 'null' END || ')' );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_SERVICE_REF( SERVICE_REF_ID PLS_INTEGER ) IS
    BEGIN
      ose_test.echo('d_service_ref(' || SERVICE_REF_ID || ')' );
      ose_test.echo('      .structure_id          = ' || d_service_ref(SERVICE_REF_ID).structure_id || ' (' || ose_test.get_structure_by_id(d_service_ref(SERVICE_REF_ID).structure_id).libelle_court || ')' );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_VOLUME_HORAIRE( VH_ID PLS_INTEGER ) IS
    BEGIN
      ose_test.echo('d_volume_horaire(' || VH_ID || ')' );
      ose_test.echo('      .service_id                = ' || d_volume_horaire(VH_ID).service_id );
      ose_test.echo('      .type_volume_horaire_id    = ' || d_volume_horaire(VH_ID).type_volume_horaire_id );
      ose_test.echo('      .etat_volume_horaire_id    = ' || d_volume_horaire(VH_ID).etat_volume_horaire_id );
      ose_test.echo('      .etat_volume_horaire_ordre = ' || d_volume_horaire(VH_ID).etat_volume_horaire_ordre );
      ose_test.echo('      .heures                    = ' || d_volume_horaire(VH_ID).heures );
      ose_test.echo('      .taux_service_du           = ' || d_volume_horaire(VH_ID).taux_service_du );
      ose_test.echo('      .taux_service_compl        = ' || d_volume_horaire(VH_ID).taux_service_compl );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_VOLUME_HORAIRE_REF( VH_REF_ID PLS_INTEGER ) IS
    BEGIN
      ose_test.echo('d_volume_horaire_ref(' || VH_REF_ID || ')' );
      ose_test.echo('      .service_referentiel_id    = ' || d_volume_horaire_ref(VH_REF_ID).service_referentiel_id );
      ose_test.echo('      .type_volume_horaire_id    = ' || d_volume_horaire_ref(VH_REF_ID).type_volume_horaire_id );
      ose_test.echo('      .etat_volume_horaire_id    = ' || d_volume_horaire_ref(VH_REF_ID).etat_volume_horaire_id );
      ose_test.echo('      .etat_volume_horaire_ordre = ' || d_volume_horaire_ref(VH_REF_ID).etat_volume_horaire_ordre );
      ose_test.echo('      .heures                    = ' || d_volume_horaire_ref(VH_REF_ID).heures );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_RESULTAT IS
    BEGIN
      ose_test.echo('d_resultat' );
      ose_test.echo('      .service_du   = ' || d_resultat.service_du );
      ose_test.echo('      .solde        = ' || d_resultat.solde );
      ose_test.echo('      .sous_service = ' || d_resultat.sous_service );
      ose_test.echo('      .heures_compl = ' || d_resultat.heures_compl );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_RESULTAT_VH( VH_ID PLS_INTEGER ) IS
    BEGIN
      ose_test.echo('d_resultat.volume_horaire(' || VH_ID || ')' );
      ose_test.echo('      .service_fi                = ' || d_resultat.volume_horaire(VH_ID).service_fi );
      ose_test.echo('      .service_fa                = ' || d_resultat.volume_horaire(VH_ID).service_fa );
      ose_test.echo('      .service_fc                = ' || d_resultat.volume_horaire(VH_ID).service_fc );
      ose_test.echo('      .heures_compl_fi           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fi );
      ose_test.echo('      .heures_compl_fa           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fa );
      ose_test.echo('      .heures_compl_fc           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fc );
      ose_test.echo('      .heures_compl_fc_majorees  = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fc_majorees );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_RESULTAT_VH_REF( VH_REF_ID PLS_INTEGER ) IS
    BEGIN
      ose_test.echo('d_resultat.volume_horaire_ref(' || VH_REF_ID || ')' );
      ose_test.echo('      .service_referentiel                = ' || d_resultat.volume_horaire_ref(VH_REF_ID).service_referentiel );
      ose_test.echo('      .heures_compl_referentiel           = ' || d_resultat.volume_horaire_ref(VH_REF_ID).heures_compl_referentiel );
      ose_test.echo('');
    END;

  PROCEDURE DEBUG_ALL( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    id  PLS_INTEGER;
    i   intervenant%rowtype;
    a   annee%rowtype;
    tvh type_volume_horaire%rowtype;
    evh etat_volume_horaire%rowtype;
    BEGIN
      IF GET_DEBUG_LEVEL >= 1 THEN
        SELECT * INTO   i FROM intervenant         WHERE id = INTERVENANT_ID;
        SELECT * INTO   a FROM annee               WHERE id = i.annee_id;
        SELECT * INTO tvh FROM type_volume_horaire WHERE id = TYPE_VOLUME_HORAIRE_ID;
        SELECT * INTO evh FROM etat_volume_horaire WHERE id = ETAT_VOLUME_HORAIRE_ID;

        ose_test.echo('');
        ose_test.echo('---------------------------------------------------------------------');
        ose_test.echo('Intervenant: ' || INTERVENANT_ID || ' : ' || i.prenom || ' ' || i.nom_usuel || ' (n° harp. ' || i.source_code || ')' );
        ose_test.echo(
            'Année: ' || a.libelle
            || ', type ' || tvh.libelle
            || ', état ' || evh.libelle
        );
        ose_test.echo('');
      END IF;
      IF GET_DEBUG_LEVEL >= 2 THEN
        DEBUG_INTERVENANT;
      END IF;

      IF GET_DEBUG_LEVEL >= 5 THEN
        id := d_service.FIRST;
        LOOP EXIT WHEN id IS NULL;
          DEBUG_SERVICE( id );
          id := d_service.NEXT(id);
        END LOOP;

        id := d_service_ref.FIRST;
        LOOP EXIT WHEN id IS NULL;
          DEBUG_SERVICE_REF( id );
          id := d_service_ref.NEXT(id);
        END LOOP;
      END IF;

      IF GET_DEBUG_LEVEL >= 6 THEN
        id := d_volume_horaire.FIRST;
        LOOP EXIT WHEN id IS NULL;
          DEBUG_VOLUME_HORAIRE( id );
          id := d_volume_horaire.NEXT(id);
        END LOOP;

        id := d_volume_horaire_ref.FIRST;
        LOOP EXIT WHEN id IS NULL;
          DEBUG_VOLUME_HORAIRE_REF( id );
          id := d_volume_horaire_ref.NEXT(id);
        END LOOP;
      END IF;

      IF GET_DEBUG_LEVEL >= 3 THEN
        DEBUG_RESULTAT;
      END IF;

      IF GET_DEBUG_LEVEL >= 4 THEN
        id := d_resultat.volume_horaire.FIRST;
        LOOP EXIT WHEN id IS NULL;
          DEBUG_RESULTAT_VH( id );
          id := d_resultat.volume_horaire.NEXT(id);
        END LOOP;

        id := d_resultat.volume_horaire_ref.FIRST;
        LOOP EXIT WHEN id IS NULL;
          DEBUG_RESULTAT_VH_REF( id );
          id := d_resultat.volume_horaire_ref.NEXT(id);
        END LOOP;
      END IF;
    END;



  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
    id PLS_INTEGER;
    found BOOLEAN;
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
    BEGIN
      package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
      function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

      -- détection de suppression des lignes de résultat obsolètes
      UPDATE formule_resultat SET TO_DELETE = 1 WHERE intervenant_id = CALCULER.INTERVENANT_ID;
      UPDATE FORMULE_RESULTAT_SERVICE_REF SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      UPDATE FORMULE_RESULTAT_SERVICE     SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      UPDATE FORMULE_RESULTAT_VH_REF      SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      UPDATE FORMULE_RESULTAT_VH          SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);

      POPULATE( INTERVENANT_ID );
      IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
        -- lancement du calcul sur les nouvelles lignes ou sur les lignes existantes
        id := d_type_etat_vh.FIRST;
        LOOP EXIT WHEN id IS NULL;
          POPULATE_FILTER( d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
          DEBUG_ALL( INTERVENANT_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
          OSE_FORMULE.INIT_RESULTAT( INTERVENANT_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
          OSE_FORMULE.CALC_RESULTAT;
          OSE_FORMULE.SAVE_RESULTAT;
          id := d_type_etat_vh.NEXT(id);
        END LOOP;
      END IF;

      -- suppression des données devenues obsolètes
      OSE_EVENT.ON_BEFORE_FORMULE_RES_DELETE( CALCULER.INTERVENANT_ID );

      UPDATE FORMULE_RESULTAT_SERVICE SET
                                          to_delete = 0,
                                          service_fi = 0,
                                          service_fa = 0,
                                          service_fc = 0,
                                          heures_compl_fi = 0,
                                          heures_compl_fa = 0,
                                          heures_compl_fc = 0,
                                          heures_compl_fc_majorees = 0,
                                          total = 0
      WHERE
          TO_DELETE = 1
        AND 0 < (SELECT COUNT(*) FROM mise_en_paiement mep WHERE mep.formule_res_service_id = FORMULE_RESULTAT_SERVICE.id)
        AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);

      DELETE FROM FORMULE_RESULTAT_SERVICE_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      DELETE FROM FORMULE_RESULTAT_VH_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
      DELETE FROM FORMULE_RESULTAT WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID;

      OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
    END;



  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    intervenant_id NUMERIC;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
    BEGIN
      OPEN diff_cur FOR 'WITH interv AS (SELECT id intervenant_id, intervenant.* FROM intervenant)
    SELECT intervenant_id FROM interv WHERE ' || unicaen_tbl.PARAMS_TO_CONDS( params );
      LOOP
        FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
        BEGIN
          CALCULER( intervenant_id );
        END;
      END LOOP;
      CLOSE diff_cur;
    END;



  FUNCTION GET_INTERVENANT RETURN NUMERIC IS
    BEGIN
      RETURN OSE_FORMULE.INTERVENANT_ID;
    END;

  PROCEDURE SET_INTERVENANT( INTERVENANT_ID NUMERIC DEFAULT NULL) IS
    BEGIN
      IF SET_INTERVENANT.INTERVENANT_ID = -1 THEN
        OSE_FORMULE.INTERVENANT_ID := NULL;
      ELSE
        OSE_FORMULE.INTERVENANT_ID := SET_INTERVENANT.INTERVENANT_ID;
      END IF;
    END;

  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC IS
    BEGIN
      IF OSE_FORMULE.INTERVENANT_ID IS NULL OR OSE_FORMULE.INTERVENANT_ID = MATCH_INTERVENANT.INTERVENANT_ID THEN
        RETURN 1;
      ELSE
        RETURN 0;
      END IF;
    END;
END OSE_FORMULE;
/



CREATE OR REPLACE PACKAGE BODY UNICAEN_TBL AS

  FUNCTION MAKE_PARAMS(
    c1 VARCHAR2 DEFAULT NULL, v1 VARCHAR2 DEFAULT NULL,
    c2 VARCHAR2 DEFAULT NULL, v2 VARCHAR2 DEFAULT NULL,
    c3 VARCHAR2 DEFAULT NULL, v3 VARCHAR2 DEFAULT NULL,
    c4 VARCHAR2 DEFAULT NULL, v4 VARCHAR2 DEFAULT NULL,
    c5 VARCHAR2 DEFAULT NULL, v5 VARCHAR2 DEFAULT NULL,
    sqlcond CLOB DEFAULT NULL
  ) RETURN t_params IS
    params t_params;
    BEGIN
      IF c1 IS NOT NULL THEN
        params.c1 := c1;
        params.v1 := v1;
      END IF;
      IF c2 IS NOT NULL THEN
        params.c2 := c2;
        params.v2 := v2;
      END IF;
      IF c3 IS NOT NULL THEN
        params.c3 := c3;
        params.v3 := v3;
      END IF;
      IF c4 IS NOT NULL THEN
        params.c4 := c4;
        params.v4 := v4;
      END IF;
      IF c5 IS NOT NULL THEN
        params.c5 := c5;
        params.v5 := v5;
      END IF;
      params.sqlcond := sqlcond;

      RETURN params;
    END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2 ) IS
    p t_params;
    BEGIN
      DEMANDE_CALCUL( tbl_name, p );
    END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, CONDS CLOB ) IS
    p t_params;
    BEGIN
      p.sqlcond := CONDS;
      DEMANDE_CALCUL( tbl_name, p );
    END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    BEGIN
      INSERT INTO tbl_dems (
          ID,
          TBL_NAME,
          c1, v1,
          c2, v2,
          c3, v3,
          c4, v4,
          c5, v5,
          sqlcond
          ) VALUES (
                       TBL_DEMS_ID_SEQ.NEXTVAL,
                       TBL_NAME,
                       PARAMS.c1, PARAMS.v1,
                       PARAMS.c2, PARAMS.v2,
                       PARAMS.c3, PARAMS.v3,
                       PARAMS.c4, PARAMS.v4,
                       PARAMS.c5, PARAMS.v5,
                       PARAMS.sqlcond
                       );
    END;



  FUNCTION PARAMS_FROM_DEMS( TBL_NAME VARCHAR2 ) RETURN t_params IS
    res t_params;
    conds CLOB := '';
    cond CLOB;
    BEGIN
      FOR d IN (
      SELECT *
      FROM   tbl_dems
      WHERE  tbl_name = PARAMS_FROM_DEMS.TBL_NAME
      )
      LOOP

        cond := '';

        IF d.c1 IS NOT NULL THEN
          IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
          IF d.v1 IS NULL THEN
            cond := cond || d.c1 || ' IS NULL';
          ELSE
            cond := cond || d.c1 || '=' || d.v1;
          END IF;
        END IF;

        IF d.c2 IS NOT NULL THEN
          IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
          IF d.v2 IS NULL THEN
            cond := cond || d.c2 || ' IS NULL';
          ELSE
            cond := cond || d.c2 || '=' || d.v2;
          END IF;
        END IF;

        IF d.c3 IS NOT NULL THEN
          IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
          IF d.v3 IS NULL THEN
            cond := cond || d.c3 || ' IS NULL';
          ELSE
            cond := cond || d.c3 || '=' || d.v3;
          END IF;
        END IF;

        IF d.c4 IS NOT NULL THEN
          IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
          IF d.v4 IS NULL THEN
            cond := cond || d.c4 || ' IS NULL';
          ELSE
            cond := cond || d.c4 || '=' || d.v4;
          END IF;
        END IF;

        IF d.c5 IS NOT NULL THEN
          IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
          IF d.v5 IS NULL THEN
            cond := cond || d.c5 || ' IS NULL';
          ELSE
            cond := cond || d.c5 || '=' || d.v5;
          END IF;
        END IF;

        IF d.sqlcond IS NOT NULL THEN
          IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
          cond := cond || '(' || d.sqlcond || ')';
        END IF;

        IF conds IS NOT NULL THEN
          conds := conds || ' OR ';
        END IF;
        conds := conds || '(' || cond || ')';
      END LOOP;

      res.sqlcond := conds;
      DELETE FROM tbl_dems WHERE tbl_name = PARAMS_FROM_DEMS.TBL_NAME;
      RETURN res;
    END;



  FUNCTION PARAMS_TO_CONDS ( PARAMS UNICAEN_TBL.T_PARAMS ) RETURN CLOB IS
    cond CLOB;
    BEGIN
      IF params.c1 IS NOT NULL THEN
        IF params.v1 IS NULL THEN
          cond := cond || params.c1 || ' IS NULL';
        ELSE
          cond := cond || params.c1 || '=' || params.v1;
        END IF;
      END IF;

      IF params.c2 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF params.v2 IS NULL THEN
          cond := cond || params.c2 || ' IS NULL';
        ELSE
          cond := cond || params.c2 || '=' || params.v2;
        END IF;
      END IF;

      IF params.c3 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF params.v3 IS NULL THEN
          cond := cond || params.c3 || ' IS NULL';
        ELSE
          cond := cond || params.c3 || '=' || params.v3;
        END IF;
      END IF;

      IF params.c4 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF params.v4 IS NULL THEN
          cond := cond || params.c4 || ' IS NULL';
        ELSE
          cond := cond || params.c4 || '=' || params.v4;
        END IF;
      END IF;

      IF params.c5 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF params.v5 IS NULL THEN
          cond := cond || params.c5 || ' IS NULL';
        ELSE
          cond := cond || params.c5 || '=' || params.v5;
        END IF;
      END IF;

      IF params.sqlcond IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        cond := cond || '(' || params.sqlcond || ')';
      END IF;

      IF cond IS NULL THEN cond := '1=1'; END IF;

      RETURN cond;
    END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2 ) IS
    p t_params;
    BEGIN
      ANNULER_DEMANDES( TBL_NAME );
      CALCULER(TBL_NAME, p);
    END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, CONDS CLOB ) IS
    p t_params;
    BEGIN
      p.sqlcond := CONDS;
      CALCULER(TBL_NAME, p);
    END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    calcul_proc varchar2(30);
    BEGIN
      IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

      SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER.TBL_NAME;

      UNICAEN_TBL.CALCUL_PROC_PARAMS := PARAMS;
      IF calcul_proc IS NOT NULL THEN
        EXECUTE IMMEDIATE
        'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
        ;
      ELSE
        EXECUTE IMMEDIATE
        'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
        ;
      END IF;

    END;



  PROCEDURE ANNULER_DEMANDES IS
    BEGIN
      DELETE FROM tbl_dems;
    END;



  PROCEDURE ANNULER_DEMANDES( TBL_NAME VARCHAR2 ) IS
    BEGIN
      DELETE FROM tbl_dems WHERE tbl_name = ANNULER_DEMANDES.tbl_name;
    END;



  FUNCTION HAS_DEMANDES RETURN BOOLEAN IS
    has_dems NUMERIC;
    BEGIN
      SELECT count(*) INTO has_dems from tbl_dems where rownum = 1;

      RETURN has_dems = 1;
    END;



  PROCEDURE CALCULER_DEMANDES IS
    dems t_params;
    BEGIN
      FOR d IN (
      SELECT DISTINCT tbl_name FROM tbl_dems
      ) LOOP
        dems := PARAMS_FROM_DEMS( d.tbl_name );
        calculer( d.tbl_name, dems );
      END LOOP;

      IF HAS_DEMANDES THEN -- pour les boucles !!
        CALCULER_DEMANDES;
      END IF;
    END;



  -- AUTOMATIC GENERATION --

  PROCEDURE C_AGREMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_AGREMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_AGREMENT t
    USING (

      SELECT
        tv.*
      FROM
        (WITH i_s AS (
          SELECT DISTINCT
            fr.intervenant_id,
            ep.structure_id
          FROM
            formule_resultat fr
            JOIN type_volume_horaire  tvh ON tvh.code = ''PREVU'' AND tvh.id = fr.type_volume_horaire_id
            JOIN etat_volume_horaire  evh ON evh.code = ''valide'' AND evh.id = fr.etat_volume_horaire_id

            JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
            JOIN service s ON s.id = frs.service_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          WHERE
            frs.total > 0
        )
        SELECT
          i.annee_id              annee_id,
          tas.type_agrement_id    type_agrement_id,
          i.id                    intervenant_id,
          null                    structure_id,
          tas.obligatoire         obligatoire,
          a.id                    agrement_id
        FROM
          type_agrement                  ta
          JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                            AND tas.histo_destruction IS NULL

          JOIN intervenant                 i ON i.histo_destruction IS NULL
                                            AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                            AND i.statut_id = tas.statut_intervenant_id

          LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                            AND a.intervenant_id = i.id
                                            AND a.histo_destruction IS NULL
        WHERE
          ta.code = ''CONSEIL_ACADEMIQUE''

        UNION ALL

        SELECT
          i.annee_id              annee_id,
          tas.type_agrement_id    type_agrement_id,
          i.id                    intervenant_id,
          i_s.structure_id        structure_id,
          tas.obligatoire         obligatoire,
          a.id                    agrement_id
        FROM
          type_agrement                   ta
          JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                            AND tas.histo_destruction IS NULL

          JOIN intervenant                 i ON i.histo_destruction IS NULL
                                            AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                            AND i.statut_id = tas.statut_intervenant_id

          JOIN                           i_s ON i_s.intervenant_id = i.id

          LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                            AND a.intervenant_id = i.id
                                            AND a.structure_id = i_s.structure_id
                                            AND a.histo_destruction IS NULL
        WHERE
          ta.code = ''CONSEIL_RESTREINT'') tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_AGREMENT_ID = v.TYPE_AGREMENT_ID
        AND t.INTERVENANT_ID   = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID         = v.ANNEE_ID,
      OBLIGATOIRE      = v.OBLIGATOIRE,
      AGREMENT_ID      = v.AGREMENT_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_AGREMENT_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      OBLIGATOIRE,
      AGREMENT_ID,
      TO_DELETE

    ) VALUES (

      TBL_AGREMENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_AGREMENT_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.OBLIGATOIRE,
      v.AGREMENT_ID,
      0

    );

    DELETE TBL_AGREMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_CHARGENS( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CHARGENS SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CHARGENS t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
        SELECT
          n.annee_id                        annee_id,
          n.noeud_id                        noeud_id,
          sn.scenario_id                    scenario_id,
          sne.type_heures_id                type_heures_id,
          ti.id                             type_intervention_id,

          n.element_pedagogique_id          element_pedagogique_id,
          n.element_pedagogique_etape_id    etape_id,
          sne.etape_id                      etape_ens_id,
          n.structure_id                    structure_id,
          n.groupe_type_formation_id        groupe_type_formation_id,

          vhe.heures                        heures,
          vhe.heures * ti.taux_hetd_service hetd,

          GREATEST(COALESCE(sns.ouverture, 1),1)                                           ouverture,
          GREATEST(COALESCE(sns.dedoublement, snsetp.dedoublement, csdd.dedoublement,1),1) dedoublement,
          COALESCE(sns.assiduite,1)                                                        assiduite,
          sne.effectif*COALESCE(sns.assiduite,1)                                           effectif,

          SUM(sne.effectif*COALESCE(sns.assiduite,1)) OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id) t_effectif

        FROM
                    scenario_noeud_effectif    sne
               JOIN etape                        e ON e.id = sne.etape_id
                                                  AND e.histo_destruction IS NULL

               JOIN scenario_noeud              sn ON sn.id = sne.scenario_noeud_id
                                                  AND sn.histo_destruction IS NULL

               JOIN tbl_noeud                       n ON n.noeud_id = sn.noeud_id

               JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                                  AND vhe.histo_destruction IS NULL
                                                  AND vhe.heures > 0

               JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

          LEFT JOIN tbl_noeud                 netp ON netp.etape_id = e.id

          LEFT JOIN scenario_noeud           snetp ON snetp.scenario_id = sn.scenario_id
                                                  AND snetp.noeud_id = netp.noeud_id
                                                  AND snetp.histo_destruction IS NULL

          LEFT JOIN scenario_noeud_seuil    snsetp ON snsetp.scenario_noeud_id = snetp.id
                                                  AND snsetp.type_intervention_id = ti.id

          LEFT JOIN tbl_chargens_seuils_def   csdd ON csdd.annee_id = n.annee_id
                                                  AND csdd.scenario_id = sn.scenario_id
                                                  AND csdd.type_intervention_id = ti.id
                                                  AND csdd.groupe_type_formation_id = n.groupe_type_formation_id
                                                  AND csdd.structure_id = n.structure_id

          LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id
                                                  AND sns.type_intervention_id = ti.id
        )
        SELECT
          annee_id,
          noeud_id,
          scenario_id,
          type_heures_id,
          type_intervention_id,

          element_pedagogique_id,
          etape_id,
          etape_ens_id,
          structure_id,
          groupe_type_formation_id,

          ouverture,
          dedoublement,
          assiduite,
          effectif,
          heures heures_ens,
          --t_effectif,

          CASE WHEN t_effectif < ouverture THEN 0 ELSE
            CEIL( t_effectif / dedoublement ) * effectif / t_effectif
          END groupes,

          CASE WHEN t_effectif < ouverture THEN 0 ELSE
            CEIL( t_effectif / dedoublement ) * heures * effectif / t_effectif
          END heures,

          CASE WHEN t_effectif < ouverture THEN 0 ELSE
            CEIL( t_effectif / dedoublement ) * hetd * effectif / t_effectif
          END  hetd

        FROM
          t) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID                 = v.ANNEE_ID
        AND t.NOEUD_ID                 = v.NOEUD_ID
        AND t.SCENARIO_ID              = v.SCENARIO_ID
        AND t.TYPE_HEURES_ID           = v.TYPE_HEURES_ID
        AND t.TYPE_INTERVENTION_ID     = v.TYPE_INTERVENTION_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID   = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.ETAPE_ID                 = v.ETAPE_ID
        AND t.ETAPE_ENS_ID             = v.ETAPE_ENS_ID
        AND t.STRUCTURE_ID             = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID = v.GROUPE_TYPE_FORMATION_ID

    ) WHEN MATCHED THEN UPDATE SET

      OUVERTURE                = v.OUVERTURE,
      DEDOUBLEMENT             = v.DEDOUBLEMENT,
      ASSIDUITE                = v.ASSIDUITE,
      EFFECTIF                 = v.EFFECTIF,
      HEURES_ENS               = v.HEURES_ENS,
      GROUPES                  = v.GROUPES,
      HEURES                   = v.HEURES,
      HETD                     = v.HETD,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      NOEUD_ID,
      SCENARIO_ID,
      TYPE_HEURES_ID,
      TYPE_INTERVENTION_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ETAPE_ENS_ID,
      STRUCTURE_ID,
      GROUPE_TYPE_FORMATION_ID,
      OUVERTURE,
      DEDOUBLEMENT,
      ASSIDUITE,
      EFFECTIF,
      HEURES_ENS,
      GROUPES,
      HEURES,
      HETD,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.NOEUD_ID,
      v.SCENARIO_ID,
      v.TYPE_HEURES_ID,
      v.TYPE_INTERVENTION_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.ETAPE_ID,
      v.ETAPE_ENS_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.OUVERTURE,
      v.DEDOUBLEMENT,
      v.ASSIDUITE,
      v.EFFECTIF,
      v.HEURES_ENS,
      v.GROUPES,
      v.HEURES,
      v.HETD,
      0

    );

    DELETE TBL_CHARGENS WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_CHARGENS_SEUILS_DEF( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CHARGENS_SEUILS_DEF SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CHARGENS_SEUILS_DEF t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          sta.annee_id,
          sta.scenario_id,
          s.structure_id,
          gtf.groupe_type_formation_id,
          sta.type_intervention_id,
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement) dedoublement
        FROM
          (SELECT DISTINCT scenario_id, type_intervention_id, annee_id FROM seuil_charge WHERE histo_destruction IS NULL) sta
          JOIN (SELECT DISTINCT structure_id FROM noeud WHERE structure_id IS NOT NULL) s ON 1=1
          JOIN (SELECT id groupe_type_formation_id FROM groupe_type_formation) gtf ON 1=1

          LEFT JOIN seuil_charge sc1 ON
            sc1.histo_destruction            IS NULL
            AND sc1.annee_id                 = sta.annee_id
            AND sc1.scenario_id              = sta.scenario_id
            AND sc1.type_intervention_id     = sta.type_intervention_id
            AND sc1.structure_id             = s.structure_id
            AND sc1.groupe_type_formation_id = gtf.groupe_type_formation_id

          LEFT JOIN seuil_charge sc2 ON
            sc2.histo_destruction            IS NULL
            AND sc2.annee_id                 = sta.annee_id
            AND sc2.scenario_id              = sta.scenario_id
            AND sc2.type_intervention_id     = sta.type_intervention_id
            AND sc2.structure_id             = s.structure_id
            AND sc2.groupe_type_formation_id IS NULL

          LEFT JOIN seuil_charge sc3 ON
            sc3.histo_destruction            IS NULL
            AND sc3.annee_id                 = sta.annee_id
            AND sc3.scenario_id              = sta.scenario_id
            AND sc3.type_intervention_id     = sta.type_intervention_id
            AND sc3.structure_id             IS NULL
            AND sc3.groupe_type_formation_id = gtf.groupe_type_formation_id

          LEFT JOIN seuil_charge sc4 ON
            sc4.histo_destruction            IS NULL
            AND sc4.annee_id                 = sta.annee_id
            AND sc4.scenario_id              = sta.scenario_id
            AND sc4.type_intervention_id     = sta.type_intervention_id
            AND sc4.structure_id             IS NULL
            AND sc4.groupe_type_formation_id IS NULL
        WHERE
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement, 1) <> 1) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID                 = v.ANNEE_ID
        AND t.SCENARIO_ID              = v.SCENARIO_ID
        AND t.STRUCTURE_ID             = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID = v.GROUPE_TYPE_FORMATION_ID
        AND t.TYPE_INTERVENTION_ID     = v.TYPE_INTERVENTION_ID

    ) WHEN MATCHED THEN UPDATE SET

      DEDOUBLEMENT             = v.DEDOUBLEMENT,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      SCENARIO_ID,
      STRUCTURE_ID,
      GROUPE_TYPE_FORMATION_ID,
      TYPE_INTERVENTION_ID,
      DEDOUBLEMENT,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_SEUILS_DEF_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.SCENARIO_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.TYPE_INTERVENTION_ID,
      v.DEDOUBLEMENT,
      0

    );

    DELETE TBL_CHARGENS_SEUILS_DEF WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_CLOTURE_REALISE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CLOTURE_REALISE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CLOTURE_REALISE t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
          SELECT
            i.annee_id              annee_id,
            i.id                    intervenant_id,
            si.peut_cloturer_saisie peut_cloturer_saisie,
            CASE WHEN v.id IS NULL THEN 0 ELSE 1 END cloture
          FROM
                      intervenant         i
                 JOIN statut_intervenant si ON si.id = i.statut_id
                 JOIN type_validation    tv ON tv.code = ''CLOTURE_REALISE''

            LEFT JOIN validation          v ON v.intervenant_id = i.id
                                           AND v.type_validation_id = tv.id
                                           AND v.histo_destruction IS NULL

          WHERE
            i.histo_destruction IS NULL
        )
        SELECT
          annee_id,
          intervenant_id,
          peut_cloturer_saisie,
          CASE WHEN sum(cloture) = 0 THEN 0 ELSE 1 END cloture
        FROM
          t
        GROUP BY
          annee_id,
          intervenant_id,
          peut_cloturer_saisie) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      PEUT_CLOTURER_SAISIE = v.PEUT_CLOTURER_SAISIE,
      CLOTURE              = v.CLOTURE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_CLOTURER_SAISIE,
      CLOTURE,
      TO_DELETE

    ) VALUES (

      TBL_CLOTURE_REALISE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_CLOTURER_SAISIE,
      v.CLOTURE,
      0

    );

    DELETE TBL_CLOTURE_REALISE WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_CONTRAT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CONTRAT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CONTRAT t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
          SELECT
            i.annee_id                                                                annee_id,
            i.id                                                                      intervenant_id,
            si.peut_avoir_contrat                                                     peut_avoir_contrat,
            NVL(ep.structure_id, i.structure_id)                                      structure_id,
            CASE WHEN evh.code IN (''contrat-edite'',''contrat-signe'') THEN 1 ELSE 0 END edite,
            CASE WHEN evh.code IN (''contrat-signe'')                 THEN 1 ELSE 0 END signe
          FROM
                      intervenant                 i

                 JOIN statut_intervenant         si ON si.id = i.statut_id

                 JOIN service                     s ON s.intervenant_id = i.id
                                                   AND s.histo_destruction IS NULL

                 JOIN type_volume_horaire       tvh ON tvh.code = ''PREVU''

                 JOIN volume_horaire             vh ON vh.service_id = s.id
                                                   AND vh.histo_destruction IS NULL
                                                   AND vh.heures <> 0
                                                   AND vh.type_volume_horaire_id = tvh.id

                 JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id

                 JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                                   AND evh.code IN (''valide'', ''contrat-edite'', ''contrat-signe'')

                 JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id

          WHERE
            i.histo_destruction IS NULL
            AND NOT (si.peut_avoir_contrat = 0 AND evh.code = ''valide'')

          UNION ALL

          SELECT
            i.annee_id                                                                annee_id,
            i.id                                                                      intervenant_id,
            si.peut_avoir_contrat                                                     peut_avoir_contrat,
            s.structure_id                                                            structure_id,
            CASE WHEN evh.code IN (''contrat-edite'',''contrat-signe'') THEN 1 ELSE 0 END edite,
            CASE WHEN evh.code IN (''contrat-signe'')                 THEN 1 ELSE 0 END signe
          FROM
                      intervenant                 i

                 JOIN statut_intervenant         si ON si.id = i.statut_id

                 JOIN service_referentiel         s ON s.intervenant_id = i.id
                                                   AND s.histo_destruction IS NULL

                 JOIN type_volume_horaire       tvh ON tvh.code = ''PREVU''

                 JOIN volume_horaire_ref         vh ON vh.service_referentiel_id = s.id
                                                   AND vh.histo_destruction IS NULL
                                                   AND vh.heures <> 0
                                                   AND vh.type_volume_horaire_id = tvh.id

                 JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id

                 JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                                   AND evh.code IN (''valide'', ''contrat-edite'', ''contrat-signe'')

          WHERE
            i.histo_destruction IS NULL
            AND NOT (si.peut_avoir_contrat = 0 AND evh.code = ''valide'')
        )
        SELECT
          annee_id,
          intervenant_id,
          peut_avoir_contrat,
          structure_id,
          count(*) as nbvh,
          sum(edite) as edite,
          sum(signe) as signe
        FROM
          t
        GROUP BY
          annee_id,
          intervenant_id,
          peut_avoir_contrat,
          structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID           = v.ANNEE_ID,
      PEUT_AVOIR_CONTRAT = v.PEUT_AVOIR_CONTRAT,
      NBVH               = v.NBVH,
      EDITE              = v.EDITE,
      SIGNE              = v.SIGNE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_AVOIR_CONTRAT,
      STRUCTURE_ID,
      NBVH,
      EDITE,
      SIGNE,
      TO_DELETE

    ) VALUES (

      TBL_CONTRAT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_AVOIR_CONTRAT,
      v.STRUCTURE_ID,
      v.NBVH,
      v.EDITE,
      v.SIGNE,
      0

    );

    DELETE TBL_CONTRAT WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_DMEP_LIQUIDATION( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_DMEP_LIQUIDATION SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_DMEP_LIQUIDATION t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          annee_id,
          type_ressource_id,
          structure_id,
          SUM(heures) heures
        FROM
        (
          SELECT
            i.annee_id,
            cc.type_ressource_id,
            COALESCE( ep.structure_id, i.structure_id ) structure_id,
            mep.heures
          FROM
                      mise_en_paiement         mep
                 JOIN centre_cout               cc ON cc.id = mep.centre_cout_id
                 JOIN formule_resultat_service frs ON frs.id = mep.formule_res_service_id
                 JOIN service                    s ON s.id = frs.service_id
                 JOIN intervenant                i ON i.id = s.intervenant_id
            LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
          WHERE
            mep.histo_destruction IS NULL

          UNION ALL

          SELECT
            i.annee_id,
            cc.type_ressource_id,
            sr.structure_id structure_id,
            heures
          FROM
                      mise_en_paiement              mep
                 JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
                 JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
                 JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
                 JOIN intervenant                     i ON i.id = sr.intervenant_id

          WHERE
            mep.histo_destruction IS NULL

        ) t1
        GROUP BY
          annee_id, type_ressource_id, structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID          = v.ANNEE_ID
        AND t.TYPE_RESSOURCE_ID = v.TYPE_RESSOURCE_ID
        AND t.STRUCTURE_ID      = v.STRUCTURE_ID

    ) WHEN MATCHED THEN UPDATE SET

      HEURES            = v.HEURES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_RESSOURCE_ID,
      STRUCTURE_ID,
      HEURES,
      TO_DELETE

    ) VALUES (

      TBL_DMEP_LIQUIDATION_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_RESSOURCE_ID,
      v.STRUCTURE_ID,
      v.HEURES,
      0

    );

    DELETE TBL_DMEP_LIQUIDATION WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_DOSSIER( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_DOSSIER SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_DOSSIER t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_dossier,
          d.id dossier_id,
          v.id validation_id
        FROM
                    intervenant         i
               JOIN statut_intervenant si ON si.id = i.statut_id
          LEFT JOIN dossier             d ON d.intervenant_id = i.id
                                      AND d.histo_destruction IS NULL

               JOIN type_validation tv ON tv.code = ''DONNEES_PERSO_PAR_COMP''
          LEFT JOIN validation       v ON v.intervenant_id = i.id
                                      AND v.type_validation_id = tv.id
                                      AND v.histo_destruction IS NULL
        WHERE
          i.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID            = v.ANNEE_ID,
      PEUT_SAISIR_DOSSIER = v.PEUT_SAISIR_DOSSIER,
      DOSSIER_ID          = v.DOSSIER_ID,
      VALIDATION_ID       = v.VALIDATION_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_DOSSIER,
      DOSSIER_ID,
      VALIDATION_ID,
      TO_DELETE

    ) VALUES (

      TBL_DOSSIER_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_DOSSIER,
      v.DOSSIER_ID,
      v.VALIDATION_ID,
      0

    );

    DELETE TBL_DOSSIER WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_PAIEMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PAIEMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PAIEMENT t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id                                  annee_id,
          frs.id                                      formule_res_service_id,
          null                                        formule_res_service_ref_id,
          i.id                                        intervenant_id,
          COALESCE( ep.structure_id, i.structure_id ) structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
          count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          NVL(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
        FROM
                    formule_resultat_service        frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id

               JOIN intervenant                       i ON i.id = fr.intervenant_id
               JOIN service                           s ON s.id = frs.service_id
          LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                                       AND mep.histo_destruction IS NULL

        UNION ALL

        SELECT
          i.annee_id                                  annee_id,
          null                                        formule_res_service_id,
          frs.id                                      formule_res_service_ref_id,
          i.id                                        intervenant_id,
          s.structure_id                              structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          frs.heures_compl_referentiel                heures_a_payer,
          count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          NVL(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
        FROM
                    formule_resultat_service_ref    frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id

               JOIN intervenant                       i ON i.id = fr.intervenant_id
               JOIN service_referentiel               s ON s.id = frs.service_referentiel_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                                       AND mep.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID             = v.INTERVENANT_ID
        AND COALESCE(t.MISE_EN_PAIEMENT_ID,0) = COALESCE(v.MISE_EN_PAIEMENT_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_REF_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_REF_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                   = v.ANNEE_ID,
      STRUCTURE_ID               = v.STRUCTURE_ID,
      PERIODE_PAIEMENT_ID        = v.PERIODE_PAIEMENT_ID,
      HEURES_A_PAYER             = v.HEURES_A_PAYER,
      HEURES_A_PAYER_POND        = v.HEURES_A_PAYER_POND,
      HEURES_DEMANDEES           = v.HEURES_DEMANDEES,
      HEURES_PAYEES              = v.HEURES_PAYEES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      MISE_EN_PAIEMENT_ID,
      PERIODE_PAIEMENT_ID,
      HEURES_A_PAYER,
      HEURES_A_PAYER_POND,
      HEURES_DEMANDEES,
      HEURES_PAYEES,
      FORMULE_RES_SERVICE_ID,
      FORMULE_RES_SERVICE_REF_ID,
      TO_DELETE

    ) VALUES (

      TBL_PAIEMENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.MISE_EN_PAIEMENT_ID,
      v.PERIODE_PAIEMENT_ID,
      v.HEURES_A_PAYER,
      v.HEURES_A_PAYER_POND,
      v.HEURES_DEMANDEES,
      v.HEURES_PAYEES,
      v.FORMULE_RES_SERVICE_ID,
      v.FORMULE_RES_SERVICE_REF_ID,
      0

    );

    DELETE TBL_PAIEMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_PIECE_JOINTE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PIECE_JOINTE t
    USING (

      SELECT
        tv.*
      FROM
        (WITH pjf AS (
          SELECT
            pjf.annee_id,
            pjf.type_piece_jointe_id,
            pjf.intervenant_id,
            COUNT(*) count,
            SUM(CASE WHEN validation_id IS NULL THEN 0 ELSE 1 END) validation,
            SUM(CASE WHEN fichier_id IS NULL THEN 0 ELSE 1 END) fichier
          FROM
            tbl_piece_jointe_fournie pjf
          GROUP BY
            pjf.annee_id,
            pjf.type_piece_jointe_id,
            pjf.intervenant_id
        )
        SELECT
          NVL( pjd.annee_id, pjf.annee_id ) annee_id,
          NVL( pjd.type_piece_jointe_id, pjf.type_piece_jointe_id ) type_piece_jointe_id,
          NVL( pjd.intervenant_id, pjf.intervenant_id ) intervenant_id,
          CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END demandee,
          CASE WHEN pjf.fichier = pjf.count THEN 1 ELSE 0 END fournie,
          CASE WHEN pjf.validation = pjf.count THEN 1 ELSE 0 END validee,
          NVL(pjd.heures_pour_seuil,0) heures_pour_seuil
        FROM
          tbl_piece_jointe_demande pjd
          FULL JOIN pjf ON pjf.type_piece_jointe_id = pjd.type_piece_jointe_id AND pjf.intervenant_id = pjd.intervenant_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      DEMANDEE             = v.DEMANDEE,
      FOURNIE              = v.FOURNIE,
      VALIDEE              = v.VALIDEE,
      HEURES_POUR_SEUIL    = v.HEURES_POUR_SEUIL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      DEMANDEE,
      FOURNIE,
      VALIDEE,
      HEURES_POUR_SEUIL,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.DEMANDEE,
      v.FOURNIE,
      v.VALIDEE,
      v.HEURES_POUR_SEUIL,
      0

    );

    DELETE TBL_PIECE_JOINTE WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_PIECE_JOINTE_DEMANDE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE_DEMANDE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PIECE_JOINTE_DEMANDE t
    USING (

      SELECT
        tv.*
      FROM
        (WITH i_h AS (
          SELECT
            s.intervenant_id,
            sum(vh.heures) heures,
            sum(ep.taux_fc) fc
          FROM
                 service               s
            JOIN type_volume_horaire tvh ON tvh.code = ''PREVU''
            JOIN volume_horaire       vh ON vh.service_id = s.id
                                        AND vh.type_volume_horaire_id = tvh.id
                                        AND vh.histo_destruction IS NULL
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id -- Service sur l''établissement
          WHERE
            s.histo_destruction IS NULL
            AND vh.motif_non_paiement_id IS NULL -- pas de motif de non paiement
          GROUP BY
            s.intervenant_id
        )
        SELECT
          i.annee_id                      annee_id,
          i.id                            intervenant_id,
          tpj.id                          type_piece_jointe_id,
          MAX(COALESCE(i_h.heures, 0))    heures_pour_seuil
        FROM
                    intervenant                 i

          LEFT JOIN dossier                     d ON d.intervenant_id = i.id
                                                 AND d.histo_destruction IS NULL

               JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                                 AND tpjs.histo_destruction IS NULL
                                                 AND i.annee_id BETWEEN COALESCE(tpjs.annee_debut_id,i.annee_id) AND COALESCE(tpjs.annee_fin_id,i.annee_id)

               JOIN type_piece_jointe         tpj ON tpj.id = tpjs.type_piece_jointe_id
                                                 AND tpj.histo_destruction IS NULL

          LEFT JOIN                           i_h ON i_h.intervenant_id = i.id
        WHERE
          -- Gestion de l''historique
          i.histo_destruction IS NULL

          -- Seuil HETD
          AND (COALESCE(i_h.heures,0) > COALESCE(tpjs.seuil_hetd,-1))

          -- En fonction du premier recrutement ou non
          AND (tpjs.premier_recrutement = 0 OR COALESCE(i.premier_recrutement,0) = 1)

          -- Le RIB n''est demandé QUE s''il est différent!!
          AND CASE
                WHEN tpjs.changement_rib = 0 OR d.id IS NULL THEN 1
                ELSE CASE WHEN replace(i.bic, '' '', '''') || ''-'' || replace(i.iban, '' '', '''') = d.rib THEN 0 ELSE 1 END
              END = 1

          -- Filtre FC
          AND (tpjs.fc = 0 OR i_h.fc > 0)
        GROUP BY
          i.annee_id,
          i.id,
          tpj.id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      HEURES_POUR_SEUIL    = v.HEURES_POUR_SEUIL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      HEURES_POUR_SEUIL,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_DEMAND_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.HEURES_POUR_SEUIL,
      0

    );

    DELETE TBL_PIECE_JOINTE_DEMANDE WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_PIECE_JOINTE_FOURNIE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE_FOURNIE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PIECE_JOINTE_FOURNIE t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          pj.type_piece_jointe_id,
          pj.intervenant_id,
          pj.id piece_jointe_id,
          v.id validation_id,
          f.id fichier_id
        FROM
                    piece_jointe          pj
               JOIN intervenant            i ON i.id = pj.intervenant_id
                                            AND i.histo_destruction IS NULL

               JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
               JOIN fichier                f ON f.id = pjf.fichier_id
                                            AND f.histo_destruction IS NULL

          LEFT JOIN validation             v ON v.id = pj.validation_id
                                            AND v.histo_destruction IS NULL
        WHERE
          pj.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND COALESCE(t.FICHIER_ID,0) = COALESCE(v.FICHIER_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      PIECE_JOINTE_ID      = v.PIECE_JOINTE_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      VALIDATION_ID,
      FICHIER_ID,
      PIECE_JOINTE_ID,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_FOURNI_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.VALIDATION_ID,
      v.FICHIER_ID,
      v.PIECE_JOINTE_ID,
      0

    );

    DELETE TBL_PIECE_JOINTE_FOURNIE WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_SERVICE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN return;
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_SERVICE t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
        SELECT
          s.id                                                                                      service_id,
          s.intervenant_id                                                                          intervenant_id,
          ep.structure_id                                                                           structure_id,
          ep.id                                                                                     element_pedagogique_id,
          ep.periode_id                                                                             element_pedagogique_periode_id,
          etp.id                                                                                    etape_id,

          vh.type_volume_horaire_id                                                                 type_volume_horaire_id,
          vh.heures                                                                                 heures,
          tvh.code                                                                                  type_volume_horaire_code,

          CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
          CASE WHEN etp.histo_destruction IS NULL OR cp.id IS NOT NULL THEN 1 ELSE 0 END            etape_histo,

          CASE WHEN ep.periode_id IS NOT NULL THEN
            SUM( CASE WHEN vh.periode_id <> ep.periode_id THEN 1 ELSE 0 END ) OVER( PARTITION BY vh.service_id, vh.periode_id, vh.type_volume_horaire_id, vh.type_intervention_id )
          ELSE 0 END has_heures_mauvaise_periode,

          CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
        FROM
          service                                       s
          LEFT JOIN element_pedagogique                ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN etape                             etp ON etp.id = ep.etape_id
          LEFT JOIN chemin_pedagogique                 cp ON cp.etape_id = etp.id
                                                         AND cp.element_pedagogique_id = ep.id
                                                         AND cp.histo_destruction IS NULL

               JOIN volume_horaire                     vh ON vh.service_id = s.id
                                                         AND vh.histo_destruction IS NULL

               JOIN type_volume_horaire               tvh ON tvh.id = vh.type_volume_horaire_id

          LEFT JOIN validation_vol_horaire            vvh ON vvh.volume_horaire_id = vh.id

          LEFT JOIN validation                          v ON v.id = vvh.validation_id
                                                         AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL
        )
        SELECT
          i.annee_id                                                                                annee_id,
          i.id                                                                                      intervenant_id,
          i.structure_id                                                                            intervenant_structure_id,
          NVL( t.structure_id, i.structure_id )                                                     structure_id,
          ti.id                                                                                     type_intervenant_id,
          ti.code                                                                                   type_intervenant_code,
          si.peut_saisir_service                                                                    peut_saisir_service,

          t.element_pedagogique_id,
          t.service_id,
          t.element_pedagogique_periode_id,
          t.etape_id,
          t.type_volume_horaire_id,
          t.type_volume_horaire_code,
          t.element_pedagogique_histo,
          t.etape_histo,

          CASE WHEN SUM(t.has_heures_mauvaise_periode) > 0 THEN 1 ELSE 0 END has_heures_mauvaise_periode,

          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE sum(t.heures) END heures,
          sum(valide) valide
        FROM
          t
          JOIN intervenant                              i ON i.id = t.intervenant_id
          JOIN statut_intervenant                      si ON si.id = i.statut_id
          JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
        GROUP BY
          i.annee_id,
          i.id,
          i.structure_id,
          t.structure_id,
          i.structure_id,
          ti.id,
          ti.code,
          si.peut_saisir_service,
          t.element_pedagogique_id,
          t.service_id,
          t.element_pedagogique_periode_id,
          t.etape_id,
          t.type_volume_horaire_id,
          t.type_volume_horaire_code,
          t.element_pedagogique_histo,
          t.etape_histo) tv
      WHERE
        ' || conds || '

    ) v ON (
            COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.SERVICE_ID             = v.SERVICE_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                       = v.ANNEE_ID,
      INTERVENANT_ID                 = v.INTERVENANT_ID,
      PEUT_SAISIR_SERVICE            = v.PEUT_SAISIR_SERVICE,
      STRUCTURE_ID                   = v.STRUCTURE_ID,
      NBVH                           = v.NBVH,
      VALIDE                         = v.VALIDE,
      ELEMENT_PEDAGOGIQUE_ID         = v.ELEMENT_PEDAGOGIQUE_ID,
      ELEMENT_PEDAGOGIQUE_PERIODE_ID = v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      ETAPE_ID                       = v.ETAPE_ID,
      ELEMENT_PEDAGOGIQUE_HISTO      = v.ELEMENT_PEDAGOGIQUE_HISTO,
      ETAPE_HISTO                    = v.ETAPE_HISTO,
      HAS_HEURES_MAUVAISE_PERIODE    = v.HAS_HEURES_MAUVAISE_PERIODE,
      INTERVENANT_STRUCTURE_ID       = v.INTERVENANT_STRUCTURE_ID,
      TYPE_INTERVENANT_ID            = v.TYPE_INTERVENANT_ID,
      TYPE_INTERVENANT_CODE          = v.TYPE_INTERVENANT_CODE,
      TYPE_VOLUME_HORAIRE_CODE       = v.TYPE_VOLUME_HORAIRE_CODE,
      HEURES                         = v.HEURES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      TYPE_VOLUME_HORAIRE_ID,
      STRUCTURE_ID,
      NBVH,
      VALIDE,
      ELEMENT_PEDAGOGIQUE_ID,
      ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      ETAPE_ID,
      ELEMENT_PEDAGOGIQUE_HISTO,
      ETAPE_HISTO,
      HAS_HEURES_MAUVAISE_PERIODE,
      SERVICE_ID,
      INTERVENANT_STRUCTURE_ID,
      TYPE_INTERVENANT_ID,
      TYPE_INTERVENANT_CODE,
      TYPE_VOLUME_HORAIRE_CODE,
      HEURES,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      v.ETAPE_ID,
      v.ELEMENT_PEDAGOGIQUE_HISTO,
      v.ETAPE_HISTO,
      v.HAS_HEURES_MAUVAISE_PERIODE,
      v.SERVICE_ID,
      v.INTERVENANT_STRUCTURE_ID,
      v.TYPE_INTERVENANT_ID,
      v.TYPE_INTERVENANT_CODE,
      v.TYPE_VOLUME_HORAIRE_CODE,
      v.HEURES,
      0

    );

    DELETE TBL_SERVICE WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_SERVICE_REFERENTIEL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE_REFERENTIEL SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_SERVICE_REFERENTIEL t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (

          SELECT
            i.annee_id,
            i.id intervenant_id,
            si.peut_saisir_referentiel peut_saisir_service,
            vh.type_volume_horaire_id,
            s.structure_id,
            CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
          FROM
                      intervenant                     i

                 JOIN statut_intervenant          si ON si.id = i.statut_id

            LEFT JOIN service_referentiel          s ON s.intervenant_id = i.id
                                                    AND s.histo_destruction IS NULL

            LEFT JOIN volume_horaire_ref          vh ON vh.service_referentiel_id = s.id
                                                    AND vh.histo_destruction IS NULL

            LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id

            LEFT JOIN validation                   v ON v.id = vvh.validation_id
                                                    AND v.histo_destruction IS NULL
          WHERE
            i.histo_destruction IS NULL

        )
        SELECT
          annee_id,
          intervenant_id,
          peut_saisir_service,
          type_volume_horaire_id,
          structure_id,
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
          sum(valide) valide
        FROM
          t
        WHERE
          NOT (structure_id IS NOT NULL AND type_volume_horaire_id IS NULL)
        GROUP BY
          annee_id,
          intervenant_id,
          peut_saisir_service,
          type_volume_horaire_id,
          structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      PEUT_SAISIR_SERVICE    = v.PEUT_SAISIR_SERVICE,
      NBVH                   = v.NBVH,
      VALIDE                 = v.VALIDE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      TYPE_VOLUME_HORAIRE_ID,
      STRUCTURE_ID,
      NBVH,
      VALIDE,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE,
      0

    );

    DELETE TBL_SERVICE_REFERENTIEL WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_SERVICE_SAISIE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE_SAISIE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_SERVICE_SAISIE t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
        FROM
          intervenant i
          JOIN statut_intervenant si ON si.id = i.statut_id
          LEFT JOIN service s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
          LEFT JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
          LEFT JOIN type_volume_horaire tvhs ON tvhs.id = vh.type_volume_horaire_id

          LEFT JOIN service_referentiel sr ON sr.intervenant_id = i.id AND sr.histo_destruction IS NULL
          LEFT JOIN volume_horaire_ref vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
          LEFT JOIN type_volume_horaire tvhrs ON tvhrs.id = vhr.type_volume_horaire_id
        WHERE
          i.histo_destruction IS NULL
        GROUP BY
          i.annee_id,
          i.id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                = v.ANNEE_ID,
      PEUT_SAISIR_SERVICE     = v.PEUT_SAISIR_SERVICE,
      PEUT_SAISIR_REFERENTIEL = v.PEUT_SAISIR_REFERENTIEL,
      HEURES_SERVICE_PREV     = v.HEURES_SERVICE_PREV,
      HEURES_REFERENTIEL_PREV = v.HEURES_REFERENTIEL_PREV,
      HEURES_SERVICE_REAL     = v.HEURES_SERVICE_REAL,
      HEURES_REFERENTIEL_REAL = v.HEURES_REFERENTIEL_REAL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      PEUT_SAISIR_REFERENTIEL,
      HEURES_SERVICE_PREV,
      HEURES_REFERENTIEL_PREV,
      HEURES_SERVICE_REAL,
      HEURES_REFERENTIEL_REAL,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_SAISIE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.PEUT_SAISIR_REFERENTIEL,
      v.HEURES_SERVICE_PREV,
      v.HEURES_REFERENTIEL_PREV,
      v.HEURES_SERVICE_REAL,
      v.HEURES_REFERENTIEL_REAL,
      0

    );

    DELETE TBL_SERVICE_SAISIE WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_VALIDATION_ENSEIGNEMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_VALIDATION_ENSEIGNEMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_VALIDATION_ENSEIGNEMENT t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT DISTINCT
          i.annee_id,
          i.id intervenant_id,
          CASE WHEN rsv.priorite = ''affectation'' THEN
            COALESCE( i.structure_id, ep.structure_id )
          ELSE
            COALESCE( ep.structure_id, i.structure_id )
          END structure_id,
          vh.type_volume_horaire_id,
          s.id service_id,
          vh.id volume_horaire_id,
          vh.auto_validation,
          v.id validation_id
        FROM
          service s
          JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
          JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id
          JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
          LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
          LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID           = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_ID             = v.SERVICE_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND t.VOLUME_HORAIRE_ID      = v.VOLUME_HORAIRE_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      AUTO_VALIDATION        = v.AUTO_VALIDATION,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_ID,
      VALIDATION_ID,
      VOLUME_HORAIRE_ID,
      AUTO_VALIDATION,
      TO_DELETE

    ) VALUES (

      TBL_VALIDATION_ENSEIGNE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_ID,
      v.VALIDATION_ID,
      v.VOLUME_HORAIRE_ID,
      v.AUTO_VALIDATION,
      0

    );

    DELETE TBL_VALIDATION_ENSEIGNEMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;



  PROCEDURE C_VALIDATION_REFERENTIEL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    BEGIN
      conds := params_to_conds( params );

      EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_VALIDATION_REFERENTIEL SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_VALIDATION_REFERENTIEL t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT DISTINCT
          i.annee_id,
          i.id intervenant_id,
          CASE WHEN rsv.priorite = ''affectation'' THEN
            COALESCE( i.structure_id, s.structure_id )
          ELSE
            COALESCE( s.structure_id, i.structure_id )
          END structure_id,
          vh.type_volume_horaire_id,
          s.id service_referentiel_id,
          vh.id volume_horaire_ref_id,
          vh.auto_validation,
          v.id validation_id
        FROM
          service_referentiel s
          JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND vh.histo_destruction IS NULL
          JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id
          JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
          LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
          LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID           = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_REFERENTIEL_ID = v.SERVICE_REFERENTIEL_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND t.VOLUME_HORAIRE_REF_ID  = v.VOLUME_HORAIRE_REF_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      AUTO_VALIDATION        = v.AUTO_VALIDATION,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_REFERENTIEL_ID,
      VALIDATION_ID,
      VOLUME_HORAIRE_REF_ID,
      AUTO_VALIDATION,
      TO_DELETE

    ) VALUES (

      TBL_VALIDATION_REFERENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_REFERENTIEL_ID,
      v.VALIDATION_ID,
      v.VOLUME_HORAIRE_REF_ID,
      v.AUTO_VALIDATION,
      0

    );

    DELETE TBL_VALIDATION_REFERENTIEL WHERE to_delete = 1 AND ' || conds || ';

    END;';

    END;

  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;
/