
create or replace PACKAGE BODY OSE_WORKFLOW AS

  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN 
    MERGE INTO wf_tmp_intervenant t USING dual ON (t.intervenant_id = p_intervenant_id) WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID) VALUES (p_intervenant_id);
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la progression dans le workflow
   */
  PROCEDURE Update_Intervenants_Etapes 
  IS
  BEGIN
    FOR ti IN (SELECT distinct * FROM wf_tmp_intervenant) LOOP
      ose_workflow.Update_Intervenant_Etapes(ti.intervenant_id);
    END LOOP;
    DELETE FROM wf_tmp_intervenant;
  END;
  
  /**
   * Regénère la progression dans le workflow de tous les intervenants dont le statut autorise la saisie de service.
   */
  PROCEDURE Update_All_Intervenants_Etapes 
  IS
    CURSOR intervenant_cur IS 
      SELECT i.* FROM intervenant i 
      JOIN statut_intervenant si ON si.id = i.statut_id AND si.histo_destruction IS NULL AND si.peut_saisir_service = 1
      WHERE i.histo_destruction IS NULL;
  BEGIN
    FOR intervenant_rec IN intervenant_cur
    LOOP
      --DBMS_OUTPUT.put_line (intervenant_rec.nom_usuel || '(' || intervenant_rec.source_code || ')');
      ose_workflow.Update_Intervenant_Etapes(intervenant_rec.id);
    END LOOP;
  END;
  
  /**
   * Regénère la progression complète dans le workflow d'un intervenant.
   */
  PROCEDURE Update_Intervenant_Etapes (p_intervenant_id NUMERIC) 
  IS
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
    -- Parcours des étapes.
    --
    FOR etape_rec IN (
      --select e.* from wf_etape e where e.code = 'DEBUT'
      --UNION
      -- liste ordonnée des étapes sans les étapes DEBUT et FIN
      select ea.* --ea.id, ea.code, ed.id depart_etape_id, ed.code depart_etape_code
      from wf_etape_to_etape ee
      inner join wf_etape ed on ed.id = ee.depart_etape_id
      inner join wf_etape ea on ea.id = ee.arrivee_etape_id
      where ea.code <> 'FIN'
      connect by ee.depart_etape_id = prior ee.arrivee_etape_id 
      start with ed.code = 'DEBUT'
      --UNION
      --select e.* from wf_etape e where e.code = 'FIN'
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
      structures_ids.DELETE;
      -- id structure null
      structures_ids(structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 THEN
        ose_workflow.fetch_structures_ens_ids(p_intervenant_id, structures_ids);
      END IF;
      
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. structures_ids.COUNT - 1
      LOOP
        structure_id := structures_ids(i);
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
   *
   */
  PROCEDURE fetch_structures_ens_ids (p_intervenant_id NUMERIC, structures_ids IN OUT T_LIST_STRUCTURE_ID)
  IS
    i PLS_INTEGER;
  BEGIN
    i := structures_ids.COUNT;
    FOR d IN (
      SELECT distinct structure_ens_id FROM service s 
      WHERE s.intervenant_id = p_intervenant_id AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE() AND S.HISTO_DESTRUCTION IS NULL
    ) LOOP
      structures_ids(i) := d.structure_ens_id;
      i := i + 1;
    END LOOP;
  END;
  
  
  /******************** Règles métiers de pertinence et de franchissement des étapes ********************/
  
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
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
    ELSE
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id 
      JOIN etape e ON e.id = ep.etape_id
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee()
      AND s.structure_ens_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
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
  FUNCTION peut_saisir_piece_jointe (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
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
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR service_cur IS 
      SELECT s.* FROM service s 
      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
--    -- autre version : sans utilisation de la vue v_volume_horaire_etat
--    CURSOR service_cur IS 
--      SELECT s.* FROM service s 
--      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
--      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
--      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
--      JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
--      JOIN validation v on VVH.VALIDATION_ID = v.id AND V.HISTO_DESTRUCTION is null
--      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
--    CURSOR service_cur IS 
--      SELECT s.* FROM service s 
--      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
--      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
--      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre < ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
--      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
--      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
--      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    IF p_structure_id IS NULL THEN
      -- si aucune structure n'est spécifiée, on se contente du moindre service trouvé
      OPEN service_cur;
      FETCH service_cur INTO service_rec;
      IF service_cur%FOUND = TRUE THEN
        res := 1;
      END IF;
      CLOSE service_cur;
    ELSE
      -- si une structure précise est spécifiée, on se contente du moindre service trouvé concernant cette structure d'enseignement
      FOR service_rec IN service_cur
      LOOP
        IF service_rec.structure_ens_id = p_structure_id THEN
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
  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM intervenant_exterieur i JOIN dossier d ON d.id = i.dossier_id AND d.histo_destruction IS NULL
    WHERE i.id = p_intervenant_id;
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
    WHERE v.histo_destruction IS NULL 
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v 
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'REFERENTIEL' 
    WHERE v.histo_destruction IS NULL 
    AND v.intervenant_id = p_intervenant_id
    AND exists (select * from validation_vol_horaire_ref vvh where vvh.validation_id = v.id); -- les validations de VH doivent exister
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION pieces_jointes_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM (
      WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant, VALIDEES OU NON
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND tpjFourni.ID = tpjAttendu.ID
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          AO.INTERVENANT_ID  ID, 
          AO.SOURCE_CODE     SOURCE_CODE, 
          AO.TOTAL_HEURES    TOTAL_HEURES, 
          COALESCE(AO.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(FO.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM ATTENDU_OBLIGATOIRE AO
      LEFT JOIN FOURNI_OBLIGATOIRE  FO ON FO.INTERVENANT_ID = AO.INTERVENANT_ID
      WHERE AO.INTERVENANT_ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pieces_jointes_validees (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM (
      WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant et VALIDEES 
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND tpjFourni.ID = tpjAttendu.ID
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          AND pj.VALIDATION_ID IS NOT NULL -- VALIDEES
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          AO.INTERVENANT_ID  ID, 
          AO.SOURCE_CODE     SOURCE_CODE, 
          AO.TOTAL_HEURES    TOTAL_HEURES, 
          AO.NB              NB_PJ_OBLIG_ATTENDU, 
          COALESCE(FO.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM      ATTENDU_OBLIGATOIRE AO
      LEFT JOIN FOURNI_OBLIGATOIRE  FO ON FO.INTERVENANT_ID = AO.INTERVENANT_ID
      WHERE AO.INTERVENANT_ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
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
        SELECT DISTINCT i.ID, i.source_code, s.structure_ens_id
        FROM service s
        INNER JOIN intervenant i ON i.ID = s.intervenant_id AND (i.histo_destructeur_id IS NULL)
        INNER JOIN STRUCTURE comp ON comp.ID = s.structure_ens_id AND (comp.histo_destructeur_id IS NULL)
        WHERE s.histo_destructeur_id IS NULL
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND s.structure_ens_id = p_structure_id)
    ),
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL) 
        WHERE A.histo_destructeur_id IS NULL
        AND ta.code = code
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND A.structure_id = p_structure_id)
    ), 
    v_agrement AS (
      -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE i.histo_destructeur_id IS NULL
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
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL) 
        WHERE A.histo_destructeur_id IS NULL
        AND ta.code = v_code
    ), 
    v_agrement AS (
      -- nombres d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE i.histo_destructeur_id IS NULL
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
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM contrat c
    JOIN validation v ON c.validation_id = v.id AND v.histo_destruction IS NULL
    WHERE c.HISTO_DESTRUCTION IS NULL 
    AND c.intervenant_id = p_intervenant_id
    AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND c.STRUCTURE_ID = p_structure_id) 
    AND ROWNUM = 1;
    
    RETURN res;
  END;

END OSE_WORKFLOW;
/






Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'DEBUT','0','Début du workflow',null,null,null,'0');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'DONNEES_PERSO_SAISIE','0','Saisie des données personnelles','ose_workflow.peut_saisir_dossier','ose_workflow.possede_dossier','Application\Service\Workflow\Step\SaisieDossierStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'DONNEES_PERSO_VALIDATION','0','Validation des données personnelles','ose_workflow.peut_saisir_dossier','ose_workflow.dossier_valide','Application\Service\Workflow\Step\ValidationDossierStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'SERVICE_SAISIE','0','Saisie des enseignements','ose_workflow.peut_saisir_service','ose_workflow.possede_services','Application\Service\Workflow\Step\SaisieServiceStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'SERVICE_VALIDATION','1','Validation des enseignements','ose_workflow.possede_services','ose_workflow.service_valide','Application\Service\Workflow\Step\ValidationServiceStep','1');
--Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'REFERENTIEL_SAISIE','0','Saisie du référentiel','ose_workflow.peut_saisir_referentiel','ose_workflow.referentiel_valide','Application\Service\Workflow\Step\SaisieReferentielStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'REFERENTIEL_VALIDATION','0','Validation du référentiel','ose_workflow.peut_saisir_referentiel','ose_workflow.referentiel_valide','Application\Service\Workflow\Step\ValidationReferentielStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'PJ_SAISIE','0','Pièces justificatives','ose_workflow.peut_saisir_piece_jointe','ose_workflow.pieces_jointes_fournies','Application\Service\Workflow\Step\SaisiePiecesJointesStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'PJ_VALIDATION','0','Validation des pièces justificatives','ose_workflow.pieces_jointes_fournies','ose_workflow.pieces_jointes_validees','Application\Service\Workflow\Step\ValidationPiecesJointesStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'CONSEIL_RESTREINT','1','Agrément du Conseil Restreint','ose_workflow.necessite_agrement_cr','ose_workflow.agrement_cr_fourni','Application\Service\Workflow\Step\AgrementStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'CONSEIL_ACADEMIQUE','0','Agrément du Conseil Académique','ose_workflow.necessite_agrement_ca','ose_workflow.agrement_ca_fourni','Application\Service\Workflow\Step\AgrementStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'CONTRAT','1','Contrats et avenants','ose_workflow.necessite_contrat','ose_workflow.possede_contrat','Application\Service\Workflow\Step\EditionContratStep','1');
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE) values (WF_ETAPE_id_seq.nextval,'FIN','0','Fin du workflow',null,null,null,'0');

Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'DEBUT' and e2.code = 'DONNEES_PERSO_SAISIE';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'DONNEES_PERSO_SAISIE' and e2.code = 'SERVICE_SAISIE';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'SERVICE_SAISIE' and e2.code = 'PJ_SAISIE';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'PJ_SAISIE' and e2.code = 'PJ_VALIDATION';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'PJ_VALIDATION' and e2.code = 'DONNEES_PERSO_VALIDATION';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'DONNEES_PERSO_VALIDATION' and e2.code = 'SERVICE_VALIDATION';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'SERVICE_VALIDATION' and e2.code = 'REFERENTIEL_VALIDATION';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'REFERENTIEL_VALIDATION' and e2.code = 'CONSEIL_RESTREINT';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'CONSEIL_RESTREINT' and e2.code = 'CONTRAT';
Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'CONTRAT' and e2.code = 'FIN';





---------------------------------------------------------------------------------
--     Génération des progressions de tous les intervenants (~ 40 sec)
---------------------------------------------------------------------------------

begin OSE_WORKFLOW.UPDATE_ALL_INTERVENANTS_ETAPES() ; end;
/





--------------------------------------------------------
--  DDL for View V_WF_INTERVENANT_ETAPE
--------------------------------------------------------

  CREATE OR REPLACE VIEW "OSE"."V_WF_INTERVENANT_ETAPE" ("ID", "NOM_USUEL", "PRENOM", "SOURCE_CODE", "ORDRE", "ETAPE_ID", "LIBELLE", "FRANCHIE", "COURANTE") AS 
  select i.id, i.nom_usuel, i.prenom, i.source_code, ie.ordre, e.id etape_id, e.libelle, ie.franchie, ie.courante
  from wf_intervenant_etape ie 
  inner join intervenant i on i.id = ie.intervenant_id
  inner join wf_etape e on e.id = ie.etape_id
  order by i.nom_usuel, i.id, ie.ordre asc;




--
-- validation du référentiel
--
INSERT INTO TYPE_VALIDATION (
    ID,
    CODE,
    LIBELLE,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID)
  VALUES (
    TYPE_VALIDATION_id_seq.nextval,
    'REFERENTIEL',
    'Validation du référentiel',
    1,
    1);



--
-- indicateurs
--
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteValidationDonneesPerso',      'Données personnelles','AttenteValidationDonneesPerso','100','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'DonneesPersoDiffImport',             'Données personnelles','DonneesPersoDiffImport','1000','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttentePieceJustif',                 'Pièces justificatives','AttentePieceJustif','200','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteValidationPieceJustif',       'Pièces justificatives','AttenteValidationPieceJustif','210','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteValidationEns',               'Enseignements','AttenteValidationEnsIndicateurImpl','300','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteAgrementCR',                  'Agrément','AttenteAgrementCR','400','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteAgrementCA',                  'Agrément','AttenteAgrementCA','500','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteContrat',                     'Contrat / avenant','Attente Contrat','600','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteAvenant',                     'Contrat / avenant','Attente Avenant','700','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'SaisieServiceApresContratAvenant',   'Contrat / avenant','Saisie Service Apres Contrat Avenant','800','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AgrementCAMaisPasContrat',           'Contrat / avenant','AgrementCAMaisPasContrat','50','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'ContratAvenantDeposes',              'Contrat / avenant','Contrat Avenant Déposés','900','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteRetourContrat',               'Contrat / avenant','AttenteRetourContrat','950','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'PermAffectAutreIntervMeme',          'Affectation','PermAffectAutreIntervMeme','975','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'PermAffectMemeIntervAutre',          'Affectation','PermAffectMemeIntervAutre','976','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'BiatssAffectMemeIntervAutre',        'Affectation','BiatssAffectMemeIntervAutre','977','1');
