-- Génération dynamique des triggers (lihne et table) de Workflow...
SELECT

'CREATE OR REPLACE TRIGGER T_WF_' || tbl || '
AFTER INSERT OR UPDATE OR DELETE ON TBL_' || tbl || '
FOR EACH ROW
BEGIN

  IF :NEW.id IS NOT NULL THEN
    OSE_WORKFLOW.DEMANDE_CALCUL( :NEW.intervenant_id );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    OSE_WORKFLOW.DEMANDE_CALCUL( :OLD.intervenant_id );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_WF_' || tbl || '_S
AFTER INSERT OR UPDATE OR DELETE ON TBL_' || tbl || '
BEGIN
  OSE_WORKFLOW.CALCULER_SUR_DEMANDE;
END;

/
' isql

FROM
  (
        SELECT 'SERVICE'             tbl FROM dual
  UNION SELECT 'SERVICE_REFERENTIEL' tbl FROM dual
  UNION SELECT 'PIECE_JOINTE'        tbl FROM dual
  UNION SELECT 'PAIEMENT'            tbl FROM dual
  UNION SELECT 'DOSSIER'             tbl FROM dual
  UNION SELECT 'SERVICE_SAISIE'      tbl FROM dual
  UNION SELECT 'AGREMENT'            tbl FROM dual
  UNION SELECT 'CLOTURE_REALISE'     tbl FROM dual
  UNION SELECT 'CONTRAT'             tbl FROM dual

  ) t;