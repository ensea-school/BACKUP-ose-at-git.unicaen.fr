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