---------------------------
--Nouveau SEQUENCE
--INDIC_MODIF_DOSSIER_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."INDIC_MODIF_DOSSIER_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 361 CACHE 20 NOORDER NOCYCLE;
---------------------------
--Modifié TABLE
--WF_ETAPE
---------------------------
ALTER TABLE "OSE"."WF_ETAPE" DROP CONSTRAINT "WF_ETAPE_CODE_UN";
ALTER TABLE "OSE"."WF_ETAPE" ADD CONSTRAINT "WF_ETAPE_CODE_UN" UNIQUE ("CODE","ANNEE_ID") ENABLE;

---------------------------
--Nouveau TABLE
--INDIC_MODIF_DOSSIER
---------------------------
  CREATE TABLE "OSE"."INDIC_MODIF_DOSSIER" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"INTERVENANT_ID" NUMBER(*,0),
	"ATTR_NAME" VARCHAR2(128 CHAR),
	"ATTR_OLD_SOURCE_NAME" VARCHAR2(128 CHAR),
	"ATTR_OLD_VALUE" VARCHAR2(1024 CHAR),
	"ATTR_NEW_SOURCE_NAME" VARCHAR2(128 CHAR),
	"ATTR_NEW_VALUE" VARCHAR2(1024 CHAR),
	"EST_CREATION_DOSSIER" NUMBER(*,0) DEFAULT 0 NOT NULL ENABLE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	CONSTRAINT "indic_diff_dossier_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "indic_diff_dossier_INT_FK" FOREIGN KEY ("INTERVENANT_ID")
	 REFERENCES "OSE"."INTERVENANT" ("ID") ENABLE
   );

---------------------------
--Modifié VIEW
--V_INDIC_ATTENTE_MEP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_ATTENTE_MEP" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TOTAL_HEURES_MEP"
  )  AS 
  with 
  -- total des heures comp ayant fait l'objet d'une *demande* de mise en paiement
  mep as (
    select intervenant_id, structure_id, sum(nvl(mep_heures, 0)) total_heures_mep
    from (
      -- enseignements
      select 
        fr.intervenant_id, 
        nvl(ep.structure_id, i.structure_id) structure_id, 
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service frs on mep.formule_res_service_id = frs.id
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction) and mep.date_mise_en_paiement is null -- si date_mise_en_paiement = null, c'est une demande
      union all
      -- referentiel
      select 
        fr.intervenant_id, 
        s.structure_id,
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service_ref frs on mep.formule_res_service_ref_id = frs.id
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction) and mep.date_mise_en_paiement is null -- si date_mise_en_paiement = null, c'est une demande
    )
    group by intervenant_id, structure_id
  )
select to_number(intervenant_id||structure_id) id, 2014 annee_id, intervenant_id, structure_id, total_heures_mep from mep;
---------------------------
--Modifié VIEW
--ADRESSE_INTERVENANT_PRINC
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."ADRESSE_INTERVENANT_PRINC" 
 ( "ID", "INTERVENANT_ID", "PRINCIPALE", "NO_VOIE", "NOM_VOIE", "BATIMENT", "MENTION_COMPLEMENTAIRE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_LIBELLE", "PAYS_CODE_INSEE", "TEL_DOMICILE", "SOURCE_ID", "SOURCE_CODE", "HISTO_CREATION", "HISTO_CREATEUR_ID", "HISTO_MODIFICATION", "HISTO_MODIFICATEUR_ID", "HISTO_DESTRUCTION", "HISTO_DESTRUCTEUR_ID", "TO_STRING"
  )  AS 
  select 
    a.ID,
    a.INTERVENANT_ID,
    a.PRINCIPALE,
    a.NO_VOIE,
    a.NOM_VOIE,
    a.BATIMENT,
    a.MENTION_COMPLEMENTAIRE,
    a.LOCALITE,
    a.CODE_POSTAL,
    a.VILLE,
    a.PAYS_LIBELLE,
    a.PAYS_CODE_INSEE,
    a.TEL_DOMICILE,
    a.SOURCE_ID,
    a.SOURCE_CODE,
    a.HISTO_CREATION,
    a.HISTO_CREATEUR_ID,
    a.HISTO_MODIFICATION,
    a.HISTO_MODIFICATEUR_ID,
    a.HISTO_DESTRUCTION,
    a.HISTO_DESTRUCTEUR_ID,
    ose_divers.formatted_adresse(
      a.NO_VOIE,
      a.NOM_VOIE,
      a.BATIMENT,
      a.MENTION_COMPLEMENTAIRE,
      a.LOCALITE,
      a.CODE_POSTAL,
      a.VILLE,
      a.PAYS_LIBELLE) to_string
  from adresse_intervenant a
  where id in (
    -- on ne retient que l'adresse principale si elle existe ou sinon la première adresse trouvée
    select id from (
      -- attribution d'un rang par intervenant aux adresses pour avoir la principale (éventuelle) en n°1
      select id, dense_rank() over(partition by intervenant_id order by principale desc) rang from adresse_intervenant
    ) 
    where rang = 1
  )
  and 1 = ose_divers.comprise_entre(a.HISTO_CREATION, a.HISTO_DESTRUCTION);
---------------------------
--Modifié TRIGGER
--SERVICE_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_CK"
  BEFORE INSERT OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  DECLARE 
  etablissement integer;
  res integer;
BEGIN
  
  etablissement := OSE_PARAMETRE.GET_ETABLISSEMENT();
  
  IF :NEW.etablissement_id = etablissement AND :NEW.element_pedagogique_id IS NULL THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être renseigné si le service est réalisé en interne.');
  END IF;

-- Mis en commentaire pour autoriser la saisie de service avant la saisie des données perso (dossier), c'est à dire avant de connaître le statut de l'intervenant :
--  IF OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service') = 0 THEN
--    raise_application_error(-20101, 'Il est impossible de saisir des services pour cet intervenant.');
--  END IF;

  IF :NEW.etablissement_id <> etablissement AND OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service_exterieur') = 0 THEN
    raise_application_error(-20101, 'Les intervenants vacataires n''ont pas la possibilité de renseigner des enseignements pris à l''extérieur.');
  END IF;

  IF :NEW.intervenant_id IS NOT NULL AND :NEW.element_pedagogique_id IS NOT NULL THEN
    SELECT
      count(*) INTO res
    FROM
      intervenant i,
      element_pedagogique ep
    WHERE
          i.id        = :NEW.intervenant_id
      AND ep.id       = :NEW.element_pedagogique_id
      AND ep.annee_id = i.annee_id
    ;
    
    IF 0 = res THEN -- années non concomitantes
      raise_application_error(-20101, 'L''année de l''intervenant ne correspond pas à l''année de l''élément pédagogique.');
    END IF;
  END IF;

  --IF :OLD.id IS NOT NULL AND ( :NEW.etablissement_id <> :OLD.etablissement_id OR :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id ) THEN
    --UPDATE volume_horaire SET histo_destruction = SYSDATE, histo_destructeur_id = :NEW.histo_modificateur_id WHERE service_id = :NEW.id;
  --END IF;

END;
/
---------------------------
--Modifié TRIGGER
--PJ_TRG_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PJ_TRG_DOSSIER"
  AFTER INSERT OR UPDATE OF STATUT_ID, PREMIER_RECRUTEMENT, RIB ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenantId numeric;
  tpjId numeric;
  TPJ_CODE_RIB varchar2(64) := 'RIB';
  ribImport varchar2(128);
BEGIN
  intervenantId := :NEW.intervenant_id;
  
  --dbms_output.put_line('PJ_TRG_DOSSIER');
  
  /**
   * Dans le cas d'une création de dossier ou si "Statut" ou "1er Recrutement" a changé, 
   * la liste des PJ attendues pour l'intervenant doit être mise à jour.
   */
  if inserting or :OLD.statut_id <> :NEW.statut_id or :OLD.premier_recrutement <> :NEW.premier_recrutement then
    --dbms_output.put_line('Statut ou 1er Recrut du dossier ' || :OLD.id || ' modifié...');
    --dbms_output.put_line('Statut     : ' || :OLD.statut_id           || ' -> ' || :NEW.statut_id);
    --dbms_output.put_line('1er Recrut : ' || :OLD.premier_recrutement || ' -> ' || :NEW.premier_recrutement);
    for t in (
      select id tpj_id from type_piece_jointe tpj where 1 = ose_divers.comprise_entre(tpj.histo_creation, tpj.histo_destruction)
    ) loop
      ose_pj.add_intervenant_to_update(intervenantId, t.tpj_id); 
    end loop;
  end if;
  
  /**
   * Si le RIB saisi diffère de celui importé, la PJ sera obligatoire.
   */
  select id into tpjId from type_piece_jointe where code = TPJ_CODE_RIB;
  select regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '') into ribImport from intervenant where id = intervenantId;
  if trim(:NEW.rib) <> ribImport then
    --dbms_output.put_line('RIB du dossier ' || :OLD.id || ' différent de celui importé : ' || trim(:NEW.rib) || ' <> ' || ribImport);
    --raise_application_error(-20000, 'RIB du dossier ' || :OLD.id || ' différent de celui importé : ' || trim(:NEW.rib) || ' <> ' || ribImport);
    ose_pj.add_intervenant_to_update(intervenantId, tpjId, 1); -- forcé à 1, i.e. obligatoire
  else
  /**
   * Si le RIB saisi égale celui importé, la PJ n'est plus requise.
   */
    --dbms_output.put_line('RIB du dossier ' || :OLD.id || ' identique à celui importé : ' || trim(:NEW.rib) || ' = ' || ribImport);
    ose_pj.add_intervenant_to_update(intervenantId, tpjId, -1); -- forcé à -1, i.e. non attendu
  end if;
END;
/

---------------------------
--Modifié PACKAGE
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS 

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

  FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;
  
  FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC;
  
  FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

  FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE, date_obs DATE DEFAULT NULL, inclusif NUMERIC DEFAULT 0 ) RETURN NUMERIC;

  PROCEDURE DO_NOTHING;

  FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC;

  FUNCTION ANNEE_UNIVERSITAIRE( date_ref DATE DEFAULT SYSDATE, mois_deb_au NUMERIC DEFAULT 9, jour_deb_au NUMERIC DEFAULT 1 ) RETURN NUMERIC;

  PROCEDURE SYNC_LOG( msg CLOB );

  FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2;
  
  FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2;
  
END OSE_DIVERS;
/


---------------------------
--Nouveau TRIGGER
--INDIC_TRG_MODIF_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."INDIC_TRG_MODIF_DOSSIER"
  AFTER INSERT OR UPDATE OF NOM_USUEL, NOM_PATRONYMIQUE, PRENOM, CIVILITE_ID, ADRESSE, RIB, DATE_NAISSANCE ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  DECLARE
  i integer := 1;
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
    left join adresse_intervenant_princ a on a.intervenant_id = i.id
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
      where INTERVENANT_ID = :NEW.intervenant_id 
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
      :NEW.intervenant_id, 
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



---------------------------
--Modifié PACKAGE BODY
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS

  --------------------------------------------------------------------------------------------------------------------------
  -- Moteur du workflow.
  --------------------------------------------------------------------------------------------------------------------------
  
  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow.
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN 
    MERGE INTO wf_tmp_intervenant t USING dual ON (t.intervenant_id = p_intervenant_id) WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID) VALUES (p_intervenant_id);
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la progression dans le workflow.
   */
  PROCEDURE Update_Intervenants_Etapes 
  IS
  BEGIN
    FOR ti IN (SELECT distinct * FROM wf_tmp_intervenant) LOOP
      --DBMS_OUTPUT.put_line ('wf_tmp_intervenant.intervenant_id = '||ti.intervenant_id);
      ose_workflow.Update_Intervenant_Etapes(ti.intervenant_id);
    END LOOP;
    DELETE FROM wf_tmp_intervenant;
  END;
  
  /**
   * Regénère la progression dans le workflow de tous les intervenants dont le statut autorise la saisie de service.
   */
  PROCEDURE Update_All_Intervenants_Etapes (p_annee_id NUMERIC DEFAULT 2014)
  IS
    CURSOR intervenant_cur IS 
      SELECT i.* FROM intervenant i 
      JOIN statut_intervenant si ON si.id = i.statut_id AND 1 = ose_divers.comprise_entre(si.histo_creation, si.histo_destruction) AND si.peut_saisir_service = 1
      WHERE i.annee_id = p_annee_id AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction);
  BEGIN
    FOR intervenant_rec IN intervenant_cur
    LOOP
      --DBMS_OUTPUT.put_line (intervenant_rec.nom_usuel || '(' || intervenant_rec.source_code || ')');
      ose_workflow.Update_Intervenant_Etapes(intervenant_rec.id);
    END LOOP;
  END;
  
  /**
   * Test
   */
  PROCEDURE Process_Intervenant_Etape (p_intervenant_id NUMERIC/*, p_structure_dependant NUMERIC*/) 
  IS
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
    parentId NUMERIC;
    intervenantEtapeIdPrec NUMERIC := 0;
  BEGIN    
    --
    -- Parcours des étapes.
    --
    FOR etape_rec IN (       
      select e.* from wf_etape e
      where e.code <> 'DEBUT' and e.code <> 'FIN' and e.annee_id = ( select annee_id from intervenant where id = p_intervenant_id ) 
      order by e.ordre
    )
    LOOP
      --
      -- Si l'étape n'est pas pertinente, on passe à la suivante.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN 
        CONTINUE;
      END IF;
      
      --
      -- La règle (fonction) de franchissement prend 2 arguments : l'id de l'intervenant (null interdit) et l'id de la structure (null accepté).
      -- Cette règle sera exécutée une fois avec un id de structure null (ce qui se traduit par "peu importe la structure"), puis
      -- autant de fois qu'il existe de structures d'enseignement dans le cas où l'étape est déclinable par structure.
      -- L'id null et les ids des structures sont stockés dans une liste qui sera parcourue plus loin.
      --
      l_structures_ids.DELETE;
      -- id structure null
      l_structures_ids(l_structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 AND etape_rec.STRUCTURES_IDS_FUNC IS NOT NULL THEN
        EXECUTE IMMEDIATE 'BEGIN ' || etape_rec.STRUCTURES_IDS_FUNC || '(:1); END;' USING p_intervenant_id;
      END IF;
      
      parentId := null;
        
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. l_structures_ids.COUNT - 1
      LOOP
        structure_id := l_structures_ids(i);
        DBMS_OUTPUT.put_line (etape_rec.libelle || ' : structures_ids('||i||') := ' || structure_id);
        
        --
        -- Interrogation de la règle de franchissement de l'étape.
        --
        IF etape_rec.FRANCH_FUNC IS NULL THEN
          franchie := 1;
        ELSE
          EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1, :2); END;' USING OUT franchie, p_intervenant_id, structure_id;
          --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
        END IF;
        
        courante := 0;
        atteignable := 0;
        
        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre, parent_id) SELECT 
          wf_intervenant_etape_id_seq.nextval, 
          p_intervenant_id, 
          etape_rec.id, 
          structure_id, 
          courante, 
          franchie, 
          atteignable, 
          ordre, 
          parentId
        FROM DUAL;
        
        -- mémorisation de l'id parent : c'est celui pour lequel aucune structure n'est spécifié
        if structure_id is null then
          parentId := wf_intervenant_etape_id_seq.currval;
        end if;
        
      END LOOP;
        
      ordre := ordre + 1;
      
    END LOOP;
  END;
  
  
  /**
   * Regénère la progression complète dans le workflow d'un intervenant.
   */
  PROCEDURE Update_Intervenant_Etapes (p_intervenant_id NUMERIC) 
  IS
    v_annee_id NUMERIC;
    structures_ids T_LIST_STRUCTURE_ID;
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
    exist_etapes NUMERIC;
  BEGIN
    --
    -- Année concernée.
    --
    select i.annee_id into v_annee_id from intervenant i where i.id = p_intervenant_id;
    
    --
    -- Création si besoin des étapes pour l'année concernée.
    --
    select count(*) into exist_etapes from wf_etape where annee_id = v_annee_id;
    if exist_etapes = 0 then
      insert into WF_ETAPE (ID,CODE,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURE_DEPENDANT,ORDRE,STRUCTURES_IDS_FUNC,ANNEE_ID)
        select wf_etape_id_seq.nextval, CODE,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURE_DEPENDANT,ORDRE,STRUCTURES_IDS_FUNC, v_annee_id from WF_ETAPE;
    end if;
    
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
    
    --
    -- Parcours des étapes de l'année concernée.
    --
    FOR etape_rec IN ( select * from wf_etape where annee_id = v_annee_id and code <> 'DEBUT' and code <> 'FIN' order by ordre )
    LOOP
      --
      -- Si l'étape n'est pas pertinente, on passe à la suivante.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN 
        CONTINUE;
      END IF;
      
      --
      -- La règle (fonction) de franchissement prend 2 arguments : l'id de l'intervenant (null interdit) et l'id de la structure (null accepté).
      -- Cette règle sera exécutée une fois avec un id de structure null (ce qui se traduit par "peu importe la structure"), puis
      -- autant de fois qu'il existe de structures d'enseignement dans le cas où l'étape est déclinable par structure.
      -- L'id null et les ids des structures sont stockés dans une liste qui sera parcourue plus loin.
      --
      l_structures_ids.DELETE;
      -- id structure null
      l_structures_ids(l_structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 AND etape_rec.STRUCTURES_IDS_FUNC IS NOT NULL THEN
        --ose_workflow.fetch_struct_ens_ids(p_intervenant_id, structures_ids);
        EXECUTE IMMEDIATE 'BEGIN ' || etape_rec.STRUCTURES_IDS_FUNC || '(:1); END;' USING p_intervenant_id;
      END IF;
      
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. l_structures_ids.COUNT - 1
      LOOP
        structure_id := l_structures_ids(i);
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' : structures_ids('||i||') := ' || structure_id);
        
        --
        -- Interrogation de la règle de franchissement de l'étape.
        --
        IF etape_rec.FRANCH_FUNC IS NULL THEN
          franchie := 1;
        ELSE
          EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1, :2); END;' USING OUT franchie, p_intervenant_id, structure_id;
          --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
        END IF;
                        
        atteignable := 1;
        
        --
        -- Si l'étape courante n'a pas encore été trouvée.
        --
        IF courante_trouvee = 0 THEN 
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            -- l'étape marquée "courante" est la 1ère étape non franchie
            courante := 1;
            courante_trouvee := etape_rec.id;
          END IF;
        --
        -- Si l'étape courante a été trouvée et que l'on se situe dessus.
        --
        ELSIF courante_trouvee = etape_rec.id THEN
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            courante := 1;
          END IF;
        --
        -- Une étape située après l'étape courante est forcément "non courante".
        --
        ELSE
          courante := 0;
          atteignable := 0;
        END IF;
                        
        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre) 
          SELECT wf_intervenant_etape_id_seq.nextval, p_intervenant_id, etape_rec.id, structure_id, courante, franchie, atteignable, ordre FROM DUAL;
        
        ordre := ordre + 1;
      END LOOP;
      
    END LOOP;
  END;
  
  /**
   * Fetch les ids des structures d'enseignement PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ens_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_ids_tvh('PREVU', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ens_realise_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_ids_tvh('REALISE', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement de l'intervenant spécifié, 
   * pour le type de volume horaire spécifié.
   */
  PROCEDURE fetch_struct_ens_ids_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    i := l_structures_ids.COUNT;
    FOR d IN (
      SELECT distinct ep.structure_id 
      FROM element_pedagogique ep
      JOIN service s on s.element_pedagogique_id = ep.id /*AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE()*/ AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id
    ) LOOP
      l_structures_ids(i) := d.structure_id;
      i := i + 1;
    END LOOP;
  END;
  
  /**
   * Fetch les ids des structures du référentiel PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ref_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ref_ids_tvh('PREVU', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures du référentiel REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ref_realise_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ref_ids_tvh('REALISE', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures du référentiel de l'intervenant spécifié, 
   * pour le seul type de volume horaire spécifié.
   */
  PROCEDURE fetch_struct_ref_ids_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    i := l_structures_ids.COUNT;
    FOR d IN (
      SELECT distinct structure_id FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE()*/ AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    ) LOOP
      l_structures_ids(i) := d.structure_id;
      i := i + 1;
    END LOOP;
  END;
  
  
  /**
   * Fetch les ids des structures d'enseignement + les ids des structures du référentiel PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ensref_ids          (p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    fetch_struct_ens_ids (p_intervenant_id);
    fetch_struct_ref_ids (p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement + les ids des structures du référentiel REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ensref_realis_ids  (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_realise_ids (p_intervenant_id);
    fetch_struct_ref_realise_ids (p_intervenant_id);
  END;
  
  
  
  
  
  
  
  --------------------------------------------------------------------------------------------------------------------------
  -- Règles de pertinence et de franchissement des étapes.
  --------------------------------------------------------------------------------------------------------------------------
  
  /**
   *
   */
  FUNCTION peut_saisir_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_dossier INTO res FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM dossier d where d.intervenant_id = p_intervenant_id and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction);
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION dossier_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v 
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP' 
    WHERE 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- teste le statut de l'intervenant issu de la table INTERVENANT
    SELECT si.peut_saisir_service INTO res 
    FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    
    if res > 0 then
      RETURN res;
    end if;
    
    -- teste sinon le statut saisi dans l'éventuel dossier
    SELECT count(*) INTO res 
    FROM dossier d
    join statut_intervenant si on si.id = d.statut_id and si.peut_saisir_service = 1
    WHERE d.intervenant_id = p_intervenant_id and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction);
    
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_services_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_services_realises (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_services_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_services_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    ELSE
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id 
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/
      AND ep.structure_id = p_structure_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;
  
  /**
   *
   */
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
  BEGIN
    RETURN service_valide_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION service_realise_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
  BEGIN
    RETURN service_valide_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION service_valide_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR service_cur IS 
      SELECT s.*, ep.structure_id
      FROM service s 
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON vh.type_volume_horaire_id = tvh.id AND tvh.code = p_type_volume_horaire_code
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    FOR service_rec IN service_cur
    LOOP
      IF p_structure_id IS NULL THEN
        -- si aucune structure n'est spécifiée, on se contente du moindre service trouvé
        return 1;
      END IF;
      -- si une structure précise est spécifiée, on se contente du moindre service trouvé concernant cette structure d'enseignement
      IF service_rec.structure_id = p_structure_id THEN
        return 1;
      END IF;
    END LOOP;
    
    RETURN 0;
  END;
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_cloturer_realise              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    estPerm numeric;
  BEGIN
    select count(*) into estPerm
    from type_intervenant ti 
    join statut_intervenant si on si.TYPE_INTERVENANT_ID = ti.id 
    join intervenant i on i.STATUT_ID = si.id and i.id = p_intervenant_id
    where ti.code = 'P';
    
    return estPerm;
  END;
  
  /**
   *
   */
  FUNCTION realise_cloture                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    found numeric;
  BEGIN
    select count(*) into found 
    from validation v 
    join type_validation tv on tv.id = v.type_validation_id and tv.code = 'CLOTURE_REALISE'
    where 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    and v.intervenant_id = p_intervenant_id;
    
    return case when found > 0 then 1 else 0 end;
  END;
  
  
  
  
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_referentiel INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_referentiel_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel_realise (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_referentiel_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    ELSE
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND s.structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN referentiel_valide_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION referentiel_realise_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN referentiel_valide_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION referentiel_valide_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR ref_cur IS 
      SELECT s.* FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON vh.type_volume_horaire_id = tvh.id AND tvh.code = p_type_volume_horaire_code
      JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    ref_rec ref_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    -- si aucun référentiel, la validation doit être considérée comme faite
    if ose_workflow.possede_referentiel_tvh(p_type_volume_horaire_code, p_intervenant_id, p_structure_id) < 1 then
      return 1;
    end if;
  
    IF p_structure_id IS NULL THEN
      -- si aucune structure n'est spécifiée, on se contente du moindre référentiel trouvé
      OPEN ref_cur;
      FETCH ref_cur INTO ref_rec;
      IF ref_cur%FOUND = TRUE THEN
        res := 1;
      END IF;
      CLOSE ref_cur;
    ELSE
      -- si une structure précise est spécifiée, on se contente du moindre référentiel trouvé concernant cette structure d'enseignement
      FOR ref_rec IN ref_cur
      LOOP
        IF ref_rec.structure_id = p_structure_id THEN
          res := 1;
          EXIT;
        END IF;
      END LOOP;
    END IF;
    RETURN res;
  END;
  
  
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_pj (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_piece_jointe_statut tpjs 
    JOIN statut_intervenant si on tpjs.statut_intervenant_id = si.id 
    JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION peut_valider_pj (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    res := peut_saisir_pj(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
  
    -- nombre de pj fournies (avec fichier)
    select count(*) into res
    from PIECE_JOINTE_FICHIER pjf
    join PIECE_JOINTE pj ON pjf.piece_jointe_id = pj.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
    join dossier d on pj.dossier_id = d.id and d.intervenant_id = p_intervenant_id and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction);
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pj_oblig_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- verif existence de données perso
    res := possede_dossier(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
  
    SELECT count(*) INTO res FROM (WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.id) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON I.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          inner join piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant, VALIDEES OU NON
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.ID) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON IE.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          I.nom_usuel,
          COALESCE(A.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(F.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      left join ATTENDU_OBLIGATOIRE A on a.intervenant_id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  F ON F.INTERVENANT_ID = i.id
      WHERE i.ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU = 0 OR NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pj_oblig_validees (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- verif existence de données perso
    res := possede_dossier(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
    
    SELECT count(*) INTO res FROM (
      WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.id) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON I.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          inner join piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant et VALIDEES 
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.ID) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON IE.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE pj.OBLIGATOIRE = 1
          and pj.validation_id is not null
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          I.nom_usuel,
          COALESCE(A.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(F.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      left join ATTENDU_OBLIGATOIRE A on a.intervenant_id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  F ON F.INTERVENANT_ID = i.id
      WHERE i.ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU = 0 OR NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas 
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_RESTREINT'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 
    AND i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas 
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_ACADEMIQUE'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 
    AND i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    code VARCHAR2(64) := 'CONSEIL_RESTREINT';
  BEGIN
    WITH 
    composantes_enseign AS (
        -- composantes d'enseignement par intervenant
        SELECT DISTINCT i.ID, i.source_code, ep.structure_id
        FROM element_pedagogique ep
        INNER JOIN service s on s.element_pedagogique_id = ep.id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
        INNER JOIN intervenant i ON i.ID = s.intervenant_id AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
        INNER JOIN STRUCTURE comp ON comp.ID = ep.structure_id AND 1 = ose_divers.comprise_entre(comp.histo_creation, comp.histo_destruction)
        WHERE 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND ep.structure_id = p_structure_id)
    ),
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND 1 = ose_divers.comprise_entre(ta.histo_creation, ta.histo_destruction)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND 1 = ose_divers.comprise_entre(tas.histo_creation, tas.histo_destruction)
        WHERE 1 = ose_divers.comprise_entre(a.histo_creation, a.histo_destruction)
        AND ta.code = code
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND A.structure_id = p_structure_id)
    ), 
    v_agrement AS (
      -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE (
      -- si aucune structure précise n'est spécifiée, on ne retient que les intervenants qui ont au moins un d'agrément CR
      p_structure_id IS NULL AND nb_agrem > 0
      OR 
      -- si une structure précise est spécifiée, on ne retient que les intervenants qui ont (au moins) autant d'agréments CR que de composantes d'enseignement
      p_structure_id IS NOT NULL AND v.nb_comp <= nb_agrem 
    ) 
    AND v.id = p_intervenant_id ;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    v_code VARCHAR2(64) := 'CONSEIL_ACADEMIQUE';
  BEGIN
    WITH 
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND 1 = ose_divers.comprise_entre(ta.histo_creation, ta.histo_destruction)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND 1 = ose_divers.comprise_entre(tas.histo_creation, tas.histo_destruction)
        WHERE 1 = ose_divers.comprise_entre(a.histo_creation, a.histo_destruction)
        AND ta.code = v_code
    ), 
    v_agrement AS (
      -- nombres d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE nb_agrem > 0
    AND v.id = p_intervenant_id ;
    
    RETURN res;
  END;
  
  
   
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION necessite_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_avoir_contrat INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM contrat c
    JOIN validation v ON c.validation_id = v.id AND 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    WHERE 1 = ose_divers.comprise_entre(c.histo_creation, c.histo_destruction)
    AND c.intervenant_id = p_intervenant_id
    AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND c.STRUCTURE_ID = p_structure_id) 
    AND ROWNUM = 1;
    
    RETURN res;
  END;






  /**
   *
   */
  FUNCTION peut_demander_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- si l'intervenant possède déjà des demande de MEP, il peut demander des MEP
    if possede_demande_mep(p_intervenant_id, p_structure_id) = 1 then
      return 1;
    end if;
  
    IF p_structure_id IS NULL THEN
      select count(*) into res from v_indic_attente_demande_mep where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_indic_attente_demande_mep where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION possede_demande_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from v_total_demande_mep_structure where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_total_demande_mep_structure where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION peut_saisir_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_demande_mep(p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION possede_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where periode_paiement_id is not null and intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where periode_paiement_id is not null and intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;


END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_PJ
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_PJ" AS
  
  /**
   * Inscription des infos utiles à la mise à jour de la liste des PJ attendues.
   */
  PROCEDURE add_intervenant_to_update (intervenantId IN NUMERIC, tpjId IN NUMERIC, forceObligatoire IN NUMERIC default null)
  IS
  BEGIN 
    MERGE INTO pj_tmp_intervenant t USING dual ON (t.intervenant_id = intervenantId and t.type_piece_jointe_id = tpjId and t.obligatoire = forceObligatoire) 
      WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID, type_piece_jointe_id, obligatoire) VALUES (intervenantId, tpjId, forceObligatoire);
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la liste des PJ attendues.
   */
  PROCEDURE update_intervenants_pj
  IS
    dossierId numeric;
  BEGIN
    FOR ti IN (SELECT distinct * FROM pj_tmp_intervenant order by intervenant_id, type_piece_jointe_id) LOOP
      -- recherche du dossier de l'intervenant spécifié dans la table temporaire
      select id into dossierId from dossier where intervenant_id = ti.intervenant_id and 1 = ose_divers.comprise_entre(histo_creation, histo_destruction);
      -- mise à jour
      ose_pj.update_pj(ti.type_piece_jointe_id, dossierId, ti.obligatoire);
    END LOOP;
    --DELETE FROM pj_tmp_intervenant;
  END;

  /**
   * Mise à jour de la liste des PJ attendues pour le type de PJ et le dossier spécifiés.
   */
  procedure update_pj(tpjId IN numeric, dossierId IN numeric, forceObligatoire IN numeric default null)
  is
    oblig numeric;
    found numeric;
  begin 
    --dbms_output.put_line('update_pj('||tpjId||', '||dossierId||', '||forceObligatoire||')');
      
    if forceObligatoire is null then
    -- pas de forçage : recherche du caractère obligatoire du type de PJ pour le dossier.
    
      select is_tpj_obligatoire(tpjId, dossierId) into oblig from dual;
      dbms_output.put_line('< is_tpj_obligatoire : '||tpjId||', '||dossierId||', '||oblig);
      
      -- La fonction is_tpj_obligatoire() renvoie -1 lorsque le type de PJ n'est pas attendu.
      -- Dans ce cas, on supprime/historise la PJ ssi son caractère obligatoire n'est pas forcé ET il n'existe pas de fichier déposé.
      if oblig = -1 then
        /*update piece_jointe pj set histo_destructeur_id = ose_parametre.get_ose_user(), histo_destruction = sysdate 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
        and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
        and pj.force = 0;*/
        delete from piece_jointe pj 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
        and not exists (
          select * from piece_jointe_fichier pjf 
          join fichier f on f.id = pjf.fichier_id and 1 = ose_divers.comprise_entre(f.histo_creation, f.histo_destruction)
          where pjf.piece_jointe_id = pj.id 
        )
        and pj.force = 0;
        
        return; -- terminé
      end if;
      
    elsif forceObligatoire = -1 then
    -- forçage à -1 (type de PJ non attendu) : on supprime, à condition qu'il n'existe pas de fichier déposé.
    
        /*update piece_jointe pj set histo_destructeur_id = ose_parametre.get_ose_user(), histo_destruction = sysdate 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
        and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
        and pj.force = 1;*/
        delete from piece_jointe pj 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId  
        and not exists (
          select * from piece_jointe_fichier pjf 
          join fichier f on f.id = pjf.fichier_id and 1 = ose_divers.comprise_entre(f.histo_creation, f.histo_destruction)
          where pjf.piece_jointe_id = pj.id 
        );
        
        return; -- terminé
        
    else
    -- forçage à 0 (facultatif) ou 1 (obligatoire)
    
      oblig := forceObligatoire;
      
    end if;
    
    -- Recherche dans PIECE_JOINTE s'il existe un enregistrement pour le type de PJ et le dossier spécifiés
    select count(*) into found from piece_jointe pj where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
      and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction);
    -- Si oui, mise à jour
    if found > 0 then
      --dbms_output.put_line('update_pj() : update : dossier '||dossierId||' tpj '||tpjId||' : '||oblig||')');
      update piece_jointe pj 
      set pj.obligatoire = oblig, 
          pj.force = case when forceObligatoire is not null then 1 else 0 end,
          histo_modificateur_id = ose_parametre.get_ose_user(), 
          histo_modification = sysdate 
      where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
      and pj.obligatoire <> oblig
      and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
      and 1 = case when pj.force = 0 or pj.force = 1 and forceObligatoire is not null then 1 else 0 end; -- un caractère forçé ne peut être modifié que par forçage!
    -- Si non, insertion
    else
      --dbms_output.put_line('update_pj() : insert : dossier '||dossierId||' tpj '||tpjId||' : '||oblig||')');
      insert into piece_jointe pj (id, dossier_id, type_piece_jointe_id, obligatoire, force, histo_createur_id, histo_modificateur_id) 
      values (
        piece_jointe_id_seq.nextval, 
        dossierId, tpjId, 
        oblig, case when forceObligatoire is not null then 1 else 0 end,
        ose_parametre.get_ose_user(), 
        ose_parametre.get_ose_user()); 
    end if;
  end;
  
  /**
   * Recherche du caractère obligatoire d'un type de PJ pour un dossier.
   */
  function is_tpj_obligatoire(tpjId IN numeric, dossierId IN numeric) return numeric 
  is 
    intervenantId numeric;
    statutId numeric;
    premierRecrutement numeric;
    TPJ_CODE_RIB type_piece_jointe.code%type := 'RIB';
    tpjCode type_piece_jointe.code%type;
    ribDossier dossier.rib%type;
    ribImporte dossier.rib%type;
    obligatoire numeric := -1; -- non attendu (i.e. ni obligatoire, ni facultatif)
  begin
    -- recherche de l'intervenant extérieur correspondant au dossier, du statut et du témoin "1er recrutement" dans le dossier
    select intervenant_id, statut_id, PREMIER_RECRUTEMENT, rib into intervenantId, statutId, premierRecrutement, ribDossier from dossier d where d.id = dossierId;
    if intervenantId is null then
      return -1;
    end if;
    
    -- recherche du caractère obligatoire du type de PJ spécifié.
    for r in ( -- astuce: utilisation d'une boucle pour parer au cas où le select ne ramène aucune ligne.
      select tpjs.obligatoire into obligatoire
      from type_piece_jointe_statut   tpjs
      join type_piece_jointe          tpj       on tpj.id = tpjs.type_piece_jointe_id and tpj.id = tpjId
      join statut_intervenant         si        on tpjs.statut_intervenant_id = si.id and si.id = statutId
      LEFT JOIN V_PJ_HEURES           vheures   ON vheures.INTERVENANT_ID = intervenantId
      where 
        tpjs.PREMIER_RECRUTEMENT = premierRecrutement AND 
        (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) > tpjs.SEUIL_HETD)
    ) loop
      obligatoire := r.obligatoire;
      exit; -- en réalité, il ne peut y avoir qu'une ligne donc on sort de la boucle.
    end loop;
    
    -- cas particulier du RIB : si le RIB saisi dans le dossier diffère de celui importé, la PJ est obligatoire.
    select code into tpjCode from type_piece_jointe where id = tpjId;
    if tpjCode = TPJ_CODE_RIB then
      select regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '') into ribImporte from intervenant where id = intervenantId;
      --select ose_divers.formatted_rib(bic, iban) into ribImporte from intervenant where id = intervenantId;
      if ribDossier is null or ribDossier <> ribImporte then
        obligatoire := 1; -- forcé à 1, i.e. obligatoire
      end if;
      
      --return obligatoire;
    end if;
    
    return obligatoire;
  end;

END OSE_PJ;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_DIVERS" AS

FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
  statut statut_intervenant%rowtype;
  itype  type_intervenant%rowtype;
  res NUMERIC;
BEGIN
  res := 1;
  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
  SELECT ti.* INTO itype  FROM type_intervenant ti JOIN intervenant i ON i.type_id = ti.id WHERE i.id = intervenant_id;
  
  /* DEPRECATED */
  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;
  IF 'saisie_service' = privilege_name THEN
    res := statut.peut_saisir_service;
    RETURN res;
  ELSIF 'saisie_service_exterieur' = privilege_name THEN
    --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
    RETURN res;
  ELSIF 'saisie_service_referentiel' = privilege_name THEN
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
    RETURN res;
  ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
    res := 1;
    RETURN res;
  ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
    res := statut.peut_saisir_motif_non_paiement;
    RETURN res;
  END IF;
  /* FIN DE DEPRECATED */
  
  SELECT
    count(*)
  INTO
    res
  FROM
    intervenant i
    JOIN statut_privilege sp ON sp.statut_id = i.statut_id
    JOIN privilege p ON p.id = sp.privilege_id
    JOIN categorie_privilege cp ON cp.id = p.categorie_id
  WHERE
    i.id = INTERVENANT_HAS_PRIVILEGE.intervenant_id
    AND cp.code || '-' || p.code = privilege_name;
    
  RETURN res;
END;

FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2 AS
  l_return CLOB:='';
  l_temp CLOB;
  TYPE r_cursor is REF CURSOR;
  rc r_cursor;
BEGIN
  OPEN rc FOR i_query;
  LOOP
    FETCH rc INTO L_TEMP;
    EXIT WHEN RC%NOTFOUND;
    l_return:=l_return||L_TEMP||i_seperator;
  END LOOP;
  RETURN RTRIM(l_return,i_seperator);
END;

FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant_permanent WHERE id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.non_autorise = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.peut_saisir_service = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC AS
BEGIN
  IF 1 <> gtf_pertinence_niveau OR niveau IS NULL OR niveau < 1 OR gtf_id < 1 THEN RETURN NULL; END IF;
  RETURN gtf_id * 256 + niveau;
END;

FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC AS
  RESULTAT NUMERIC;
BEGIN
  IF structure_testee = structure_cible THEN RETURN 1; END IF;
  
  select count(*) into resultat
  from structure
  WHERE structure.id = structure_testee
  start with parente_id = structure_cible
  connect by parente_id = prior id;

  RETURN RESULTAT;
END;

FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB IS
BEGIN
  RETURN utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai')));
END;

FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC IS
BEGIN
  IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
  RETURN 0;
END;

FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC IS
BEGIN
  RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
END;

FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE, date_obs DATE DEFAULT NULL, inclusif NUMERIC DEFAULT 0 ) RETURN NUMERIC IS
  d_deb DATE;
  d_fin DATE;
  d_obs DATE;
  res NUMERIC;
BEGIN
  IF inclusif = 1 THEN
    d_obs := TRUNC( COALESCE( d_obs     , SYSDATE ) );
    d_deb := TRUNC( COALESCE( date_debut, d_obs   ) );
    d_fin := TRUNC( COALESCE( date_fin  , d_obs   ) );
    IF d_obs BETWEEN d_deb AND d_fin THEN
      RETURN 1;
    ELSE
      RETURN 0;
    END IF;
  ELSE
    d_obs := TRUNC( COALESCE( d_obs, SYSDATE ) );
    d_deb := TRUNC( date_debut );
    d_fin := TRUNC( date_fin   );
    
    IF d_deb IS NOT NULL AND NOT d_deb <= d_obs THEN
      RETURN 0;
    END IF;
    IF d_fin IS NOT NULL AND NOT d_obs < d_fin THEN
      RETURN 0;
    END IF;
    RETURN 1;
  END IF;
END;

PROCEDURE DO_NOTHING IS
BEGIN
  RETURN;
END;

FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT count(*) INTO res FROM
    validation v
    JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
  WHERE
    1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction );
  RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
END;


PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 15 ) IS
  nt FLOAT;
  bi FLOAT;
  bc FLOAT;
  ba FLOAT;
  reste FLOAT;
BEGIN
  bi := eff_fi * fi;
  bc := eff_fc * fc;
  ba := eff_fa * fa;
  nt := bi + bc + ba;

  IF nt = 0 THEN -- au cas ou, alors on ne prend plus en compte les effectifs!!
    bi := fi;
    bc := fc;
    ba := fa;
    nt := bi + bc + ba;
  END IF;
  
  IF nt = 0 THEN -- toujours au cas ou...
    bi := 1;
    bc := 0;
    ba := 0;
    nt := bi + bc + ba;
  END IF;

  -- Calcul
  r_fi := bi / nt;
  r_fc := bc / nt;
  r_fa := ba / nt;

  -- Arrondis
  r_fi := ROUND( r_fi, arrondi );
  r_fc := ROUND( r_fc, arrondi );
  r_fa := ROUND( r_fa, arrondi );

  -- détermination du reste
  reste := 1 - r_fi - r_fc - r_fa;

  -- répartition éventuelle du reste
  IF reste <> 0 THEN
    IF r_fi > 0 THEN r_fi := r_fi + reste;
    ELSIF r_fc > 0 THEN r_fc := r_fc + reste;
    ELSE r_fa := r_fa + reste; END IF;
  END IF;

END;


FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ri;
END;
  
FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN rc;
END;
  
FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ra;
END;

FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT id INTO res FROM structure WHERE niveau = 1 AND ROWNUM = 1;
  RETURN res;
END;

PROCEDURE SYNC_LOG( msg CLOB ) IS
BEGIN
  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
END;

FUNCTION ANNEE_UNIVERSITAIRE( date_ref DATE DEFAULT SYSDATE, mois_deb_au NUMERIC DEFAULT 9, jour_deb_au NUMERIC DEFAULT 1 ) RETURN NUMERIC IS
  annee_ref NUMERIC;
  mois_ref NUMERIC;
  jour_ref NUMERIC;
BEGIN
  annee_ref := to_number(to_char(date_ref, 'yyyy'));
  mois_ref  := to_number(to_char(date_ref, 'mm'));
  jour_ref  := to_number(to_char(date_ref, 'dd'));
  
  IF jour_ref < jour_deb_au THEN mois_ref := mois_ref - 1; END IF;
  IF mois_ref < mois_deb_au THEN annee_ref := annee_ref - 1; END IF;
  
  RETURN annee_ref;
END;

FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  if bic is null and iban is null then
    return null;
  end if;
  RETURN regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '');
END;

FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2
IS
BEGIN
  return
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' FROM REPLACE(', ' || NVL(no_voie,'#') || ', ' || NVL(nom_voie,'#') || ', ' || NVL(batiment,'#') || ', ' || NVL(mention_complementaire,'#'), ', #', ''))) ||
    -- saut de ligne complet
    chr(13) || chr(10) ||
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' FROM REPLACE(', ' || NVL(localite,'#') || ', ' || NVL(code_postal,'#') || ', ' || NVL(ville,'#') || ', ' || NVL(pays_libelle,'#'), ', #', '')));
END;

END OSE_DIVERS;
/

   
   
   
DROP INDEX DEPARTEMENT_HDFK;
DROP INDEX EIE_FK;
DROP INDEX NOTIF_INDICATEUR_UFK;
DROP INDEX WF_INTERVENANT_ETAPE_EFK;
DROP INDEX AFFECTATION_STRUCTURE_FK;
DROP INDEX TYPE_MODULATEUR_STRUCTURE_HCFK;
DROP INDEX SERVICE_REFERENTIEL_HCFK;
DROP INDEX GROUPE_HCFK;
DROP INDEX TYPE_STRUCTURE_HDFK;
DROP INDEX INTERVENANT_PERMANENT_HDFK;
DROP INDEX FRES_ETAT_VOLUME_HORAIRE_FK;
DROP INDEX CCEP_TYPE_HEURES_FK;
DROP INDEX EPS_FK;
DROP INDEX GROUPE_HDFK;
DROP INDEX AGREMENT_HCFK;
DROP INDEX DOTATION_TYPE_DOTATION_FK;
DROP INDEX PERSONNEL_HMFK;
DROP INDEX ADRESSE_STRUCTURE_HCFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_PERIODE_FK;
DROP INDEX DOSSIER_HCFK;
DROP INDEX EFFECTIFS_HDFK;
DROP INDEX EMPLOYEUR_HCFK;
DROP INDEX NOTIF_INDICATEUR_SFK;
DROP INDEX SITUATION_FAMILIALE_HMFK;
DROP INDEX AFFECTATION_PERSONNEL_FK;
DROP INDEX TYPE_HEURES_TYPE_HEURES_FK;
DROP INDEX FICHIER_VALID_FK;
DROP INDEX ETAPE_SOURCE_FK;
DROP INDEX CORPS_HDFK;
DROP INDEX STATUT_INTERVENANT_HDFK;
DROP INDEX ADRESSE_STRUCTURE_HMFK;
DROP INDEX CONTRAT_TYPE_CONTRAT_FK;
DROP INDEX IERS_FK;
DROP INDEX AFFECTATION_R_HCFK;
DROP INDEX AFFECTATION_R_SOURCE_FK;
DROP INDEX PARAMETRE_HMFK;
DROP INDEX FRES_TYPE_VOLUME_HORAIRE_FK;
DROP INDEX CORPS_HCFK;
DROP INDEX ETAPE_HDFK;
DROP INDEX SERVICE_REFERENTIEL_HMFK;
DROP INDEX EMPLOI_HDFK;
DROP INDEX INTERVENANT_SOURCE_FK;
DROP INDEX IE_SOURCE_FK;
DROP INDEX VOLUME_HORAIRE_HCFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_SOURCE_FK;
DROP INDEX MISE_EN_PAIEMENT_VALIDATION_FK;
DROP INDEX MOTIF_NON_PAIEMENT_HDFK;
DROP INDEX TYPE_POSTE_HDFK;
DROP INDEX DOTATION_STRUCTURE_FK;
DROP INDEX MEP_TYPE_HEURES_FK;
DROP INDEX GROUPE_TYPE_FORMATION_HCFK;
DROP INDEX GROUPE_TYPE_INTERVENTION_FK;
DROP INDEX SR_IP_FK;
DROP INDEX DEPARTEMENT_HMFK;
DROP INDEX DISCIPLINE_HDFK;
DROP INDEX TPJS_STATUT_INTERVENANT_FK;
DROP INDEX TYPE_AGREMENT_HCFK;
DROP INDEX TYPE_VALIDATION_HMFK;
DROP INDEX ROLE_PERIMETRE_FK;
DROP INDEX STRUCTURE_SOURCE_FK;
DROP INDEX ELEMENT_PEDAGOGIQUE_ANNEE_FK;
DROP INDEX SITUATION_FAMILIALE_HDFK;
DROP INDEX PIECE_JOINTE_HDFK;
DROP INDEX GTYPE_FORMATION_SOURCE_FK;
DROP INDEX VOLUME_HORAIRE_ENS_HMFK;
DROP INDEX TYPE_AGREMENT_STATUT_HCFK;
DROP INDEX STRUCTURE_ETABLISSEMENT_FK;
DROP INDEX TYPE_RESSOURCE_HCFK;
DROP INDEX CORPS_HMFK;
DROP INDEX TYPE_INTERVENTION_HCFK;
DROP INDEX FRVHR_FORMULE_RESULTAT_FK;
DROP INDEX MOTIF_MODIFICATION_SERVIC_HDFK;
DROP INDEX ELEMENT_DISCIPLINE_HMFK;
DROP INDEX MODULATEUR_HMFK;
DROP INDEX ELEMENT_MODULATEUR_HCFK;
DROP INDEX ETABLISSEMENT_HDFK;
DROP INDEX REGIME_SECU_HMFK;
DROP INDEX CCEP_SOURCE_FK;
DROP INDEX TYPE_DOTATION_SOURCE_FK;
DROP INDEX CENTRE_COUT_HCFK;
DROP INDEX PERIODE_HMFK;
DROP INDEX NOTIF_INDICATEUR_IFK;
DROP INDEX CENTRE_COUT_EP_HDFK;
DROP INDEX PJ_TYPE_PIECE_JOINTE_FK;
DROP INDEX EFFECTIFS_HMFK;
DROP INDEX ETAPE_STRUCTURE_FK;
DROP INDEX DEPARTEMENT_SOURCE_FK;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HCFK;
DROP INDEX TYPE_INTERVENTION_STRUCTU_HMFK;
DROP INDEX PERSONNEL_HDFK;
DROP INDEX EMPLOYEURS_EMPLOYEURS_FK;
DROP INDEX ADRESSE_STRUCTURE_SOURCE_FK;
DROP INDEX CONTRAT_HDFK;
DROP INDEX ELEMENT_DISCIPLINE_HDFK;
DROP INDEX EMPLOYEUR_HMFK;
DROP INDEX TAUX_HORAIRE_HETD_HMFK;
DROP INDEX PAYS_HMFK;
DROP INDEX FICHIER_HMFK;
DROP INDEX TYPE_MODULATEUR_HDFK;
DROP INDEX GROUPE_TYPE_FORMATION_HMFK;
DROP INDEX PARAMETRE_HDFK;
DROP INDEX MODULATEUR_HCFK;
DROP INDEX MISE_EN_PAIEMENT_HCFK;
DROP INDEX PERSONNEL_CIVILITE_FK;
DROP INDEX TYPE_FORMATION_HCFK;
DROP INDEX AGREMENT_HDFK;
DROP INDEX TYPE_HEURES_HCFK;
DROP INDEX FONCTION_REFERENTIEL_HMFK;
DROP INDEX FRVHR_VOLUME_HORAIRE_REF_FK;
DROP INDEX TIS_TYPE_INTERVENTION_FK;
DROP INDEX FONCTION_REFERENTIEL_HCFK;
DROP INDEX IP_SOURCE_FK;
DROP INDEX PERIODE_HCFK;
DROP INDEX EFFECTIFS_HCFK;
DROP INDEX EMPLOYEUR_HDFK;
DROP INDEX CENTRE_COUT_TYPE_RESSOURCE_FK;
DROP INDEX TYPE_HEURES_HMFK;
DROP INDEX CENTRE_COUT_EP_HCFK;
DROP INDEX ROLE_HCFK;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HMFK;
DROP INDEX GROUPE_TYPE_FORMATION_HDFK;
DROP INDEX DS_MDS_FK;
DROP INDEX ETABLISSEMENT_HMFK;
DROP INDEX TAS_STATUT_INTERVENANT_FK;
DROP INDEX FRVH_VOLUME_HORAIRE_FK;
DROP INDEX FRS_SERVICE_FK;
DROP INDEX CHEMIN_PEDAGOGIQUE_ETAPE_FK;
DROP INDEX PAYS_HCFK;
DROP INDEX ETAPE_DOMAINE_FONCTIONNEL_FK;
DROP INDEX REGIME_SECU_HCFK;
DROP INDEX TYPE_AGREMENT_STATUT_HMFK;
DROP INDEX VALIDATION_HCFK;
DROP INDEX TYPE_INTERVENTION_STRUCTU_HCFK;
DROP INDEX SR_STRUCTURE_FK;
DROP INDEX TYPE_INTERVENTION_HMFK;
DROP INDEX TYPE_INTERVENTION_STRUCTU_HDFK;
DROP INDEX MISE_EN_PAIEMENT_HDFK;
DROP INDEX INTERVENANT_EXTERIEUR_HCFK;
DROP INDEX MOTIF_MODIFICATION_SERVIC_HCFK;
DROP INDEX MODULATEUR_TYPE_MODULATEUR_FK;
DROP INDEX MODIFICATION_SERVICE_DU_HMFK;
DROP INDEX GROUPE_HMFK;
DROP INDEX ETABLISSEMENT_HCFK;
DROP INDEX ETAPE_TYPE_FORMATION_FK;
DROP INDEX TYPE_DOTATION_HCFK;
DROP INDEX VHENS_TYPE_INTERVENTION_FK;
DROP INDEX INTERVENANT_STATUT_FK;
DROP INDEX ELEMENT_TAUX_REGIMES_HMFK;
DROP INDEX STRUCTURE_HDFK;
DROP INDEX FRS_FORMULE_RESULTAT_FK;
DROP INDEX PERSONNEL_SOURCE_FK;
DROP INDEX ADRESSE_STRUCTURE_HDFK;
DROP INDEX WF_INTERVENANT_ETAPE_SFK;
DROP INDEX CENTRE_COUT_HMFK;
DROP INDEX CCEP_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX PIECE_JOINTE_HCFK;
DROP INDEX CC_ACTIVITE_HMFK;
DROP INDEX ROLE_HDFK;
DROP INDEX TYPE_INTERVENANT_HCFK;
DROP INDEX VVH_VOLUME_HORAIRE_FK;
DROP INDEX ELEMENT_TAUX_REGIMES_HDFK;
DROP INDEX EMPLOI_HMFK;
DROP INDEX CONTRAT_FICHIER_FFK;
DROP INDEX FONCTION_REFERENTIEL_SFK;
DROP INDEX INTERVENANT_PERMANENT_HMFK;
DROP INDEX STATUT_INTERVENANT_SOURCE_FK;
DROP INDEX EMPLOI_HCFK;
DROP INDEX SRFR_FK;
DROP INDEX VALIDATION_INTERVENANT_FK;
DROP INDEX TME_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX TMS_STRUCTURE_FK;
DROP INDEX CONTRAT_VALIDATION_FK;
DROP INDEX MISE_EN_PAIEMENT_HMFK;
DROP INDEX TAUX_HORAIRE_HETD_HDFK;
DROP INDEX CHEMIN_PEDAGOGIQUE_HMFK;
DROP INDEX FICHIER_HDFK;
DROP INDEX EFFECTIFS_ELEMENT_FK;
DROP INDEX PAYS_HDFK;
DROP INDEX FRR_FORMULE_RESULTAT_FK;
DROP INDEX TYPE_PIECE_JOINTE_HCFK;
DROP INDEX ELEMENT_MODULATEUR_HMFK;
DROP INDEX TYPE_CONTRAT_HDFK;
DROP INDEX TYPE_AGREMENT_HDFK;
DROP INDEX ROLE_HMFK;
DROP INDEX DISCIPLINE_HMFK;
DROP INDEX MODULATEUR_HDFK;
DROP INDEX ADRESSE_INTERVENANT_SOURCE_FK;
DROP INDEX STRUCTURE_TYPE_STRUCTURE_FK;
DROP INDEX ROLE_PRIVILEGE_ROLE_FK;
DROP INDEX REGIME_SECU_HDFK;
DROP INDEX SITUATION_FAMILIALE_HCFK;
DROP INDEX TYPE_MODULATEUR_EP_HCFK;
DROP INDEX TYPE_DOTATION_HMFK;
DROP INDEX TYPE_PIECE_JOINTE_HDFK;
DROP INDEX VVHR_VOLUME_HORAIRE_REF_FK;
DROP INDEX AFFECTATION_R_STRUCTURE_FK;
DROP INDEX TYPE_MODULATEUR_EP_HMFK;
DROP INDEX CHEMIN_PEDAGOGIQUE_HCFK;
DROP INDEX VHR_SERVICE_REFERENTIEL_FK;
DROP INDEX FICHIER_HCFK;
DROP INDEX STATUT_INTERVENANT_HCFK;
DROP INDEX EM_MODULATEUR_FK;
DROP INDEX DOMAINE_FONCTIONNEL_HMFK;
DROP INDEX CENTRE_COUT_ACTIVITE_FK;
DROP INDEX TIEP_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX FRVH_FORMULE_RESULTAT_FK;
DROP INDEX TAUX_HORAIRE_HETD_HCFK;
DROP INDEX MODIFICATION_SERVICE_DU_HCFK;
DROP INDEX TME_SOURCE_FK;
DROP INDEX INTERVENANT_PERMANENT_HCFK;
DROP INDEX IE_TYPE_POSTE_FK;
DROP INDEX INTERVENANT_HCFK;
DROP INDEX WF_INTERVENANT_ETAPE_IFK;
DROP INDEX TYPE_CONTRAT_HMFK;
DROP INDEX DOTATION_HCFK;
DROP INDEX TYPE_INTERVENANT_HDFK;
DROP INDEX STATUT_INTERVENANT_TYPE_FK;
DROP INDEX DOMAINE_FONCTIONNEL_HCFK;
DROP INDEX STATUT_INTERVENANT_HMFK;
DROP INDEX TYPE_DOTATION_HDFK;
DROP INDEX DOTATION_ANNEE_FK;
DROP INDEX AFFECTATION_SOURCE_FK;
DROP INDEX AFFECTATION_HMFK;
DROP INDEX TYPE_VALIDATION_HDFK;
DROP INDEX STRUCTURE_HMFK;
DROP INDEX DOSSIER_HDFK;
DROP INDEX INTERVENANT_PERMANENT_CORPS_FK;
DROP INDEX ETAPE_HCFK;
DROP INDEX TYPE_FORMATION_SOURCE_FK;
DROP INDEX TYPE_VOLUME_HORAIRE_HDFK;
DROP INDEX VOLUME_HORAIRE_HMFK;
DROP INDEX INTERVENANT_STRUCTURE_FK;
DROP INDEX AFFECTATION_R_HMFK;
DROP INDEX ELEMENT_DISCIPLINE_HCFK;
DROP INDEX AFFECTATION_HDFK;
DROP INDEX TYPE_HEURES_HDFK;
DROP INDEX TIEP_TYPE_INTERVENTION_FK;
DROP INDEX STAT_PRIV_PRIVILEGE_FK;
DROP INDEX PIECE_JOINTE_FICHIER_FFK;
DROP INDEX FRSR_SERVICE_REFERENTIEL_FK;
DROP INDEX DEPARTEMENT_HCFK;
DROP INDEX MODIFICATION_SERVICE_DU_HDFK;
DROP INDEX INTERVENANT_EXTERIEUR_HDFK;
DROP INDEX CONTRAT_CONTRAT_FK;
DROP INDEX TYPE_VOLUME_HORAIRE_HMFK;
DROP INDEX ELEMENT_DISCIPLINE_SOURCE_FK;
DROP INDEX AGREMENT_HMFK;
DROP INDEX TYPE_VOLUME_HORAIRE_HCFK;
DROP INDEX TYPE_MODULATEUR_STRUCTURE_HMFK;
DROP INDEX CC_ACTIVITE_HDFK;
DROP INDEX DISCIPLINE_SOURCE_FK;
DROP INDEX VOLUME_HORAIRE_ENS_SOURCE_FK;
DROP INDEX ETR_SOURCE_FK;
DROP INDEX FONCTION_REFERENTIEL_HDFK;
DROP INDEX VALIDATION_HDFK;
DROP INDEX CENTRE_COUT_STRUCTURE_FK;
DROP INDEX VOLUME_HORAIRE_HDFK;
DROP INDEX DOMAINE_FONCTIONNEL_HDFK;
DROP INDEX PIECE_JOINTE_VFK;
DROP INDEX TYPE_PIECE_JOINTE_HMFK;
DROP INDEX INTERVENANT_EXTERIEUR_HMFK;
DROP INDEX STRUCTURE_HCFK;
DROP INDEX VOLUME_HORAIRE_ENS_HCFK;
DROP INDEX PAYS_SOURCE_FK;
DROP INDEX TYPE_VALIDATION_HCFK;
DROP INDEX EMPLOIS_EMPLOYEURS_FK;
DROP INDEX CENTRE_COUT_HDFK;
DROP INDEX TYPE_AGREMENT_STATUT_HDFK;
DROP INDEX VHR_TYPE_VOLUME_HORAIRE_FK;
DROP INDEX WF_ETAPE_AFK;
DROP INDEX CHEMIN_PEDAGOGIQUE_HDFK;
DROP INDEX SERVICE_HCFK;
DROP INDEX DOSSIER_HMFK;
DROP INDEX MOTIF_MODIFICATION_SERVIC_HMFK;
DROP INDEX TYPE_STRUCTURE_HCFK;
DROP INDEX IIT_FK;
DROP INDEX SERVICE_HDFK;
DROP INDEX VALIDATION_HMFK;
DROP INDEX PARAMETRE_HCFK;
DROP INDEX AFFECTATION_HCFK;
DROP INDEX PJ_DOSSIER_FK;
DROP INDEX ELEMENT_PEDAGOGIQUE_HMFK;
DROP INDEX DS_IP_FK;
DROP INDEX TYPE_INTERVENTION_EP_HMFK;
DROP INDEX MEP_CENTRE_COUT_FK;
DROP INDEX DOMAINE_FONCTIONNEL_SOURCE_FK;
DROP INDEX EFFECTIFS_SOURCE_FK;
DROP INDEX TYPE_INTERVENTION_EP_HDFK;
DROP INDEX TYPE_INTERVENTION_EP_SOURCE_FK;
DROP INDEX VALIDATION_STRUCTURE_FK;
DROP INDEX SERVICE_HMFK;
DROP INDEX ETAT_VOLUME_HORAIRE_HCFK;
DROP INDEX ELEMENT_TAUX_REGIMES_HCFK;
DROP INDEX TYPE_INTERVENTION_EP_HCFK;
DROP INDEX INTERVENANT_HMFK;
DROP INDEX PERSONNEL_HCFK;
DROP INDEX DOTATION_HDFK;
DROP INDEX MISE_EN_PAIEMENT_PERIODE_FK;
DROP INDEX TD_TYPE_RESSOURCE_FK;
DROP INDEX TYPE_AGREMENT_HMFK;
DROP INDEX AFFECTATION_R_HDFK;
DROP INDEX AGREMENT_STRUCTURE_FK;
DROP INDEX ETAPE_HMFK;
DROP INDEX TYPE_RESSOURCE_HDFK;
DROP INDEX CONTRAT_HMFK;
DROP INDEX CORPS_SOURCE_FK;
DROP INDEX TYPE_INTERVENTION_HDFK;
DROP INDEX INTERVENANTS_CIVILITES_FK;
DROP INDEX TYPE_CONTRAT_HCFK;
DROP INDEX TYPE_MODULATEUR_HCFK;
DROP INDEX CENTRE_COUT_CENTRE_COUT_FK;
DROP INDEX PERSONNEL_STRUCTURE_FK;
DROP INDEX ETAT_VOLUME_HORAIRE_HDFK;
DROP INDEX ETAT_VOLUME_HORAIRE_HMFK;
DROP INDEX INTERVENANT_HDFK;
DROP INDEX TYPE_POSTE_HMFK;
DROP INDEX TYPE_FORMATION_GROUPE_FK;
DROP INDEX INTERVENANT_DISCIPLINE_FK;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HDFK;
DROP INDEX PERIODE_HDFK;
DROP INDEX DISCIPLINE_HCFK;
DROP INDEX TYPE_STRUCTURE_HMFK;
DROP INDEX DOTATION_HMFK;
DROP INDEX ELEMENT_MODULATEUR_HDFK;
DROP INDEX SERVICE_REFERENTIEL_HDFK;
DROP INDEX ED_DISCIPLINE_FK;
DROP INDEX TIS_STRUCTURE_FK;
DROP INDEX VOLUME_HORAIRE_ENS_HDFK;
DROP INDEX AFFECTATION_R_INTERVENANT_FK;
DROP INDEX STRUCTURE_STRUCTURE_FK;
DROP INDEX TYPE_FORMATION_HMFK;
DROP INDEX "OSE"."WF_ETAPE_CODE_UN;
DROP INDEX TYPE_RESSOURCE_HMFK;
DROP INDEX AFFECTATION_ROLE_FK;
DROP INDEX ETABLISSEMENT_SOURCE_FK;
DROP INDEX CC_ACTIVITE_HCFK;
DROP INDEX TYPE_INTERVENANT_HMFK;
DROP INDEX TYPE_MODULATEUR_STRUCTURE_HDFK;
DROP INDEX TYPE_FORMATION_HDFK;
DROP INDEX MOTIF_NON_PAIEMENT_HMFK;
DROP INDEX AGREMENT_INTERVENANT_FK;
DROP INDEX ELEMENT_PEDAGOGIQUE_HDFK;
DROP INDEX CHEMIN_PEDAGOGIQUE_SOURCE_FK;
DROP INDEX ADRESSE_STRUCTURE_STRUCTURE_FK;
DROP INDEX CENTRE_COUT_SOURCE_FK;
DROP INDEX VHENS_ELEMENT_DISCIPLINE_FK;
DROP INDEX TYPE_POSTE_HCFK;
DROP INDEX TYPE_MODULATEUR_EP_HDFK;
DROP INDEX CONTRAT_STRUCTURE_FK;
DROP INDEX STRUCTURES_STRUCTURES_FK;
DROP INDEX CONTRAT_HCFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_HCFK;
DROP INDEX CENTRE_COUT_EP_HMFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_ETAPE_FK;
DROP INDEX AII_FK;
DROP INDEX MOTIF_NON_PAIEMENT_HCFK;
DROP INDEX INTERVENANT_ANNEE_FK;
DROP INDEX PIECE_JOINTE_HMFK;
DROP INDEX TYPE_MODULATEUR_HMFK;   
   
   
   
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_ANNEE_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_ANNEE_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_UFK_IDX
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_UFK_IDX" ON "OSE"."NOTIFICATION_INDICATEUR" ("PERSONNEL_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HCFK_IDX" ON "OSE"."DEPARTEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_TYPE_RESSOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_TYPE_RESSOURCE_IDX" ON "OSE"."CENTRE_COUT" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_ETAPE_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_ETAPE_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--SR_IP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SR_IP_FK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_STRUCTU_HCIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_STRUCTU_HCIDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_SOUR_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_SOUR_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--PAYS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_HDFK_IDX" ON "OSE"."PAYS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HCFK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--IIT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."IIT_FK_IDX" ON "OSE"."INTERVENANT" ("TYPE_ID");
---------------------------
--Nouveau INDEX
--MOTIF_MODIFICATION_SERV_HCIDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_MODIFICATION_SERV_HCIDX" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HDFK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_STRUCTURE_FK_IDX" ON "OSE"."ETAPE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_STRUCTURE_FK_IDX" ON "OSE"."INTERVENANT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_EFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_EFK_IDX" ON "OSE"."WF_INTERVENANT_ETAPE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HCFK_IDX" ON "OSE"."CONTRAT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EMPLOI_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOI_HDFK_IDX" ON "OSE"."EMPLOI" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_SOURCE_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_HMFK_IDX" ON "OSE"."FICHIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HDFK_IDX" ON "OSE"."VALIDATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TIEP_TYPE_INTERVENTION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIEP_TYPE_INTERVENTION_FK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HMFK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HDFK_IDX" ON "OSE"."TYPE_VALIDATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_STRUCTURE_FK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_DISCIPLINE_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_DISCIPLINE_SOURCE_IDX" ON "OSE"."ELEMENT_DISCIPLINE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_DISCIPLINE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_DISCIPLINE_FK_IDX" ON "OSE"."INTERVENANT" ("DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_SOURCE_FK_IDX" ON "OSE"."DEPARTEMENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_HCFK_IDX" ON "OSE"."DOTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HMFK_IDX" ON "OSE"."DEPARTEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HCFK_IDX" ON "OSE"."MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HMFK_IDX" ON "OSE"."EFFECTIFS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HMFK_IDX" ON "OSE"."PIECE_JOINTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HCFK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HDFK_IDX" ON "OSE"."CONTRAT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HCFK_IDX" ON "OSE"."TYPE_RESSOURCE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_STRUCTU_HMIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_STRUCTU_HMIDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HCFK_IDX" ON "OSE"."AGREMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--indic_diff_dossier_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."indic_diff_dossier_PK" ON "OSE"."INDIC_MODIF_DOSSIER" ("ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HCFK_IDX" ON "OSE"."TYPE_CONTRAT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TPJS_STATUT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TPJS_STATUT_INTERVENANT_FK_IDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("STATUT_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_HDFK_IDX" ON "OSE"."ETAPE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_IFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_IFK_IDX" ON "OSE"."WF_INTERVENANT_ETAPE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HCFK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HCFK_IDX" ON "OSE"."TYPE_FORMATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_STRUC_HCIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_STRUC_HCIDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ED_DISCIPLINE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ED_DISCIPLINE_FK_IDX" ON "OSE"."ELEMENT_DISCIPLINE" ("DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--PAYS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_SOURCE_FK_IDX" ON "OSE"."PAYS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TIS_TYPE_INTERVENTION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_TYPE_INTERVENTION_FK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_PERMANENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_PERMANENT_HDFK_IDX" ON "OSE"."INTERVENANT_PERMANENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_DISCIPLINE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_DISCIPLINE_HDFK_IDX" ON "OSE"."ELEMENT_DISCIPLINE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_VALIDATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_VALIDATION_FK_IDX" ON "OSE"."CONTRAT" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--SR_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SR_STRUCTURE_FK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TYPE_POSTE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_POSTE_HDFK_IDX" ON "OSE"."TYPE_POSTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_SOURCE_FK_IDX" ON "OSE"."ETAPE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_STATUT_HDIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_HDIDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HCFK_IDX" ON "OSE"."TYPE_MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HMFK_IDX" ON "OSE"."TYPE_INTERVENTION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_STRUCTURE_FK_IDX" ON "OSE"."DOTATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--WF_ETAPE_AFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_ETAPE_AFK_IDX" ON "OSE"."WF_ETAPE" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HDFK_IDX" ON "OSE"."STATUT_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_TYPE_HEURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_TYPE_HEURES_FK_IDX" ON "OSE"."CENTRE_COUT_EP" ("TYPE_HEURES_ID");
---------------------------
--Nouveau INDEX
--DS_MDS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DS_MDS_FK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("MOTIF_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HDFK_IDX" ON "OSE"."DISCIPLINE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_PERIO_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_PERIO_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("PERIODE_ID");
---------------------------
--Nouveau INDEX
--FRVH_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVH_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HMFK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_EXTERIEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_EXTERIEUR_HCFK_IDX" ON "OSE"."INTERVENANT_EXTERIEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HDFK_IDX" ON "OSE"."AFFECTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HDFK_IDX" ON "OSE"."VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_CENTRE_COUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_CENTRE_COUT_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("CENTRE_COUT_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HCFK_IDX" ON "OSE"."TYPE_DOTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HMFK_IDX" ON "OSE"."ETABLISSEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EMPLOYEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOYEUR_HMFK_IDX" ON "OSE"."EMPLOYEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HMFK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HCFK_IDX" ON "OSE"."TYPE_INTERVENTION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_STRUCTU_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_STRUCTU_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HMFK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HMFK_IDX" ON "OSE"."CONTRAT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--SITUATION_FAMILIALE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."SITUATION_FAMILIALE_HDFK_IDX" ON "OSE"."SITUATION_FAMILIALE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_CIVILITE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_CIVILITE_FK_IDX" ON "OSE"."PERSONNEL" ("CIVILITE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HMFK_IDX" ON "OSE"."TYPE_MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HDFK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HCFK_IDX" ON "OSE"."PARAMETRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HCFK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HDFK_IDX" ON "OSE"."ETABLISSEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_PERSONNEL_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_PERSONNEL_FK_IDX" ON "OSE"."AFFECTATION" ("PERSONNEL_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HDFK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HDFK_IDX" ON "OSE"."TYPE_FORMATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--EMPLOIS_EMPLOYEURS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOIS_EMPLOYEURS_FK_IDX" ON "OSE"."EMPLOI" ("EMPLOYEUR_ID");
---------------------------
--Nouveau INDEX
--VHENS_ELEMENT_DISCIPLINE_IDX
---------------------------
  CREATE INDEX "OSE"."VHENS_ELEMENT_DISCIPLINE_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("ELEMENT_DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--TIEP_ELEMENT_PEDAGOGIQUE_IDX
---------------------------
  CREATE INDEX "OSE"."TIEP_ELEMENT_PEDAGOGIQUE_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HMFK_IDX" ON "OSE"."CC_ACTIVITE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_SFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_SFK_IDX" ON "OSE"."WF_INTERVENANT_ETAPE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_TYPE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_TYPE_FK_IDX" ON "OSE"."STATUT_INTERVENANT" ("TYPE_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--REGIME_SECU_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."REGIME_SECU_HMFK_IDX" ON "OSE"."REGIME_SECU" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HCFK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HCFK_IDX" ON "OSE"."DOSSIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_SFK_IDX
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_SFK_IDX" ON "OSE"."NOTIFICATION_INDICATEUR" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HMFK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MV_ELEMENT_PEDAGOGIQUE_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_ELEMENT_PEDAGOGIQUE_PK" ON "OSE"."MV_ELEMENT_PEDAGOGIQUE" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--FRVHR_VOLUME_HORAIRE_REF_IDX
---------------------------
  CREATE INDEX "OSE"."FRVHR_VOLUME_HORAIRE_REF_IDX" ON "OSE"."FORMULE_RESULTAT_VH_REF" ("VOLUME_HORAIRE_REF_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HMFK_IDX" ON "OSE"."CENTRE_COUT_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_INTERVENANT_FK_IDX" ON "OSE"."VALIDATION" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--MODIFICATION_SERVICE_DU_HDIDX
---------------------------
  CREATE INDEX "OSE"."MODIFICATION_SERVICE_DU_HDIDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FRVHR_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVHR_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH_REF" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HMFK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HDFK_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HDFK_IDX" ON "OSE"."DOSSIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HCFK_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HDFK_IDX" ON "OSE"."TYPE_RESSOURCE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HDFK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_TYPE_FORMATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_TYPE_FORMATION_FK_IDX" ON "OSE"."ETAPE" ("TYPE_FORMATION_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HMFK_IDX" ON "OSE"."TYPE_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HDFK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_DOMAINE_FONCTIONNEL_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_DOMAINE_FONCTIONNEL_IDX" ON "OSE"."ETAPE" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HDFK_IDX" ON "OSE"."CENTRE_COUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HMFK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANTS_CIVILITES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANTS_CIVILITES_FK_IDX" ON "OSE"."INTERVENANT" ("CIVILITE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HDFK_IDX" ON "OSE"."TYPE_INTERVENTION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HMFK_IDX" ON "OSE"."MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HMFK_IDX" ON "OSE"."TYPE_AGREMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_SOURCE_FK_IDX" ON "OSE"."TYPE_DOTATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_CENTRE_COUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_CENTRE_COUT_FK_IDX" ON "OSE"."CENTRE_COUT" ("PARENT_ID");
---------------------------
--Nouveau INDEX
--TYPE_POSTE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_POSTE_HCFK_IDX" ON "OSE"."TYPE_POSTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HDFK_IDX" ON "OSE"."MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PAYS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_HMFK_IDX" ON "OSE"."PAYS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_HCFK_IDX" ON "OSE"."CORPS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FRS_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRS_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--VHR_TYPE_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHR_TYPE_VOLUME_HORAIRE_FK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--MOTIF_MODIFICATION_SERV_HMIDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_MODIFICATION_SERV_HMIDX" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HCFK_IDX" ON "OSE"."INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HDFK_IDX" ON "OSE"."INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HMFK_IDX" ON "OSE"."DOSSIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_TYPE_MODULATEUR_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_TYPE_MODULATEUR_IDX" ON "OSE"."MODULATEUR" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HMFK_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_ETAPE_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_ETAPE_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HMFK_IDX" ON "OSE"."TYPE_VALIDATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HMFK_IDX" ON "OSE"."AFFECTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HCFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HDFK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MODIFICATION_SERVICE_DU_HMIDX
---------------------------
  CREATE INDEX "OSE"."MODIFICATION_SERVICE_DU_HMIDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HMFK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HCFK_IDX" ON "OSE"."TYPE_VALIDATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_POSTE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_POSTE_HMFK_IDX" ON "OSE"."TYPE_POSTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_SOURCE_FK_IDX" ON "OSE"."TYPE_FORMATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--EMPLOYEURS_EMPLOYEURS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOYEURS_EMPLOYEURS_FK_IDX" ON "OSE"."EMPLOYEUR" ("EMPLOYEUR_PERE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HMFK_IDX" ON "OSE"."INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_STATUT_HCIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_HCIDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HDFK_IDX" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HMFK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--REGIME_SECU_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."REGIME_SECU_HCFK_IDX" ON "OSE"."REGIME_SECU" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_HCFK_IDX" ON "OSE"."SERVICE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_SOURCE_FK_IDX" ON "OSE"."CENTRE_COUT_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_DISCIPLINE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_DISCIPLINE_HCFK_IDX" ON "OSE"."ELEMENT_DISCIPLINE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PJ_TYPE_PIECE_JOINTE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PJ_TYPE_PIECE_JOINTE_FK_IDX" ON "OSE"."PIECE_JOINTE" ("TYPE_PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_SOURCE_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HCFK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HMFK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VVH_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VVH_VOLUME_HORAIRE_FK_IDX" ON "OSE"."VALIDATION_VOL_HORAIRE" ("VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_FICHIER_FFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_FICHIER_FFK_IDX" ON "OSE"."CONTRAT_FICHIER" ("FICHIER_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HCFK_IDX" ON "OSE"."EFFECTIFS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HDFK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--EMPLOYEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOYEUR_HDFK_IDX" ON "OSE"."EMPLOYEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HDFK_IDX" ON "OSE"."CC_ACTIVITE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--SITUATION_FAMILIALE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."SITUATION_FAMILIALE_HMFK_IDX" ON "OSE"."SITUATION_FAMILIALE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_IFK_IDX
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_IFK_IDX" ON "OSE"."NOTIFICATION_INDICATEUR" ("INDICATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_ACTIVITE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_ACTIVITE_FK_IDX" ON "OSE"."CENTRE_COUT" ("ACTIVITE_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HMFK_IDX" ON "OSE"."PARAMETRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_SOURCE_FK_IDX" ON "OSE"."STRUCTURE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HDFK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STAT_PRIV_PRIVILEGE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STAT_PRIV_PRIVILEGE_FK_IDX" ON "OSE"."STATUT_PRIVILEGE" ("PRIVILEGE_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HCFK_IDX" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERIODE_HCFK_IDX" ON "OSE"."PERIODE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_STRUCTURE_FK_IDX" ON "OSE"."CONTRAT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TMS_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_STRUCTURE_FK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HDFK_IDX" ON "OSE"."EFFECTIFS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HCFK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_VALIDATI_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_VALIDATI_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_STRUCTURE_FK_IDX" ON "OSE"."CENTRE_COUT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HMFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HDFK_IDX" ON "OSE"."PARAMETRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HDFK_IDX" ON "OSE"."TYPE_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HDFK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HCFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_SOURCE_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HDFK_IDX" ON "OSE"."CENTRE_COUT_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HMFK_IDX" ON "OSE"."PERSONNEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EMPLOI_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOI_HMFK_IDX" ON "OSE"."EMPLOI" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HCFK_IDX" ON "OSE"."TYPE_AGREMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_STRUCTURE_FK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_HCFK_IDX" ON "OSE"."ETAPE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_INTERVENANT_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_INTERVENANT_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--IP_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."IP_SOURCE_FK_IDX" ON "OSE"."INTERVENANT_PERMANENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--SRFR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SRFR_FK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("FONCTION_ID");
---------------------------
--Nouveau INDEX
--REGIME_SECU_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."REGIME_SECU_HDFK_IDX" ON "OSE"."REGIME_SECU" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_STRUCTURE_FK_IDX" ON "OSE"."AFFECTATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HCFK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PAYS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_HCFK_IDX" ON "OSE"."PAYS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HMFK_IDX" ON "OSE"."VALIDATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_PERIMETRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_PERIMETRE_FK_IDX" ON "OSE"."ROLE" ("PERIMETRE_ID");
---------------------------
--Nouveau INDEX
--AII_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AII_FK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_ETABLISSEMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_ETABLISSEMENT_FK_IDX" ON "OSE"."STRUCTURE" ("ETABLISSEMENT_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HCFK_IDX" ON "OSE"."TYPE_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_FICHIER_FFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_FICHIER_FFK_IDX" ON "OSE"."PIECE_JOINTE_FICHIER" ("FICHIER_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HDFK_IDX" ON "OSE"."TYPE_AGREMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HCFK_IDX" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FRS_SERVICE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRS_SERVICE_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE" ("SERVICE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_HMFK_IDX" ON "OSE"."DOTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_PERIODE_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_PERIODE_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("PERIODE_PAIEMENT_ID");
---------------------------
--Nouveau INDEX
--VHENS_TYPE_INTERVENTION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHENS_TYPE_INTERVENTION_FK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HDFK_IDX" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_SOURCE_FK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ROLE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_HCFK_IDX" ON "OSE"."ROLE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_SOURCE_FK_IDX" ON "OSE"."CORPS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TME_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TME_SOURCE_FK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_STRUCTURE_FK_IDX" ON "OSE"."VALIDATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_STATUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_STATUT_FK_IDX" ON "OSE"."INTERVENANT" ("STATUT_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HDFK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VVHR_VOLUME_HORAIRE_REF_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VVHR_VOLUME_HORAIRE_REF_FK_IDX" ON "OSE"."VALIDATION_VOL_HORAIRE_REF" ("VOLUME_HORAIRE_REF_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_EXTERIEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_EXTERIEUR_HMFK_IDX" ON "OSE"."INTERVENANT_EXTERIEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HCFK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_SOURCE_FK_IDX" ON "OSE"."INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HDFK_IDX" ON "OSE"."TYPE_DOTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_HDFK_IDX" ON "OSE"."ROLE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_HMFK_IDX" ON "OSE"."CORPS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_HMFK_IDX" ON "OSE"."ROLE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_PERMANENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_PERMANENT_HMFK_IDX" ON "OSE"."INTERVENANT_PERMANENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HMFK_IDX" ON "OSE"."AGREMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_SFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_SFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--EPS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EPS_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERIODE_HDFK_IDX" ON "OSE"."PERIODE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GTYPE_FORMATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."GTYPE_FORMATION_SOURCE_FK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURES_STRUCTURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURES_STRUCTURES_FK_IDX" ON "OSE"."STRUCTURE" ("PARENTE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HCFK_IDX" ON "OSE"."AFFECTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_VALID_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_VALID_FK_IDX" ON "OSE"."FICHIER" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--DS_IP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DS_IP_FK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--ETR_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETR_SOURCE_FK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HCFK_IDX" ON "OSE"."PERSONNEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERIODE_HMFK_IDX" ON "OSE"."PERIODE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_HMFK_IDX" ON "OSE"."ETAPE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_INTERVENTION_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_INTERVENTION_IDX" ON "OSE"."GROUPE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HDFK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HMFK_IDX" ON "OSE"."STATUT_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HMFK_IDX" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_ROLE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_ROLE_FK_IDX" ON "OSE"."AFFECTATION" ("ROLE_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_HCFK_IDX" ON "OSE"."FICHIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HMFK_IDX" ON "OSE"."TYPE_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HDFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_STRUCTURE_FK_IDX" ON "OSE"."PERSONNEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--SITUATION_FAMILIALE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."SITUATION_FAMILIALE_HCFK_IDX" ON "OSE"."SITUATION_FAMILIALE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_HMFK_IDX" ON "OSE"."GROUPE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HMFK_IDX" ON "OSE"."CENTRE_COUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_HCFK_IDX" ON "OSE"."GROUPE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_SOURCE_FK_IDX" ON "OSE"."EFFECTIFS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--EMPLOYEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOYEUR_HCFK_IDX" ON "OSE"."EMPLOYEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HMFK_IDX" ON "OSE"."STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HDFK_IDX" ON "OSE"."AGREMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_ANNEE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_ANNEE_FK_IDX" ON "OSE"."INTERVENANT" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--FRVH_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVH_VOLUME_HORAIRE_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH" ("VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--FRES_TYPE_VOLUME_HORAIRE_IDX
---------------------------
  CREATE INDEX "OSE"."FRES_TYPE_VOLUME_HORAIRE_IDX" ON "OSE"."FORMULE_RESULTAT" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--MEP_TYPE_HEURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_TYPE_HEURES_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("TYPE_HEURES_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HDFK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--IE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."IE_SOURCE_FK_IDX" ON "OSE"."INTERVENANT_EXTERIEUR" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_TYPE_CONTRAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_TYPE_CONTRAT_FK_IDX" ON "OSE"."CONTRAT" ("TYPE_CONTRAT_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HDFK_IDX" ON "OSE"."TYPE_CONTRAT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PJ_DOSSIER_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PJ_DOSSIER_FK_IDX" ON "OSE"."PIECE_JOINTE" ("DOSSIER_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_PERMANENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_PERMANENT_HCFK_IDX" ON "OSE"."INTERVENANT_PERMANENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_ANNEE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_ANNEE_FK_IDX" ON "OSE"."DOTATION" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HCFK_IDX" ON "OSE"."DISCIPLINE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HCFK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HCFK_IDX" ON "OSE"."PIECE_JOINTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_VFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_VFK_IDX" ON "OSE"."PIECE_JOINTE" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_PERMANENT_COR_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_PERMANENT_COR_IDX" ON "OSE"."INTERVENANT_PERMANENT" ("CORPS_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HCFK_IDX" ON "OSE"."CENTRE_COUT_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HMFK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FRR_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRR_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE_REF" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_HDFK_IDX" ON "OSE"."DOTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HDFK_IDX" ON "OSE"."PIECE_JOINTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HCFK_IDX" ON "OSE"."TYPE_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HMFK_IDX" ON "OSE"."TYPE_FORMATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--IERS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."IERS_FK_IDX" ON "OSE"."INTERVENANT_EXTERIEUR" ("REGIME_SECU_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HDFK_IDX" ON "OSE"."STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HMFK_IDX" ON "OSE"."TYPE_DOTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_DISCIPLINE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_DISCIPLINE_HMFK_IDX" ON "OSE"."ELEMENT_DISCIPLINE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HMFK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_ELEMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_ELEMENT_FK_IDX" ON "OSE"."EFFECTIFS" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_TYPE_DOTATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_TYPE_DOTATION_FK_IDX" ON "OSE"."DOTATION" ("TYPE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HDFK_IDX" ON "OSE"."TYPE_MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HMFK_IDX" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HCFK_IDX" ON "OSE"."CC_ACTIVITE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HDFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_SOURCE_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--IE_TYPE_POSTE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."IE_TYPE_POSTE_FK_IDX" ON "OSE"."INTERVENANT_EXTERIEUR" ("TYPE_POSTE_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HCFK_IDX" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HMFK_IDX" ON "OSE"."DISCIPLINE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HMFK_IDX" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_HDFK_IDX" ON "OSE"."FICHIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_SOURCE_IDX" ON "OSE"."STATUT_INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HDFK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HCFK_IDX" ON "OSE"."VALIDATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_STRUCTU_HDIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_STRUCTU_HDIDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HCFK_IDX" ON "OSE"."ETABLISSEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VHR_SERVICE_REFERENTIEL_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHR_SERVICE_REFERENTIEL_FK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("SERVICE_REFERENTIEL_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HDFK_IDX" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HCFK_IDX" ON "OSE"."STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_SOURCE_FK_IDX" ON "OSE"."AFFECTATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_STRUCT_HMIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_STRUCT_HMIDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HCFK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_SOURCE_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HDFK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HDFK_IDX" ON "OSE"."TYPE_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_STRUCTURE_FK_IDX" ON "OSE"."STRUCTURE" ("STRUCTURE_NIV2_ID");
---------------------------
--Nouveau INDEX
--TD_TYPE_RESSOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TD_TYPE_RESSOURCE_FK_IDX" ON "OSE"."TYPE_DOTATION" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--MOTIF_MODIFICATION_SERV_HDIDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_MODIFICATION_SERV_HDIDX" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_STRUCTURE_FK_IDX" ON "OSE"."AGREMENT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HMFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HDFK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HDFK_IDX" ON "OSE"."PERSONNEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--EMPLOI_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."EMPLOI_HCFK_IDX" ON "OSE"."EMPLOI" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HMFK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_HDFK_IDX" ON "OSE"."CORPS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HCFK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_SOURCE_FK_IDX" ON "OSE"."CENTRE_COUT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HMFK_IDX" ON "OSE"."TYPE_RESSOURCE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MODIFICATION_SERVICE_DU_HCIDX
---------------------------
  CREATE INDEX "OSE"."MODIFICATION_SERVICE_DU_HCIDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HMFK_IDX" ON "OSE"."TYPE_CONTRAT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Modifié INDEX
--WF_ETAPE_CODE_UN
---------------------------
DROP INDEX "OSE"."WF_ETAPE_CODE_UN";
  CREATE UNIQUE INDEX "OSE"."WF_ETAPE_CODE_UN" ON "OSE"."WF_ETAPE" ("CODE","ANNEE_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_HDFK_IDX" ON "OSE"."GROUPE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HCFK_IDX" ON "OSE"."STATUT_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HDFK_IDX" ON "OSE"."DEPARTEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TAS_STATUT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TAS_STATUT_INTERVENANT_FK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("STATUT_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HCFK_IDX" ON "OSE"."VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_ETAT_VOLUME_HORAIRE_IDX
---------------------------
  CREATE INDEX "OSE"."FRES_ETAT_VOLUME_HORAIRE_IDX" ON "OSE"."FORMULE_RESULTAT" ("ETAT_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HCFK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_EXTERIEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_EXTERIEUR_HDFK_IDX" ON "OSE"."INTERVENANT_EXTERIEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_SOURCE_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_SOURCE_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ROLE_PRIVILEGE_ROLE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_PRIVILEGE_ROLE_FK_IDX" ON "OSE"."ROLE_PRIVILEGE" ("ROLE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HMFK_IDX" ON "OSE"."VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_SOURCE_FK_IDX" ON "OSE"."PERSONNEL" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--EIE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EIE_FK_IDX" ON "OSE"."EMPLOI" ("INTERVENANT_EXTERIEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HCFK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_STRUC_HDIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_STRUC_HDIDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_ELEMENT_PEDAGOGIQUE_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_ELEMENT_PEDAGOGIQUE_IDX" ON "OSE"."CENTRE_COUT_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HMFK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TME_ELEMENT_PEDAGOGIQUE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TME_ELEMENT_PEDAGOGIQUE_FK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_STATUT_HMIDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_HMIDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_SOURCE_FK_IDX" ON "OSE"."ETABLISSEMENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_CONTRAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_CONTRAT_FK_IDX" ON "OSE"."CONTRAT" ("CONTRAT_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_GROUPE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_GROUPE_FK_IDX" ON "OSE"."TYPE_FORMATION" ("GROUPE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_HMFK_IDX" ON "OSE"."SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EM_MODULATEUR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EM_MODULATEUR_FK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HCFK_IDX" ON "OSE"."CENTRE_COUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_INTERVENANT_FK_IDX" ON "OSE"."AGREMENT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_HDFK_IDX" ON "OSE"."SERVICE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FRSR_SERVICE_REFERENTIEL_IDX
---------------------------
  CREATE INDEX "OSE"."FRSR_SERVICE_REFERENTIEL_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE_REF" ("SERVICE_REFERENTIEL_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_SOURCE_FK_IDX" ON "OSE"."DISCIPLINE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_TYPE_STRUCTURE_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_TYPE_STRUCTURE_IDX" ON "OSE"."STRUCTURE" ("TYPE_ID");
