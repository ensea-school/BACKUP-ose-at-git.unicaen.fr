CREATE OR REPLACE TRIGGER "PARAMETRE_S"
AFTER UPDATE ON PARAMETRE
FOR EACH ROW
BEGIN
  ose_parametre.clear_cache;
END;