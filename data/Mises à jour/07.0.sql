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

ALTER TABLE volume_horaire ADD (
  horaire   DATE
  );

ALTER TABLE volume_horaire_ref ADD (
  horaire   DATE
  );

ALTER TABLE type_formation ADD (
  service_statutaire   NUMBER(1) DEFAULT 1 NOT NULL
  );

ALTER TABLE fonction_referentiel ADD (
  service_statutaire   NUMBER(1) DEFAULT 1 NOT NULL
  );