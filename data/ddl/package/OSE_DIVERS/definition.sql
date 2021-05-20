CREATE OR REPLACE PACKAGE "OSE_DIVERS" AS

  FUNCTION CALC_POURC_AA( periode_id NUMERIC, horaire_debut DATE, horaire_fin DATE, annee_id NUMERIC ) RETURN FLOAT;
  FUNCTION CALC_HEURES_AA(heures FLOAT, pourc_exercice_aa FLOAT, total_heures FLOAT, cumul_heures FLOAT) RETURN FLOAT;

  PROCEDURE CALCULER_TABLEAUX_BORD;
  PROCEDURE CALCUL_FEUILLE_DE_ROUTE( INTERVENANT_ID NUMERIC );

  FUNCTION DATE_TO_PERIODE_CODE( date DATE, annee_id NUMERIC ) RETURN VARCHAR2;

  FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC;
  FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC;

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE );

  FUNCTION STR_REDUCE( str VARCHAR2 ) RETURN VARCHAR2;

  FUNCTION STR_FIND( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC;

  FUNCTION GET_VIEW_QUERY( view_name VARCHAR2 ) RETURN CLOB;

  FUNCTION LIKED( haystack VARCHAR2, needle VARCHAR2 ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 4 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 4 ) RETURN FLOAT;

  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 4 ) RETURN FLOAT;

  PROCEDURE SYNC_LOG( msg VARCHAR2 );

  FUNCTION FORMATTED_ADRESSE(
    precisions VARCHAR2,
    lieu_dit VARCHAR2,
    numero VARCHAR2,
    numero_compl_id NUMERIC,
    voirie_id NUMERIC,
    voie VARCHAR2,
    code_postal VARCHAR2,
    commune VARCHAR2,
    pays_id VARCHAR2
  ) RETURN VARCHAR2;

END OSE_DIVERS;