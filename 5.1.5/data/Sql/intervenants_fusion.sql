-- déplacer les données d'un compte vers un autre en vue d'une fusion
alter trigger "OSE"."SERVICE_HISTO_CK" disable;



/
DECLARE
  old_source_code NUMERIC := '119531';
  new_source_code NUMERIC := '3657';
  annee_id NUMERIC := 2016;

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

  ose_event.set_actif(false);

  --UPDATE adresse_intervenant SET intervenant_id = new_id WHERE intervenant_id = old_id AND source_id = ose_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  --UPDATE affectation_recherche SET intervenant_id = new_id WHERE intervenant_id = old_id AND source_id = ose_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE agrement SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE contrat SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE modification_service_du SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);
  UPDATE service SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE service_referentiel SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE validation SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE piece_jointe SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE dossier SET intervenant_id = new_id WHERE intervenant_id = old_id AND 1 = ose_divers.comprise_entre(histo_creation,histo_destruction);

  UPDATE formule_resultat SET intervenant_id = new_id WHERE intervenant_id = old_id;

  ose_event.set_actif(true);

--ose_event.

  ose_agrement.calculer(old_id);ose_agrement.calculer(new_id);
  OSE_CLOTURE_REALISE.calculer(old_id);OSE_CLOTURE_REALISE.calculer(new_id);
  OSE_CONTRAT.calculer(old_id);OSE_CONTRAT.calculer(new_id);
  OSE_DOSSIER.calculer(old_id);OSE_DOSSIER.calculer(new_id);
  OSE_FORMULE.calculer(old_id);OSE_FORMULE.calculer(new_id);
  OSE_PAIEMENT.calculer(old_id);OSE_PAIEMENT.calculer(new_id);
  OSE_PIECE_JOINTE.calculer(old_id);OSE_PIECE_JOINTE.calculer(new_id);
  OSE_PIECE_JOINTE_DEMANDE.calculer(old_id);OSE_PIECE_JOINTE_DEMANDE.calculer(new_id);
  OSE_PIECE_JOINTE_FOURNIE.calculer(old_id);OSE_PIECE_JOINTE_FOURNIE.calculer(new_id);
  OSE_SERVICE.calculer(old_id);OSE_SERVICE.calculer(new_id);
  OSE_SERVICE_REFERENTIEL.calculer(old_id);OSE_SERVICE_REFERENTIEL.calculer(new_id);
  OSE_SERVICE_SAISIE.calculer(old_id);OSE_SERVICE_SAISIE.calculer(new_id);
  OSE_VALIDATION_ENSEIGNEMENT.calculer(old_id);OSE_VALIDATION_ENSEIGNEMENT.calculer(new_id);
  OSE_VALIDATION_REFERENTIEL.calculer(old_id);OSE_VALIDATION_REFERENTIEL.calculer(new_id);
  OSE_WORKFLOW.calculer(old_id);OSE_WORKFLOW.calculer(new_id);

END;
/
alter trigger "OSE"."SERVICE_HISTO_CK" enable;

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