--
-- Version 1.5
--


---------------------------------------------------------------------------------
-- Modif Statuts (Tâche #1728 : Refonte de la page Données personnelles)
---------------------------------------------------------------------------------

update statut_intervenant set 
    LIBELLE = 'Sans emploi, non étudiant', 
    SOURCE_CODE = 'SS_EMPLOI_NON_ETUD', 
    PEUT_CHOISIR_DANS_DOSSIER = 1,
    histo_modification = sysdate
where SOURCE_CODE = 'CHARG_ENS_1AN';

update statut_intervenant set 
    LIBELLE = 'Auto-entrepreneur, profession libérale ou indépendante', 
    SOURCE_CODE = 'AUTO_LIBER_INDEP',
    histo_modification = sysdate
where SOURCE_CODE = 'NON_SALAR';





---------------------------------------------------------------------------------
-- Refonte gestion des PJ.
-- Nécessaire pour permettre la demande du RIB uniquement s'il a changé dans le dossier.
---------------------------------------------------------------------------------

-- par défaut, le RIB devient une PJ obligatoire (premier recrutement ou non)
update type_piece_jointe_statut set obligatoire = 1
  where obligatoire <> 1 and type_piece_jointe_id = ( select id from type_piece_jointe where code = 'RIB');




create or replace PACKAGE OSE_PJ AS 

  /**
   * Recherche du caractère obligatoire d'un type de PJ pour un dossier.
   */
  function is_tpj_obligatoire(tpjId IN numeric, dossierId IN numeric) return numeric;
  
  /**
   * Mise à jour des PJ attendues pour le type de PJ et le dossier spécifiés.
   */
  procedure update_pj(tpjId IN numeric, dossierId IN numeric, forceObligatoire IN numeric default null);
  
END OSE_PJ;
/

create or replace PACKAGE BODY OSE_PJ AS
  
  /**
   * Recherche du caractère obligatoire d'un type de PJ pour un dossier.
   */
  function is_tpj_obligatoire(tpjId IN numeric, dossierId IN numeric) return numeric 
  is 
    intervenantId numeric;
    statutId numeric;
    premierRecrutement numeric;
    obligatoire numeric;
  begin
    -- recherche de l'intervenant extérieur correspondant au dossier et de son statut
    select i.statut_id, i.id into statutId, intervenantId from intervenant_exterieur ie join intervenant i on i.id = ie.id where ie.dossier_id = dossierId;
    
    -- recherche du témoin "1er recrutement" dans le dossier
    select PREMIER_RECRUTEMENT into premierRecrutement from dossier d where d.id = dossierId;
    
    -- recherche du caractère obligatoire du type de PJ spécifié
    select tpjs.OBLIGATOIRE into obligatoire
    from type_piece_jointe_statut   tpjs
    join type_piece_jointe          tpj       on tpj.id = tpjs.type_piece_jointe_id and tpj.id = tpjId
    join statut_intervenant         si        on tpjs.statut_intervenant_id = si.id and si.id = statutId
    LEFT JOIN V_PJ_HEURES           vheures   ON vheures.INTERVENANT_ID = intervenantId
    where 
      tpjs.PREMIER_RECRUTEMENT = premierRecrutement AND 
      (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) > tpjs.SEUIL_HETD);
    
    return obligatoire;
  end;

  /**
   * Mise à jour des PJ attendues pour le type de PJ et le dossier spécifiés.
   */
  procedure update_pj(tpjId IN numeric, dossierId IN numeric, forceObligatoire IN numeric default null)
  is
    oblig numeric;
    found numeric;
  begin 
    if forceObligatoire is null then
      -- Recherche du caractère obligatoire du type de PJ pour le dossier.
      select is_tpj_obligatoire(tpjId, dossierId) into oblig from dual;
      
      -- La fonction is_tpj_obligatoire() renvoie null lorsque le type de PJ ne figure pas dans TYPE_PIECE_JOINTE_STATUT (i.e. n'est pas attendu).
      -- Dans ce cas, on historise la PJ et on se barre.
      if oblig is null then
        update piece_jointe pj set histo_destructeur_id = ose_parametre.get_ose_user(), histo_destruction = sysdate 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId;
        
        return;
      end if;
    else
      oblig := forceObligatoire;
    end if;
    
    -- Recherche dans PIECE_JOINTE s'il existe un enregistrement pour le type de PJ et le dossier spécifiés
    select count(*) into found from piece_jointe pj where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId;
    -- Si oui, mise à jour
    if found > 0 then
      update piece_jointe pj set pj.obligatoire = oblig, histo_modificateur_id = ose_parametre.get_ose_user(), histo_modification = sysdate 
      where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId and pj.obligatoire <> oblig;
    -- Si non, insertion
    else
      insert into piece_jointe pj (id, dossier_id, type_piece_jointe_id, obligatoire, histo_createur_id, histo_modificateur_id) 
      values (piece_jointe_id_seq.nextval, dossierId, tpjId, oblig, ose_parametre.get_ose_user(), ose_parametre.get_ose_user()); 
    end if;    
  end;

END OSE_PJ;
/


create or replace procedure upgrade_piece_jointe_v15 as
  found numeric;
begin 
  for r in (
    -- parcours : produit_cartesien(tous les dossiers existants, tous les types de PJ existants)
    select d.id dossier_id, tpj.id tpj_id
    from dossier d, type_piece_jointe tpj
    where d.histo_destruction is null and tpj.histo_destruction is null
  ) 
  loop
    -- Mise à jour des PJ attendues pour le type de PJ et le dossier spécifiés.
    ose_pj.update_pj(r.tpj_id, r.dossier_id);
    
    -- Le témoin "PJ fournie" est mis à 1 si des fichiers sont trouvés
    update piece_jointe pj set pj.fournie = (
      select case when count(*)>0 then 1 else 0 end 
      from piece_jointe_fichier pjf 
      join fichier f on f.id = pjf.fichier_id and f.histo_destruction is null 
      where pjf.piece_jointe_id = pj.id
    );
    -- et forcé à 1 si la PJ a été validée
    update piece_jointe pj set pj.fournie = 1 where pj.validation_id is not null;
    
  end loop;
end;
/

-- mise à niveau du contenu de la table PIECE_JOINTE.
-- NB: peut être la,cée plusieurs fois.
alter trigger WF_TRG_PJ disable;
alter trigger WF_TRG_PJ_VALIDATION disable;
begin upgrade_piece_jointe_v15(); end; 
/
alter trigger WF_TRG_PJ enable;
alter trigger WF_TRG_PJ_VALIDATION enable;

--drop procedure upgrade_piece_jointe_v15;




-- drop table PJ_TMP_INTERVENANT;
create global temporary table pj_tmp_intervenant (
  intervenant_id number(*,0) not null , 
  type_piece_jointe_id number(*,0) not null , 
  CONSTRAINT "PJ_TMP_INTERVENANT_PK" PRIMARY KEY (INTERVENANT_ID, type_piece_jointe_id) 
) on commit delete rows ;


create or replace TRIGGER PJ_TRG_DOSSIER AFTER UPDATE ON dossier 
FOR EACH ROW
DECLARE
  intervenantId numeric;
  tpjId numeric;
BEGIN
  -- recherche de l'intervenant propriétaire du dossier
  select id into intervenantId from intervenant_exterieur where dossier_id = :OLD.id;
  
  -- si le RIB a changé
  if trim(:OLD.rib) <> trim(:NEW.rib) then
    --dbms_output.put_line('RIB du dossier ' || :OLD.id || ' modifié : ' || :OLD.rib || ' -> ' || :NEW.rib);
    select id into tpjId from type_piece_jointe where code = 'RIB';
    ose_pj.add_intervenant_to_update (intervenantId, tpjId); 
  end if;
  
--  -- si le numéro INSEE a changé
--  if trim(:OLD.numero_insee) <> trim(:NEW.numero_insee) then
--    --dbms_output.put_line('Numero INSEE du dossier ' || :OLD.id || ' modifié : ' || :OLD.numero_insee || ' -> ' || :NEW.numero_insee);
--    select id into tpjId from type_piece_jointe where code = 'CARTE_VITALE';
--    ose_pj.add_intervenant_to_update (intervenantId, tpjId); 
--  end if;
END;
/

create or replace TRIGGER PJ_TRG_DOSSIER_S AFTER UPDATE OF rib ON dossier 
BEGIN
  dbms_output.put_line('PJ_TRG_DOSSIER_RIB_S ');
  ose_pj.update_intervenants_pj();
END;
/







---------------------------------------------------------------------------------
-- WF
---------------------------------------------------------------------------------

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'DEMANDE_MEP', '1', 'Demande mise en paiemant', 'ose_workflow.peut_demander_mep', 'ose_workflow.possede_demande_mep', 'Application\Service\Workflow\Step\DemandeMepStep', '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 140);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'SAISIE_MEP',  '1', 'Mise en paiement',         'ose_workflow.peut_saisir_mep',   'ose_workflow.possede_mep',         'Application\Service\Workflow\Step\MepStep',        '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 150);

--
-- Génération des progressions de tous les intervenants (~ 2 min)
--
begin OSE_WORKFLOW.UPDATE_ALL_INTERVENANTS_ETAPES() ; end;
/
