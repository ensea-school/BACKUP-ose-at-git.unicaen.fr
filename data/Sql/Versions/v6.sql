-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END;
/


Insert into INDICATEUR (
  ID,
  TYPE,
  ORDRE,
  ENABLED,
  NUMERO,
  LIBELLE_SINGULIER,
  LIBELLE_PLURIEL,
  ROUTE,
  TEM_DISTINCT,
  TEM_NOT_STRUCTURE,
  MESSAGE
) values (
  INDICATEUR_ID_SEQ.NEXTVAL,
  'Charges d''enseignement',
  1110,
  1,
  1110,
  '%s intervenant a des heures d''enseignement <i>prévisionnel</i> dépassant la charge programmée',
  '%s intervenants ont des heures d''enseignement <i>prévisionnel</i> dépassant la charge programmée',
  'indicateur/depassement-charges',
  1,
  0,
  null
);

Insert into INDICATEUR (
  ID,
  TYPE,
  ORDRE,
  ENABLED,
  NUMERO,
  LIBELLE_SINGULIER,
  LIBELLE_PLURIEL,
  ROUTE,
  TEM_DISTINCT,
  TEM_NOT_STRUCTURE,
  MESSAGE
) values (
  INDICATEUR_ID_SEQ.NEXTVAL,
  'Charges d''enseignement',
  1120,
  1,
  1120,
  '%s intervenant a des heures d''enseignement <i>réalisé</i> dépassant la charge programmée',
  '%s intervenants ont des heures d''enseignement <i>réalisé</i> dépassant la charge programmée',
  'indicateur/depassement-charges',
  1,
  0,
  null
);

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
