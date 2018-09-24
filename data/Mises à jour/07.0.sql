-- Script de migration de la version 6.3.2 à la 7.0

-- Import ouvert pour les services
ALTER TABLE service ADD (source_id   NUMBER(*,0) );
ALTER TABLE service ADD (source_code VARCHAR2(100 CHAR));

ALTER TRIGGER SERVICE_CK DISABLE;
UPDATE service SET source_id = (SELECT id FROM source WHERE code = 'OSE');
UPDATE service SET source_code = id;
ALTER TRIGGER SERVICE_CK ENABLE;

ALTER TABLE service MODIFY ( source_id NOT NULL );
ALTER TABLE service MODIFY ( source_code NOT NULL );
ALTER TABLE service ADD CONSTRAINT service_source_fk FOREIGN KEY ( source_id ) REFERENCES source ( id ) NOT DEFERRABLE;
ALTER TABLE service ADD CONSTRAINT service_source_un UNIQUE ( source_code,histo_destruction );

-- Import possible pour les volumes horaires
ALTER TABLE volume_horaire ADD (source_id   NUMBER(*,0) );
UPDATE volume_horaire SET source_id = (SELECT id FROM source WHERE code = 'OSE');

ALTER TABLE volume_horaire ADD (source_code VARCHAR2(100 CHAR));
UPDATE volume_horaire SET source_code = id;

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