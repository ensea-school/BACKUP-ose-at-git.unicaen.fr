-- déplacer les données d'un compte vers un autre en vue d'une fusion
alter trigger "OSE"."SERVICE_HISTO_CK" disable;
alter trigger "OSE"."VALIDATION_CK" disable;
alter trigger "OSE"."F_CONTRAT_S" disable;
alter trigger "OSE"."F_MODIF_SERVICE_DU_S" disable;
alter trigger "OSE"."SERVICE_CK" disable;
alter trigger "OSE"."F_SERVICE_S" disable;
alter trigger "OSE"."F_SERVICE_REFERENTIEL_S" disable;
alter trigger "OSE"."F_VALIDATION_S" disable;
alter trigger "OSE"."FORMULE_RES_SERVICE_DEL_CK" disable;
/
DECLARE
  old_source_code NUMERIC := '112292';
  new_source_code NUMERIC := '40926';

  old_id NUMERIC;
  new_id NUMERIC;
  
  ie_old_id NUMERIC;
  ie_new_id NUMERIC;
  
  old_dossier_id NUMERIC;
  new_dossier_id NUMERIC;
  
  ose_id NUMERIC;
BEGIN
  select id INTO old_id from intervenant where source_code = old_source_code AND annee_id = 2015;  
  select id INTO new_id from intervenant where source_code = new_source_code AND annee_id = 2015;

 -- select id, dossier_id INTO ie_old_id, old_dossier_id from intervenant_exterieur where id = old_id;
 -- select id, dossier_id INTO ie_new_id, new_dossier_id from intervenant_exterieur where id = new_id;

  SELECT id INTO ose_id FROM source WHERE code = 'OSE';
  
  --UPDATE adresse_intervenant SET intervenant_id = new_id WHERE intervenant_id = old_id AND source_id = ose_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  --UPDATE affectation_recherche SET intervenant_id = new_id WHERE intervenant_id = old_id AND source_id = ose_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE agrement SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  
  UPDATE contrat SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  
  UPDATE modification_service_du SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  UPDATE service SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  
  UPDATE service_referentiel SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  
  UPDATE validation SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  UPDATE wf_intervenant_etape SET intervenant_id = new_id WHERE intervenant_id = old_id;
  
  -- traitement du dossier
  /*IF old_dossier_id is not null and new_dossier_id is not null THEN
    UPDATE piece_jointe SET dossier_id = new_dossier_id WHERE dossier_id = old_dossier_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);  
  ELSIF ie_new_id IS NOT NULL AND old_dossier_id IS NOT NULL THEN
    UPDATE intervenant SET dossier_id = new_dossier_id WHERE id = ie_new_id;
  END IF;*/
  
END;
/
alter trigger "OSE"."SERVICE_HISTO_CK" enable;
alter trigger "OSE"."VALIDATION_CK" enable;
alter trigger "OSE"."F_CONTRAT_S" enable;
alter trigger "OSE"."F_MODIF_SERVICE_DU_S" enable;
alter trigger "OSE"."SERVICE_CK" enable;
alter trigger "OSE"."F_SERVICE_S" enable;
alter trigger "OSE"."F_SERVICE_REFERENTIEL_S" enable;
alter trigger "OSE"."F_VALIDATION_S" enable;
alter trigger "OSE"."FORMULE_RES_SERVICE_DEL_CK" enable;
/



-- intervertir deux source_code harpège
DECLARE
  old_source_code VARCHAR2(255) DEFAULT '96074';
  new_source_code VARCHAR2(255) DEFAULT '55342';
BEGIN
  
  UPDATE intervenant SET source_code = 'old_zzz1' WHERE source_code = old_source_code;
  UPDATE intervenant SET source_code = 'new_zzz1' WHERE source_code = new_source_code;
  
  UPDATE intervenant SET source_code = new_source_code WHERE source_code = 'old_zzz1';
  UPDATE intervenant SET source_code = old_source_code WHERE source_code = 'new_zzz1';
  
END;
/


--select ut.* from user_tab_cols utc JOIN user_tables ut ON ut.table_name = utc.table_name where utc.column_name = 'DOSSIER_ID' order by ut.table_name;