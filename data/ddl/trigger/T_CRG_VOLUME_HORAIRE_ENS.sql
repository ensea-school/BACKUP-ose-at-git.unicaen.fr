CREATE OR REPLACE TRIGGER "T_CRG_VOLUME_HORAIRE_ENS"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_INTERVENTION_ID, HEURES, HISTO_DESTRUCTION, ELEMENT_PEDAGOGIQUE_ID ON "VOLUME_HORAIRE_ENS"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('element_pedagogique_id', :OLD.element_pedagogique_id ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('element_pedagogique_id', :NEW.element_pedagogique_id ) );
  END IF;

END;