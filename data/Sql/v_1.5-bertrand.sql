
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
delete from type_piece_jointe_statut where type_piece_jointe_id = ( select id from type_piece_jointe where code = 'RIB');

-- mise à niveau du contenu de la table PIECE_JOINTE.
-- NB: peut être la,cée plusieurs fois.
alter trigger WF_TRG_PJ disable;
alter trigger WF_TRG_PJ_VALIDATION disable;
begin upgrade_piece_jointe_v15(); end; 
/
alter trigger WF_TRG_PJ enable;
alter trigger WF_TRG_PJ_VALIDATION enable;








---------------------------------------------------------------------------------
-- WF
---------------------------------------------------------------------------------

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'CLOTURE_REALISE', '0', 'Clôture de la saisie du service réalisé', 'ose_workflow.peut_cloturer_realise', 'ose_workflow.realise_cloture', 'Application\Service\Workflow\Step\ClotureRealiseStep', '1', null, 115);

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'DEMANDE_MEP', '1', 'Demande mise en paiemant', 'ose_workflow.peut_demander_mep', 'ose_workflow.possede_demande_mep', 'Application\Service\Workflow\Step\DemandeMepStep', '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 140);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'SAISIE_MEP',  '1', 'Mise en paiement',         'ose_workflow.peut_saisir_mep',   'ose_workflow.possede_mep',         'Application\Service\Workflow\Step\MepStep',        '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 150);


create or replace PACKAGE OSE_WORKFLOW AS 

  PROCEDURE add_intervenant_to_update         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenant_etapes         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_all_intervenants_etapes    (p_annee_id NUMERIC DEFAULT 2014);
  PROCEDURE Process_Intervenant_Etape (p_intervenant_id NUMERIC) ;
  
  TYPE T_LIST_STRUCTURE_ID IS TABLE OF NUMBER INDEX BY PLS_INTEGER;

  -- liste d'ids de structures
  l_structures_ids T_LIST_STRUCTURE_ID;
  
  --
  -- Fetch des ids des structures d'intervention (enseignement)
  --
  PROCEDURE fetch_struct_ens_ids_tvh          (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ens_ids              (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ens_realise_ids      (p_intervenant_id NUMERIC);
  
  --
  -- Fetch des ids des structures d'intervention (référentiel)
  --
  PROCEDURE fetch_struct_ref_ids_tvh          (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ref_ids              (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ref_realise_ids      (p_intervenant_id NUMERIC);
  
  --
  -- Fetch des ids des structures d'intervention (enseignement + référentiel)
  --
  PROCEDURE fetch_struct_ensref_ids          (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ensref_realis_ids   (p_intervenant_id NUMERIC);
  
    
  
  
  --------------------------------------------------------------------------------------------------------------------------
  -- Règles de pertinence et de franchissement des étapes
  --------------------------------------------------------------------------------------------------------------------------
  --
  -- Données personnelles
  --
  FUNCTION peut_saisir_dossier                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_dossier                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION dossier_valide                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Enseignements
  --  
  FUNCTION peut_saisir_service                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION possede_services_tvh               (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services                   (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services_realises          (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION service_valide_tvh                 (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_valide                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_realise_valide             (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION peut_cloturer_realise              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION realise_cloture                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Référentiel
  --
  FUNCTION peut_saisir_referentiel            (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION possede_referentiel_tvh            (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_referentiel                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_referentiel_realise        (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION referentiel_valide_tvh             (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_valide                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_realise_valide         (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Pièces justificatives
  --
  FUNCTION peut_saisir_pj                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_valider_pj                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION pj_oblig_fournies                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION pj_oblig_validees                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Agréments
  --
  FUNCTION necessite_agrement_cr              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_ca              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION agrement_cr_fourni                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_ca_fourni                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Contrat / avenant
  --
  FUNCTION necessite_contrat                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_contrat                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Paiement
  --
  FUNCTION peut_demander_mep                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_demande_mep                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_mep                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_mep                        (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

END OSE_WORKFLOW;
/


create or replace PACKAGE BODY       "OSE_WORKFLOW" AS

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
  BEGIN
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
    
    --
    -- Année concernée.
    --
    select i.annee_id into v_annee_id from intervenant i where i.id = p_intervenant_id;
    
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
    SELECT si.peut_saisir_service INTO res FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
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
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/;
    ELSE
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/
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
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/;
    ref_rec ref_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
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
      select count(*) into res from v_indic_attente_mep where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_indic_attente_mep where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
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
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;


END OSE_WORKFLOW;
/



--
-- Génération des progressions de tous les intervenants (~ 2 min)
--
begin OSE_WORKFLOW.UPDATE_ALL_INTERVENANTS_ETAPES(); end;
/





---------------------------------------------------------------------------------
-- Indicateurs
---------------------------------------------------------------------------------

update indicateur set code = 'AttenteValidationEnsPrevuVac'   where code = 'AttenteValidationEnsPrevu';
update indicateur set code = 'AttenteValidationEnsRealiseVac' where code = 'AttenteValidationEnsRealise';

Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationEnsPrevuPerm',  
    'Enseignements et référentiel',
    '325',
    '1'
);
Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationEnsRealisePerm',
    'Enseignements et référentiel',
    '375',
    '1'
);

Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationRefPrevuPerm',  
    'Enseignements et référentiel',
    '327',
    '1'
);
Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationRefRealisePerm',
    'Enseignements et référentiel',
    '377',
    '1'
);

update indicateur set code = 'AttenteDemandeMepVac' where code = 'AttenteDemandeMep';
update indicateur set code = 'AttenteMepVac'        where code = 'AttenteMep';

Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteDemandeMepPerm',  
    'Mise en paiement',
    '1150',
    '1'
);
Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteMepPerm',
    'Mise en paiement',
    '1250',
    '1'
);


Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'EnsRealisePermSaisieNonCloturee',
    'Enseignements et référentiel',
    '335',
    '1'
);

update indicateur set type = 'Enseignements et référentiel <em>Permanents</em>' where code in (
    'AttenteValidationRefRealisePerm',
    'AttenteValidationRefPrevuPerm',
    'PlafondRefRealiseDepasse',
    'PlafondRefPrevuDepasse',
    'AttenteValidationEnsPrevuPerm',
    'AttenteValidationEnsRealisePerm',
    'EnsRealisePermSaisieNonCloturee'
);

update indicateur set type = 'Enseignements et référentiel <em>Vacataires</em>' where code in (
    'AttenteValidationEnsPrevuVac',
    'AttenteValidationEnsRealiseVac'
);

update indicateur set type = 'Mise en paiement <em>Permanents</em>' where code in (
    'AttenteDemandeMepPerm',
    'AttenteMepPerm'
);

update indicateur set type = 'Mise en paiement <em>Vacataires</em>' where code in (
    'AttenteDemandeMepVac',
    'AttenteMepVac'
);






  CREATE OR REPLACE  VIEW "OSE"."V_INDIC_ATTENTE_DEMANDE_MEP" AS 
  with 
  -- total des heures comp ayant fait l'objet d'une (demande de) mise en paiement
  mep as (
    select intervenant_id, structure_id, sum(nvl(mep_heures, 0)) total_heures_mep
    from (
      -- enseignements
      select 
        fr.intervenant_id, 
        nvl(ep.structure_id, i.structure_id) structure_id, 
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service frs on mep.formule_res_service_id = frs.id --and mep.date_mise_en_paiement is null -- date_mise_en_paiement is null <=> demande
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction)
      union all
      -- referentiel
      select 
        fr.intervenant_id, 
        s.structure_id,
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service_ref frs on mep.formule_res_service_ref_id = frs.id --and mep.date_mise_en_paiement is null -- date_mise_en_paiement is null <=> demande
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction)
    )
    group by intervenant_id, structure_id
  ),
  -- total des heures comp
  hc as (
    select intervenant_id, structure_id, sum(nvl(total_heures_compl, 0)) total_heures_compl
    from (
      -- enseignements
      select 
        fr.intervenant_id, 
        nvl(ep.structure_id, i.structure_id) structure_id, 
        nvl(frs.heures_compl_fi, 0) + nvl(frs.heures_compl_fa, 0) + nvl(frs.heures_compl_fc, 0) + nvl(frs.heures_compl_fc_majorees, 0) total_heures_compl
      from formule_resultat_service frs
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      union all
      -- referentiel
      select 
        fr.intervenant_id, 
        s.structure_id,
        nvl(frs.heures_compl_referentiel, 0) total_heures_compl
      from formule_resultat_service_ref frs
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
    )
    group by intervenant_id, structure_id
  )
select to_number(i.id||hc.structure_id) id, i.id intervenant_id, i.source_code, ti.code, i.annee_id, hc.structure_id, hc.total_heures_compl, nvl(mep.total_heures_mep, 0) total_heures_mep
from intervenant i
join statut_intervenant si on si.id = i.statut_id
join type_intervenant ti on ti.id = si.type_intervenant_id
join hc on hc.intervenant_id = i.id
left join mep on mep.intervenant_id = i.id and hc.structure_id = mep.structure_id
where (
  -- un permanent doit avoir clôturé la saisie de son service réalisé ; pour un vacataire, pas besoin.
  ti.code = 'E' 
  or exists (
    select * from validation v
    join type_validation tv on v.type_validation_id = tv.id and tv.code = 'CLOTURE_REALISE' 
    where v.intervenant_id = i.id and 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
  )
)
and nvl(mep.total_heures_mep, 0) < hc.total_heures_compl
order by id, hc.structure_id
;

