
--------------------------------------------------------
--  DDL for Trigger INDIC_TRG_MODIF_DOSSIER
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."INDIC_TRG_MODIF_DOSSIER" 
  AFTER INSERT OR UPDATE OF NOM_USUEL, NOM_PATRONYMIQUE, PRENOM, CIVILITE_ID, ADRESSE, RIB, DATE_NAISSANCE ON "OSE"."DOSSIER"

  FOR EACH ROW
/**
 * But : mettre à jour la liste des PJ attendues.
 */
DECLARE
  i integer := 1;
  intervenantId NUMERIC;
  found integer;
  estCreationDossier integer;
  type array_t is table of varchar2(1024);
  
  attrNames     array_t := array_t();
  attrOldVals   array_t := array_t();
  attrNewVals   array_t := array_t();
  
  -- valeurs importées (format texte) :
  impSourceName source.libelle%type;
  impNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  impCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  impDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  impAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  impRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
  -- anciennes valeurs dans le dossier (format texte) :
  oldSourceName source.libelle%type;
  oldNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  oldRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
  -- nouvelles valeurs dans le dossier (format texte) :
  newSourceName source.libelle%type;
  newNomUsuel   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newNomPatro   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newPrenom     indic_modif_dossier.ATTR_NEW_VALUE%type;
  newCivilite   indic_modif_dossier.ATTR_NEW_VALUE%type;
  newDateNaiss  indic_modif_dossier.ATTR_NEW_VALUE%type;
  newAdresse    indic_modif_dossier.ATTR_NEW_VALUE%type;
  newRib        indic_modif_dossier.ATTR_NEW_VALUE%type;
BEGIN
  --
  -- Témoin indiquant s'il s'agit d'une création de dossier (insert).
  --
  estCreationDossier := case when inserting then 1 else 0 end;
  
  --
  -- Fetch source OSE.
  --
  select s.libelle into newSourceName from source s where s.code = 'OSE';
  
  -- 
  -- Fetch et formattage texte des valeurs importées.
  --
  select 
      i.id,
      s.libelle, 
      nvl(i.NOM_USUEL, '(Aucun)'), 
      nvl(i.NOM_PATRONYMIQUE, '(Aucun)'), 
      nvl(i.PRENOM, '(Aucun)'), 
      nvl(c.libelle_court, '(Aucune)'), 
      nvl(to_char(i.DATE_NAISSANCE, 'DD/MM/YYYY'), '(Aucune)'), 
      nvl(ose_divers.formatted_rib(i.bic, i.iban), '(Aucun)'), 
      case when a.id is not null  
        then ose_divers.formatted_adresse(a.NO_VOIE, a.NOM_VOIE, a.BATIMENT, a.MENTION_COMPLEMENTAIRE, a.LOCALITE, a.CODE_POSTAL, a.VILLE, a.PAYS_LIBELLE) 
        else '(Aucune)'
      end
    into 
      intervenantId,
      oldSourceName, 
      impNomUsuel, 
      impNomPatro, 
      impPrenom, 
      impCivilite, 
      impDateNaiss, 
      impRib, 
      impAdresse
    from intervenant i
    join source s on s.id = i.source_id
    left join civilite c on c.id = i.civilite_id
    left join adresse_intervenant a on a.intervenant_id = i.id
    where i.id = :NEW.intervenant_id;
  
  -- 
  -- Anciennes valeurs dans le cas d'une création de dossier : ce sont les valeurs importées.
  -- 
  if (1 = estCreationDossier) then
    --dbms_output.put_line('inserting');
    oldNomUsuel  := impNomUsuel;
    oldNomPatro  := impNomPatro;
    oldPrenom    := impPrenom;
    oldCivilite  := impCivilite;
    oldDateNaiss := impDateNaiss;
    oldAdresse   := impAdresse;
    oldRib       := impRib;
  -- 
  -- Anciennes valeurs dans le cas d'une mise à jour du dossier.
  -- 
  else
    --dbms_output.put_line('updating');
    oldNomUsuel     := trim(:OLD.NOM_USUEL);
    oldNomPatro     := trim(:OLD.NOM_PATRONYMIQUE);
    oldPrenom       := trim(:OLD.PRENOM);
    oldDateNaiss    := case when :OLD.DATE_NAISSANCE is null then '(Aucune)' else to_char(:OLD.DATE_NAISSANCE, 'DD/MM/YYYY') end;
    oldAdresse      := trim(:OLD.ADRESSE);
    oldRib          := trim(:OLD.RIB);
    if :OLD.CIVILITE_ID is not null then
      select c.libelle_court into oldCivilite from civilite c where c.id = :OLD.CIVILITE_ID;
    else
      oldCivilite := '(Aucune)';
    end if;
    select s.libelle into oldSourceName from source s where s.code = 'OSE';
  end if;
  
  -- 
  -- Nouvelles valeurs saisies.
  --
  newNomUsuel   := trim(:NEW.NOM_USUEL);
  newNomPatro   := trim(:NEW.NOM_PATRONYMIQUE);
  newPrenom     := trim(:NEW.PRENOM);
  newDateNaiss  := case when :NEW.DATE_NAISSANCE is null then '(Aucune)' else to_char(:NEW.DATE_NAISSANCE, 'DD/MM/YYYY') end;
  newAdresse    := trim(:NEW.ADRESSE);
  newRib        := trim(:NEW.RIB);
  if :NEW.CIVILITE_ID is not null then
    select c.libelle_court into newCivilite from civilite c where c.id = :NEW.CIVILITE_ID;
  else
    newCivilite := '(Aucune)';
  end if;
    
  --
  -- Détection des différences.
  --
  if newNomUsuel <> oldNomUsuel then
    --dbms_output.put_line('NOM_USUEL ' || sourceLib || ' = ' || oldNomUsuel || ' --> NOM_USUEL OSE = ' || :NEW.NOM_USUEL);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Nom usuel';
    attrOldVals(i) := oldNomUsuel;
    attrNewVals(i) := newNomUsuel;
    i := i + 1;
  end if;  
  if newNomPatro <> oldNomPatro then
    --dbms_output.put_line('NOM_PATRONYMIQUE ' || sourceLib || ' = ' || oldNomPatro || ' --> NOM_PATRONYMIQUE OSE = ' || :NEW.NOM_PATRONYMIQUE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Nom de naissance';
    attrOldVals(i) := oldNomPatro;
    attrNewVals(i) := newNomPatro;
    i := i + 1;
  end if;  
  if newPrenom <> oldPrenom then
    --dbms_output.put_line('PRENOM ' || sourceLib || ' = ' || oldPrenom || ' --> PRENOM OSE = ' || :NEW.PRENOM);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Prénom';
    attrOldVals(i) := oldPrenom;
    attrNewVals(i) := newPrenom;
    i := i + 1;
  end if;  
  if newCivilite <> oldCivilite then
    --dbms_output.put_line('CIVILITE_ID ' || sourceLib || ' = ' || oldCivilite || ' --> CIVILITE_ID OSE = ' || :NEW.CIVILITE_ID);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Civilité';
    attrOldVals(i) := oldCivilite;
    attrNewVals(i) := newCivilite;
    i := i + 1;
  end if;  
  if newDateNaiss <> oldDateNaiss then
    --dbms_output.put_line('DATE_NAISSANCE ' || sourceLib || ' = ' || oldDateNaiss || ' --> DATE_NAISSANCE OSE = ' || :NEW.DATE_NAISSANCE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Date de naissance';
    attrOldVals(i) := oldDateNaiss;
    attrNewVals(i) := newDateNaiss;
    i := i + 1;
  end if;  
  if newAdresse <> oldAdresse then
    --dbms_output.put_line('ADRESSE ' || sourceLib || ' = ' || oldAdresse || ' --> ADRESSE OSE = ' || :NEW.ADRESSE);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'Adresse postale';
    attrOldVals(i) := oldAdresse;
    attrNewVals(i) := newAdresse;
    i := i + 1;
  end if;  
  if oldRib is null or newRib <> oldRib then
    --dbms_output.put_line('RIB ' || sourceLib || ' = ' || oldRib || ' --> RIB OSE = ' || :NEW.RIB);
    attrNames.extend(1);
    attrOldVals.extend(1);
    attrNewVals.extend(1);
    attrNames(i)   := 'RIB';
    attrOldVals(i) := oldRib;
    attrNewVals(i) := newRib;
    i := i + 1;
  end if;
  
  --
  -- Enregistrement des différences.
  --
  for i in 1 .. attrNames.count loop
    --dbms_output.put_line(attrNames(i) || ' ' || oldSourceName || ' = ' || attrOldVals(i) || ' --> ' || attrNames(i) || ' ' || newSourceName || ' = ' || attrNewVals(i));
    
    -- vérification que la même modif n'est pas déjà consignée
    select count(*) into found from indic_modif_dossier 
      where INTERVENANT_ID = intervenantId
      and ATTR_NAME = attrNames(i) 
      and ATTR_OLD_VALUE = to_char(attrOldVals(i))
      and ATTR_NEW_VALUE = to_char(attrNewVals(i));
    if found > 0 then
      continue;
    end if;
    
    insert into INDIC_MODIF_DOSSIER(
      id, 
      INTERVENANT_ID, 
      ATTR_NAME, 
      ATTR_OLD_SOURCE_NAME, 
      ATTR_OLD_VALUE, 
      ATTR_NEW_SOURCE_NAME, 
      ATTR_NEW_VALUE,
      EST_CREATION_DOSSIER, -- témoin indiquant s'il s'agit d'une création ou d'une modification de dossier
      HISTO_CREATION,       -- NB: date de modification du dossier
      HISTO_CREATEUR_ID,    -- NB: auteur de la modification du dossier
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    ) 
    values (
      indic_modif_dossier_id_seq.nextval, 
      intervenantId,
      attrNames(i), 
      oldSourceName, 
      to_char(attrOldVals(i)), 
      newSourceName, 
      to_char(attrNewVals(i)),
      estCreationDossier,
      :NEW.HISTO_MODIFICATION,
      :NEW.HISTO_MODIFICATEUR_ID,
      :NEW.HISTO_MODIFICATION,
      :NEW.HISTO_MODIFICATEUR_ID
    );
  end loop;
  
END;
/



--------------------------------------------------------
--  DDL for Trigger INTERVENANT_RECHERCHE
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."INTERVENANT_RECHERCHE" 
  BEFORE INSERT OR UPDATE OF NOM_USUEL, NOM_PATRONYMIQUE, PRENOM ON INTERVENANT
  REFERENCING FOR EACH ROW
BEGIN

  :NEW.critere_recherche := ose_divers.str_reduce( :NEW.nom_usuel || ' ' || :NEW.nom_patronymique || ' ' || :NEW.prenom );
  
END;
/



--------------------------------------------------------
--  DDL for Trigger PFM_VOLUME_HORAIRE
--------------------------------------------------------
CREATE OR REPLACE TRIGGER "OSE"."PFM_VOLUME_HORAIRE" 
BEFORE UPDATE ON volume_horaire
FOR EACH ROW
BEGIN
    -- si on met en buffer le temps de contrôler le plafond
    IF :NEW.tem_plafond_fc_maj <> 1 THEN
      :NEW.buff_pfm_heures                := :OLD.heures;
      :NEW.buff_pfm_motif_non_paiement_id := :OLD.motif_non_paiement_id;
      :NEW.buff_pfm_histo_modification    := :OLD.histo_modification;
      :NEW.buff_pfm_histo_modificateur_id := :OLD.histo_modificateur_id;
    END IF;
END;
/




--------------------------------------------------------
--  CALCUL DES EFFECTIFS SUBALTERNES
--------------------------------------------------------

CREATE OR REPLACE TRIGGER chargens_maj_effectifs
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