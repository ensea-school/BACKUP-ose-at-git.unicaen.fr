CREATE OR REPLACE PACKAGE "FORMULE_UNICAEN_2015" AS
  debug_enabled                BOOLEAN DEFAULT FALSE;
  debug_etat_volume_horaire_id NUMERIC DEFAULT 1;
  debug_volume_horaire_id      NUMERIC;
  debug_volume_horaire_ref_id  NUMERIC;

  PROCEDURE CALCUL_RESULTAT_V2;
  PROCEDURE CALCUL_RESULTAT;

  PROCEDURE PURGE_EM_NON_FC;

  FUNCTION INTERVENANT_QUERY RETURN CLOB;
  FUNCTION VOLUME_HORAIRE_QUERY RETURN CLOB;

END FORMULE_UNICAEN_2015;