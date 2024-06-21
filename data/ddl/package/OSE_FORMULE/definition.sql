CREATE OR REPLACE PACKAGE "OSE_FORMULE" AS

  TYPE t_intervenant IS RECORD (
    -- identifiants
    id                             NUMERIC,
    annee_id                       NUMERIC,
    type_volume_horaire_id         NUMERIC,
    etat_volume_horaire_id         NUMERIC,

    -- paramètres globaux
    heures_service_statutaire      FLOAT DEFAULT 0,
    heures_service_modifie         FLOAT DEFAULT 0,
    depassement_service_du_sans_hc BOOLEAN DEFAULT FALSE,
    structure_code                 VARCHAR(100),
    type_intervenant_code          VARCHAR(2),

    -- paramètres spacifiques
    param_1                        VARCHAR(100),
    param_2                        VARCHAR(100),
    param_3                        VARCHAR(100),
    param_4                        VARCHAR(100),
    param_5                        VARCHAR(100),

    -- résultats
    service_du                     FLOAT,
    total                          FLOAT,
    solde                          FLOAT,
    debug_info                     CLOB
  );

  TYPE t_volume_horaire IS RECORD (
    -- identifiants
    volume_horaire_id          NUMERIC,
    volume_horaire_ref_id      NUMERIC,
    service_id                 NUMERIC,
    service_referentiel_id     NUMERIC,

    -- paramètres globaux
    type_volume_horaire_code   VARCHAR(15),
    structure_code             VARCHAR(100),
    structure_is_affectation   BOOLEAN DEFAULT TRUE,
    structure_is_univ          BOOLEAN DEFAULT FALSE,
    structure_is_exterieur     BOOLEAN DEFAULT FALSE,
    service_statutaire         BOOLEAN DEFAULT TRUE,
    taux_fi                    FLOAT DEFAULT 1,
    taux_fa                    FLOAT DEFAULT 0,
    taux_fc                    FLOAT DEFAULT 0,

    -- pondérations et heures
    type_intervention_code     VARCHAR(15),
    taux_service_du            FLOAT DEFAULT 1, -- en fonction des types d'intervention
    taux_service_compl         FLOAT DEFAULT 1, -- en fonction des types d'intervention
    ponderation_service_du     FLOAT DEFAULT 1, -- relatif aux modulateurs
    ponderation_service_compl  FLOAT DEFAULT 1, -- relatif aux modulateurs
    heures                     FLOAT DEFAULT 0, -- heures réelles saisies

    -- paramètres spacifiques
    param_1                    VARCHAR(100),
    param_2                    VARCHAR(100),
    param_3                    VARCHAR(100),
    param_4                    VARCHAR(100),
    param_5                    VARCHAR(100),

    -- résultats
    service_fi                 FLOAT DEFAULT 0,
    service_fa                 FLOAT DEFAULT 0,
    service_fc                 FLOAT DEFAULT 0,
    service_referentiel        FLOAT DEFAULT 0,
    heures_compl_fi            FLOAT DEFAULT 0,
    heures_compl_fa            FLOAT DEFAULT 0,
    heures_compl_fc            FLOAT DEFAULT 0,
    heures_primes              FLOAT DEFAULT 0,
    heures_compl_referentiel   FLOAT DEFAULT 0,

    debug_info                 CLOB
  );
  TYPE t_lst_volume_horaire IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;
  TYPE t_volumes_horaires IS RECORD (
    LENGTH NUMERIC DEFAULT 0,
    items t_lst_volume_horaire
  );

  debug_actif      BOOLEAN DEFAULT FALSE;
  intervenant      t_intervenant;
  volumes_horaires t_volumes_horaires;

  PROCEDURE TEST( INTERVENANT_TEST_ID NUMERIC );
  PROCEDURE TEST_TOUT;

  PROCEDURE DEBUG_INTERVENANT;
  PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL);
END OSE_FORMULE;