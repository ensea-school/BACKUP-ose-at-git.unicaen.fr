CREATE OR REPLACE TRIGGER "T_CRG_SCENARIO_NOEUD"
  AFTER INSERT OR DELETE OR UPDATE OF SCENARIO_ID, NOEUD_ID, HISTO_DESTRUCTION ON "SCENARIO_NOEUD"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('noeud_id', :OLD.noeud_id, 'scenario_id', :OLD.scenario_id ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('noeud_id', :NEW.noeud_id, 'scenario_id', :NEW.scenario_id ) );
  END IF;

END;