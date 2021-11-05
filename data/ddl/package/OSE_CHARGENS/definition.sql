create or replace PACKAGE "OSE_CHARGENS" AS

  PROCEDURE CALC_EFFECTIF( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC);
  PROCEDURE CALC_SUB_EFFECTIF( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC);

  PROCEDURE DUPLIQUER( source_id NUMERIC, destination_id NUMERIC, utilisateur_id NUMERIC, structure_id NUMERIC, noeuds VARCHAR2 DEFAULT '', liens VARCHAR2 DEFAULT '' );

  FUNCTION CREER_SCENARIO_NOEUD( scenario_id NUMERIC, noeud_id NUMERIC, assiduite FLOAT DEFAULT 1 ) RETURN NUMERIC;

  PROCEDURE ADD_SCENARIO_NOEUD_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT );

END OSE_CHARGENS;