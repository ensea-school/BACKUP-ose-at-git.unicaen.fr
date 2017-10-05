CREATE OR REPLACE TRIGGER T_CRG_ETAPE
AFTER 
  INSERT 
  OR UPDATE OF HISTO_DESTRUCTION 
  OR DELETE ON ETAPE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', 'etape_id = ' || :OLD.id || ' OR etape_ens_id = ' || :OLD.id );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', 'etape_id = ' || :NEW.id || ' OR etape_ens_id = ' || :NEW.id );
  END IF;
  
END;

/

CREATE OR REPLACE TRIGGER T_CRG_SCENARIO_NOEUD
AFTER 
  INSERT 
  OR UPDATE OF HISTO_DESTRUCTION, NOEUD_ID, SCENARIO_ID 
  OR DELETE ON SCENARIO_NOEUD
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('noeud_id', :OLD.noeud_id, 'scenario_id', :OLD.scenario_id ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('noeud_id', :NEW.noeud_id, 'scenario_id', :NEW.scenario_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRG_SCENARIO_NOEUD_EFFECTIF
AFTER
  INSERT
  OR UPDATE OF EFFECTIF, ETAPE_ID, SCENARIO_NOEUD_ID, TYPE_HEURES_ID
  OR DELETE ON SCENARIO_NOEUD_EFFECTIF
FOR EACH ROW
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
      UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('etape_ens_id', p.etape_id, 'scenario_id', p.scenario_id ) );
    END IF;
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('noeud_id', p.noeud_id, 'scenario_id', p.scenario_id ) );
    
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_CRG_SCENARIO_NOEUD_SEUIL
AFTER 
  INSERT 
  OR UPDATE OF ASSIDUITE, DEDOUBLEMENT, OUVERTURE, SCENARIO_NOEUD_ID, TYPE_INTERVENTION_ID 
  OR DELETE ON SCENARIO_NOEUD_SEUIL
FOR EACH ROW
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
      UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('etape_ens_id', p.etape_id, 'scenario_id', p.scenario_id ) );
    END IF;
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('noeud_id', p.noeud_id, 'scenario_id', p.scenario_id ) );
    
  END LOOP;

END;

/
/*
CREATE OR REPLACE TRIGGER T_CRG_TBL_CHARGENS_SEUILS_DEF
AFTER 
  INSERT 
  OR UPDATE OF ANNEE_ID, DEDOUBLEMENT, GROUPE_TYPE_FORMATION_ID, SCENARIO_ID, STRUCTURE_ID, TYPE_INTERVENTION_ID 
  OR DELETE ON TBL_CHARGENS_SEUILS_DEF
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params(
      'annee_id'                , :OLD.annee_id,
      'scenario_id'             , :OLD.scenario_id,
      'type_intervention_id'    , :OLD.type_intervention_id,
      'structure_id'            , :OLD.structure_id,
      'groupe_type_formation_id', :OLD.groupe_type_formation_id
    ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params(
      'annee_id'                , :NEW.annee_id,
      'scenario_id'             , :NEW.scenario_id,
      'type_intervention_id'    , :NEW.type_intervention_id,
      'structure_id'            , :NEW.structure_id,
      'groupe_type_formation_id', :NEW.groupe_type_formation_id
    ) );
  END IF;
  
END;

/

CREATE OR REPLACE TRIGGER T_CRG_TBL_NOEUD
AFTER 
  INSERT 
  OR UPDATE OF ANNEE_ID, ELEMENT_PEDAGOGIQUE_ETAPE_ID, ELEMENT_PEDAGOGIQUE_ID, ETAPE_ID, GROUPE_TYPE_FORMATION_ID, NOEUD_ID, STRUCTURE_ID 
  OR DELETE ON TBL_NOEUD
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;



END;
*/
/

CREATE OR REPLACE TRIGGER T_CRG_TYPE_INTERVENTION
AFTER 
  INSERT 
  OR UPDATE OF TAUX_HETD_SERVICE 
  OR DELETE ON TYPE_INTERVENTION
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('type_intervention_id', :OLD.id ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('type_intervention_id', :NEW.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRG_VOLUME_HORAIRE_ENS
AFTER 
  INSERT 
  OR UPDATE OF ELEMENT_PEDAGOGIQUE_ID, HEURES, HISTO_DESTRUCTION, TYPE_INTERVENTION_ID 
  OR DELETE ON VOLUME_HORAIRE_ENS
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('element_pedagogique_id', :OLD.element_pedagogique_id ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('element_pedagogique_id', :NEW.element_pedagogique_id ) );
  END IF;

END;



/



CREATE OR REPLACE TRIGGER T_CRG_ETAPE_S
AFTER INSERT OR UPDATE OR DELETE ON ETAPE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRG_TYPE_INTERVENTION
AFTER INSERT OR UPDATE OR DELETE ON TYPE_INTERVENTION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
