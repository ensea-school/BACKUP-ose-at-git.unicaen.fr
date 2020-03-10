CREATE OR REPLACE TRIGGER "CHARGENS_MAJ_EFFECTIFS"
  AFTER INSERT OR UPDATE OR DELETE ON scenario_noeud_effectif
  REFERENCING FOR EACH ROW
BEGIN
RETURN;
  return;
  IF NOT ose_chargens.ENABLE_TRIGGER_EFFECTIFS THEN RETURN; END IF;
  IF DELETING THEN
    ose_chargens.DEM_CALC_SUB_EFFECTIF( :OLD.scenario_noeud_id, :OLD.type_heures_id, :OLD.etape_id, 0 );
  ELSE
    ose_chargens.DEM_CALC_SUB_EFFECTIF( :NEW.scenario_noeud_id, :NEW.type_heures_id, :NEW.etape_id, :NEW.effectif );
  END IF;

END;