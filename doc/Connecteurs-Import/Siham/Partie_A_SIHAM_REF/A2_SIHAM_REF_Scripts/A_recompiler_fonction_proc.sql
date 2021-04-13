/* ============= PARTIE_A_SIHAM_REF  =================================

 script de recompil de toutes les fonctions et procédure OSE 
 ===================================================================*/

ALTER FUNCTION OSE.UM_EXISTE_PAYS compile;
ALTER FUNCTION OSE.UM_EXISTE_DEPARTEMENT compile;
ALTER FUNCTION OSE.UM_UO_TYPE_ENS compile;

ALTER FUNCTION OSE.UM_CODE_UO_NIVEAU_DESSUS compile;
ALTER FUNCTION OSE.UM_EST_STRUCT_MANU compile;
ALTER FUNCTION OSE.UM_NIVEAU_UO compile;
ALTER FUNCTION OSE.UM_AFFICH_UO_SUP compile;
ALTER FUNCTION OSE.UM_UO_NUDOSS compile;
ALTER FUNCTION OSE.UM_EXISTE_STRUCTURE compile;
ALTER FUNCTION OSE.UM_EXISTE_ADR_STRUCTURE compile;

ALTER FUNCTION OSE.UM_EXISTE_CORPS compile;
ALTER FUNCTION OSE.UM_EXISTE_GRADE compile;

-- OSE V15
ALTER FUNCTION OSE.UM_EST_CTR_PERMANENT compile;
ALTER FUNCTION OSE.UM_EST_CTR_PERM_OU_VAC compile;
ALTER FUNCTION OSE.UM_EXISTE_VOIRIE compile;
ALTER FUNCTION OSE.UM_EXISTE_VOIRIE_LIB  compile;
ALTER FUNCTION OSE.UM_EXISTE_ADR_NUM_COMPL compile;

--- procedures --------------------------------

ALTER PROCEDURE OSE.UM_SYNCHRO_PAYS compile;
ALTER PROCEDURE OSE.UM_SYNCHRO_DEPARTEMENT compile;
-- OSE V15
ALTER PROCEDURE OSE.UM_SYNCHRO_VOIRIE compile;
ALTER PROCEDURE OSE.UM_ALIM_ADRESSE_NUMERO_COMPL compile;

ALTER PROCEDURE OSE.UM_SYNCHRO_STRUCTURE compile;
ALTER PROCEDURE OSE.UM_SYNCHRO_GRADE compile;