CREATE OR REPLACE PACKAGE "OSE_CHARGENS" AS
  ENABLE_TRIGGER_EFFECTIFS BOOLEAN DEFAULT TRUE;

  TYPE tnoeud_ids IS TABLE OF NUMERIC;

  FUNCTION GET_SCENARIO RETURN NUMERIC;
  PROCEDURE SET_SCENARIO( SCENARIO NUMERIC );

  FUNCTION GET_NOEUD RETURN NUMERIC;
  PROCEDURE SET_NOEUD( NOEUD NUMERIC );

  FUNCTION CALC_COEF( choix_min NUMERIC, choix_max NUMERIC, poids NUMERIC, max_poids NUMERIC, total_poids NUMERIC, nb_choix NUMERIC ) RETURN FLOAT;

  PROCEDURE DEM_CALC_SUB_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT );
  PROCEDURE CALC_SUB_EFFECTIF_DEM;

  PROCEDURE CALC_ALL_EFFECTIFS;

  PROCEDURE CALC_EFFECTIF( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC DEFAULT NULL, etape_id NUMERIC DEFAULT NULL);
  PROCEDURE CALC_SUB_EFFECTIF2( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC DEFAULT NULL, etape_id NUMERIC DEFAULT NULL);

  PROCEDURE DUPLIQUER( source_id NUMERIC, destination_id NUMERIC, utilisateur_id NUMERIC, structure_id NUMERIC, noeuds VARCHAR2 DEFAULT '', liens VARCHAR2 DEFAULT '' );

  PROCEDURE CONTROLE_SEUIL( ouverture NUMERIC, dedoublement NUMERIC );

  FUNCTION GET_SCENARIO_NOEUD_ID( scenario_id NUMERIC, noeud_id NUMERIC ) RETURN NUMERIC;
  FUNCTION CREER_SCENARIO_NOEUD( scenario_id NUMERIC, noeud_id NUMERIC, assiduite FLOAT DEFAULT 1 ) RETURN NUMERIC;

  PROCEDURE ADD_SCENARIO_NOEUD_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT );

  PROCEDURE INIT_SCENARIO_NOEUD_EFFECTIF(
    etape_id NUMERIC,
    scenario_id NUMERIC,
    type_heures_id NUMERIC,
    effectif FLOAT,
    surcharge BOOLEAN DEFAULT FALSE
  );

  PROCEDURE SET_PRECALC_HEURES_PARAMS(
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_ids                      tnoeud_ids DEFAULT NULL
  );

  FUNCTION MATCH_PRECALC_HEURES_PARAMS(
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_id                       NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;


END OSE_CHARGENS;