CREATE OR REPLACE TRIGGER "T_CRG_SCENARIO_NOEUD_EFFECTIF"
  AFTER INSERT OR DELETE OR UPDATE OF SCENARIO_NOEUD_ID, TYPE_HEURES_ID, EFFECTIF, ETAPE_ID ON "SCENARIO_NOEUD_EFFECTIF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT
      sn.noeud_id, sn.scenario_id, n.etape_id
    FROM
      scenario_noeud sn
      JOIN noeud n ON n.id = sn.noeud_id
    WHERE
      sn.id = :OLD.scenario_noeud_id OR sn.id = :NEW.scenario_noeud_id

  ) LOOP

    IF p.etape_id IS NOT NULL THEN
      UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', 'ETAPE_ENS_ID', p.etape_id );
    END IF;
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', 'NOEUD_ID', p.noeud_id );

  END LOOP;

END;