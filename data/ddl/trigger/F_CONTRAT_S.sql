CREATE OR REPLACE TRIGGER "F_CONTRAT_S"
AFTER UPDATE OR DELETE ON contrat
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;