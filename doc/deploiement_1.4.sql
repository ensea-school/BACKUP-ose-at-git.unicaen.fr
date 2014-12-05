

-- drop table WF_ETAPE
CREATE TABLE WF_ETAPE (
  ID NUMBER(*,0) NOT NULL, 
	CODE VARCHAR2(64 CHAR) NOT NULL, 
	LIBELLE VARCHAR2(256 CHAR) NOT NULL, 
	PERTIN_FUNC VARCHAR2(256 CHAR) default null, 
	FRANCH_FUNC VARCHAR2(256 CHAR) default null, 
	--ROUTE VARCHAR2(128 CHAR) default null, 
	--PERTIN_RULE_CLASS VARCHAR2(256 CHAR) default null,
	--FRANCH_RULE_CLASS VARCHAR2(256 CHAR) default null, 
	STEP_CLASS VARCHAR2(256 CHAR) default null, 
	STRUCTURE_DEPENDANT NUMBER(1,0) DEFAULT 0 NOT NULL, 
  VISIBLE NUMBER(1,0) DEFAULT 1 NOT NULL, 
	CONSTRAINT WF_ETAPE_PK PRIMARY KEY (ID),
  CONSTRAINT WF_ETAPE_CODE_UN UNIQUE (CODE)
);
COMMENT ON COLUMN WF_ETAPE.CODE IS 'Code de cette étape';
--COMMENT ON COLUMN WF_ETAPE.ROUTE IS 'Nom de la route (au sens ZF2) correspondant à cette étape';
--COMMENT ON COLUMN WF_ETAPE.PERTIN_RULE_CLASS IS 'Nom complet (FQDN) de la classe PHP correspondant la règle de pertinence de cette étape';
--COMMENT ON COLUMN WF_ETAPE.FRANCH_RULE_CLASS IS 'Nom complet (FQDN) de la classe PHP correspondant la règle de franchissement de cette étape';
COMMENT ON COLUMN WF_ETAPE.PERTIN_FUNC IS 'Fonction correspondant à la règle de pertinence de cette étape ; i.e. fonction executée pour savoir si cette étape est pertinente ou non';
COMMENT ON COLUMN WF_ETAPE.FRANCH_FUNC IS 'Fonction correspondant à la règle de franchissement de cette étape ; i.e. fonction executée pour savoir si cette étape est franchie ou non';
COMMENT ON COLUMN WF_ETAPE.STEP_CLASS IS 'Nom complet (FQDN) de la classe PHP correspondant à cette étape';
COMMENT ON COLUMN WF_ETAPE.STRUCTURE_DEPENDANT IS 'Témoin indiquant si cette étape se décliner pour plusieurs structures (ex de la validation des services par structure d''enseignement)';
COMMENT ON COLUMN WF_ETAPE.VISIBLE IS 'Témoin indiquant si cette étape est visible ou non';

--drop sequence WF_ETAPE_ID_SEQ
CREATE SEQUENCE WF_ETAPE_ID_SEQ;

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, VISIBLE) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'DEBUT',
  'Début du workflow',
  0
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'DONNEES_PERSO_SAISIE',
  'Saisie des données personnelles',
  'ose_workflow.peut_saisir_dossier',
  'ose_workflow.possede_dossier',
  'Application\Service\Workflow\Step\SaisieDossierStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'DONNEES_PERSO_VALIDATION',
  'Validation des données personnelles',
  'ose_workflow.peut_saisir_dossier',
  'ose_workflow.dossier_valide',
  'Application\Service\Workflow\Step\ValidationDossierStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'SERVICE_SAISIE',
  'Saisie des enseignements',
  'ose_workflow.peut_saisir_service',
  'ose_workflow.possede_services',
  'Application\Service\Workflow\Step\SaisieServiceStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS, STRUCTURE_DEPENDANT) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'SERVICE_VALIDATION',
  'Validation des enseignements',
  'ose_workflow.possede_services',
  'ose_workflow.service_valide',
  'Application\Service\Workflow\Step\ValidationServiceStep',
  1
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'REFERENTIEL_SAISIE',
  'Saisie du référentiel',
  'ose_workflow.peut_saisir_referentiel',
  'ose_workflow.referentiel_valide',
  'Application\Service\Workflow\Step\SaisieReferentielStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'REFERENTIEL_VALIDATION',
  'Validation du référentiel',
  'ose_workflow.peut_saisir_referentiel',
  'ose_workflow.referentiel_valide',
  'Application\Service\Workflow\Step\ValidationReferentielStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'PIECES_JOINTES',
  'Pièces justificatives',
  'ose_workflow.peut_saisir_piece_jointe',
  'ose_workflow.pieces_jointes_fournies',
  'Application\Service\Workflow\Step\SaisiePiecesJointesStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS, STRUCTURE_DEPENDANT) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'CONSEIL_RESTREINT',
  'Agrément du Conseil Restreint',
  'ose_workflow.necessite_agrement_cr', -- AND possede_services
  'ose_workflow.agrement_cr_fourni',
  'Application\Service\Workflow\Step\AgrementStep',
  1
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'CONSEIL_ACADEMIQUE',
  'Agrément du Conseil Académique',
  'ose_workflow.necessite_agrement_ca', -- AND possede_services
  'ose_workflow.agrement_ca_fourni',
  'Application\Service\Workflow\Step\AgrementStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, PERTIN_FUNC, FRANCH_FUNC, STEP_CLASS) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'CONTRAT',
  'Contrats et avenants',
  'ose_workflow.necessite_contrat', -- AND possede_services
  'ose_workflow.possede_contrat',
  'Application\Service\Workflow\Step\EditionContratStep'
);

INSERT INTO WF_ETAPE (ID, CODE, LIBELLE, VISIBLE) VALUES (
  WF_ETAPE_ID_SEQ.NEXTVAL,
  'FIN',
  'Fin du workflow',
  0
);


-- DROP TABLE   WF_ETAPE_TO_ETAPE
CREATE TABLE WF_ETAPE_TO_ETAPE (
	DEPART_ETAPE_ID NUMBER(*,0) NOT NULL,
	ARRIVEE_ETAPE_ID NUMBER(*,0) NOT NULL, 
	CONSTRAINT WF_ETAPE_TO_ETAPE_PK PRIMARY KEY (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID), 
	CONSTRAINT WF_ETAPE_TO_ETAPE_DEFK FOREIGN KEY (DEPART_ETAPE_ID) REFERENCES OSE.WF_ETAPE (ID), 
	CONSTRAINT WF_ETAPE_TO_ETAPE_AEFK FOREIGN KEY (ARRIVEE_ETAPE_ID) REFERENCES OSE.WF_ETAPE (ID)
);
COMMENT ON COLUMN WF_ETAPE_TO_ETAPE.DEPART_ETAPE_ID IS 'Identifiant de étape l''étape de départ';
COMMENT ON COLUMN WF_ETAPE_TO_ETAPE.ARRIVEE_ETAPE_ID IS 'Identifiant de étape l''étape d''arrivée';

-- DROP SEQUENCE WF_ETAPE_TO_ETAPE_ID_SEQ;
CREATE SEQUENCE WF_ETAPE_TO_ETAPE_ID_SEQ;

-- DEBUT --> DONNEES_PERSO_SAISIE
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'DEBUT' AND EA.CODE = 'DONNEES_PERSO_SAISIE'
;
-- DONNEES_PERSO_SAISIE --> SERVICE_SAISIE
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'DONNEES_PERSO_SAISIE' AND EA.CODE = 'SERVICE_SAISIE'
;
-- SERVICE_SAISIE --> PIECES_JOINTES
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'SERVICE_SAISIE' AND EA.CODE = 'PIECES_JOINTES'
;
-- PIECES_JOINTES --> DONNEES_PERSO_VALIDATION
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'PIECES_JOINTES' AND EA.CODE = 'DONNEES_PERSO_VALIDATION'
;
-- DONNEES_PERSO_VALIDATION --> SERVICE_VALIDATION
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'DONNEES_PERSO_VALIDATION' AND EA.CODE = 'SERVICE_VALIDATION'
;
-- SERVICE_VALIDATION --> CONSEIL_RESTREINT
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'SERVICE_VALIDATION' AND EA.CODE = 'CONSEIL_RESTREINT'
;
-- CONSEIL_RESTREINT --> CONSEIL_ACADEMIQUE
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'CONSEIL_RESTREINT' AND EA.CODE = 'CONSEIL_ACADEMIQUE'
;
-- CONSEIL_ACADEMIQUE --> CONTRAT
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'CONSEIL_ACADEMIQUE' AND EA.CODE = 'CONTRAT'
;
-- CONTRAT --> FIN
INSERT INTO WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID, ARRIVEE_ETAPE_ID) 
  SELECT ED.ID, EA.ID FROM WF_ETAPE ED, WF_ETAPE EA WHERE ED.CODE = 'CONTRAT' AND EA.CODE = 'FIN'
;
-- verif : liste ordonnée des étapes sans les étapes DEBUT et FIN
select ea.* --ea.id, ea.code, ed.id depart_etape_id, ed.code depart_etape_code
from wf_etape_to_etape ee
inner join wf_etape ed on ed.id = ee.depart_etape_id
inner join wf_etape ea on ea.id = ee.arrivee_etape_id
where ea.code <> 'FIN'
connect by ee.depart_etape_id = prior ee.arrivee_etape_id 
start with ed.code = 'DEBUT';



--  DROP TABLE   WF_INTERVENANT_ETAPE
CREATE TABLE WF_INTERVENANT_ETAPE (
  ID NUMBER(*,0) NOT NULL, 
	INTERVENANT_ID NUMBER(*,0) NOT NULL, 
	ETAPE_ID NUMBER(*,0) NOT NULL, 
	--STRUCTURE_ID NUMBER(*,0), 
	FRANCHIE NUMBER(1,0) DEFAULT 0 NOT NULL, 
	COURANTE NUMBER(1,0) DEFAULT 0 NOT NULL, 
	--DATE_ENTREE DATE, 
	--DATE_SORTIE DATE, 
  ORDRE NUMBER(*,0) DEFAULT 0 NOT NULL, 
	CONSTRAINT WF_INTERVENANT_ETAPE_PK PRIMARY KEY (ID),
	CONSTRAINT WF_INTERVENANT_ETAPE_IFK FOREIGN KEY (INTERVENANT_ID) REFERENCES OSE.INTERVENANT (ID),
	CONSTRAINT WF_INTERVENANT_ETAPE_EFK FOREIGN KEY (ETAPE_ID) REFERENCES OSE.WF_ETAPE (ID)  
	--,CONSTRAINT WF_INTERVENANT_ETAPE_SFK FOREIGN KEY (STRUCTURE_ID) REFERENCES OSE.STRUCTURE (ID)
);
COMMENT ON COLUMN WF_INTERVENANT_ETAPE.INTERVENANT_ID IS 'Identifiant de l''intervenant concerné';
COMMENT ON COLUMN WF_INTERVENANT_ETAPE.ETAPE_ID IS 'Identifiant de l''étape concernée';
--COMMENT ON COLUMN WF_INTERVENANT_ETAPE.STRUCTURE_ID IS 'Identifiant éventuel de la structure précise concernée';
COMMENT ON COLUMN WF_INTERVENANT_ETAPE.FRANCHIE IS 'Témoin indiquant si l''étape est franchie ou non';
COMMENT ON COLUMN WF_INTERVENANT_ETAPE.COURANTE IS 'Témoin indiquant s''il s''agit ou non de l''étape courante (i.e. où se situe l''intervenant)';
--COMMENT ON COLUMN WF_INTERVENANT_ETAPE.DATE_ENTREE IS 'Date d''entrée dans l''étape';
--COMMENT ON COLUMN WF_INTERVENANT_ETAPE.DATE_SORTIE IS 'Date éventuelle de sortie de l''étape';

-- drop sequence WF_INTERVENANT_ETAPE_ID_SEQ
CREATE SEQUENCE WF_INTERVENANT_ETAPE_ID_SEQ;







create or replace PACKAGE OSE_WORKFLOW AS 

  PROCEDURE add_intervenant_to_update (p_intervenant_id NUMERIC);
  PROCEDURE update_all_intervenants_etapes;
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_intervenant_etapes (p_intervenant_id NUMERIC);
  
  FUNCTION peut_saisir_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_piece_jointe (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION dossier_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION pieces_jointes_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

END OSE_WORKFLOW;



create or replace PACKAGE BODY OSE_WORKFLOW AS

  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN
    INSERT INTO wf_tmp_intervenant (intervenant_id) VALUES (p_intervenant_id); 
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
   * Regénère la progression dans le workflow d'un intervenant précis.
   */
  PROCEDURE Update_Intervenant_Etapes (p_intervenant_id NUMERIC) 
  IS
    pertinente NUMERIC;
    franchie NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
  BEGIN
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
        
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
      -- Ajout de l'étape si elle est pertinente.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.PERTIN_RULE_CLASS || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN 
        CONTINUE;
      END IF;
      --DBMS_OUTPUT.put_line (etape_rec.code);
      
      --
      -- Marquage de l'étape "franchie" ou "courante".
      --
      IF etape_rec.FRANCH_FUNC IS NULL THEN
        franchie := 1;
      ELSE
        --franch_func := SUBSTR(etape_rec.FRANCH_RULE_CLASS, INSTR(etape_rec.FRANCH_RULE_CLASS, '\', -1) + 1);
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1); END;' USING OUT franchie, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
      END IF;
      IF courante_trouvee = 1 THEN 
        -- une étape située après l'étape courante est forcément "non courante" et "non franchie"
        courante := 0;
        franchie := 0;
      ELSE
        IF franchie = 1 THEN 
          courante := 0;
        ELSE
          courante := 1;
          courante_trouvee := 1;
        END IF;
      END IF;
      
      --
      -- Ecriture dans la table.
      --
      INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, courante, franchie, ordre) 
        SELECT wf_intervenant_etape_id_seq.nextval, p_intervenant_id, etape_rec.id, courante, franchie, ordre FROM DUAL;
        
      ordre := ordre + 1;
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
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
  BEGIN
    
    
    -- on se contente d'un service trouvé
    IF p_structure_id IS NULL THEN
      OPEN service_cur;
      FETCH service_cur INTO service_rec;
      IF service_cur%FOUND THEN
        res := 1;
      END IF;
      CLOSE service_cur;
    ELSE
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
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
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
          -- nombres de pj OBLIGATOIRES FOURNIES par chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND tpjFourni.ID = tpjAttendu.ID
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          -- %s
          AND pj.VALIDATION_ID IS NOT NULL -- %s
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      ATTENDU_FACULTATIF AS (
          -- nombres de pj FACULTATIVES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ), 
      FOURNI_FACULTATIF AS (
          -- nombres de pj FACULTATIVES FOURNIES par chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
          AND tpjFourni.ID = tpjAttendu.ID
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) ID, 
          COALESCE(AO.SOURCE_CODE, AF.SOURCE_CODE)       SOURCE_CODE, 
          COALESCE(AO.TOTAL_HEURES, AF.TOTAL_HEURES)     TOTAL_HEURES, 
          COALESCE(AO.NB, 0)                             NB_PJ_OBLIG_ATTENDU, 
          COALESCE(FO.NB, 0)                             NB_PJ_OBLIG_FOURNI, 
          COALESCE(AF.NB, 0)                             NB_PJ_FACUL_ATTENDU, 
          COALESCE(FF.NB, 0)                             NB_PJ_FACUL_FOURNI 
      FROM            ATTENDU_OBLIGATOIRE AO
      FULL OUTER JOIN ATTENDU_FACULTATIF  AF ON AF.INTERVENANT_ID = AO.INTERVENANT_ID
      LEFT JOIN       FOURNI_OBLIGATOIRE  FO ON FO.INTERVENANT_ID = AO.INTERVENANT_ID
      LEFT JOIN       FOURNI_FACULTATIF   FF ON FF.INTERVENANT_ID = AF.INTERVENANT_ID
      WHERE COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) = p_intervenant_id
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
    SELECT count(*) INTO res FROM (
      WITH 
      COMPOSANTES_ENSEIGN AS (
          -- nombre de composantes d'enseignement par intervenant
          SELECT I.ID, I.SOURCE_CODE, COUNT(distinct s.STRUCTURE_ENS_ID) NB_COMP_ENS
          FROM SERVICE s
          INNER JOIN INTERVENANT I ON I.ID = s.INTERVENANT_ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STRUCTURE comp ON comp.ID = s.STRUCTURE_ENS_ID AND (comp.HISTO_DESTRUCTEUR_ID IS NULL)
          WHERE (s.HISTO_DESTRUCTEUR_ID IS NULL) 
          -- AND s.STRUCTURE_ENS_ID = p_structure_id
          GROUP BY I.ID, I.SOURCE_CODE
      ),
      AGREMENTS_OBLIG_EXIST AS (
          -- nombre d'agréments obligatoires obtenus par intervenant et par type d'agrément
          SELECT I.ID, I.SOURCE_CODE, a.TYPE_AGREMENT_ID, COUNT(a.ID) NB_AGR_OBL_EXIST
          FROM AGREMENT a
          INNER JOIN TYPE_AGREMENT ta ON a.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN INTERVENANT I ON a.INTERVENANT_ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN TYPE_AGREMENT_STATUT tas ON I.STATUT_ID = tas.STATUT_INTERVENANT_ID AND ta.ID = tas.TYPE_AGREMENT_ID 
              AND COALESCE(I.PREMIER_RECRUTEMENT, 0) = tas.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 AND (tas.HISTO_DESTRUCTEUR_ID IS NULL) 
          WHERE (a.HISTO_DESTRUCTEUR_ID IS NULL) 
          -- AND a.STRUCTURE_ID = p_structure_id
          GROUP BY I.ID, I.SOURCE_CODE, a.TYPE_AGREMENT_ID
      )
      -- intervenants concernés de manière FACULTATIVE par le type d'agrément
      SELECT DISTINCT i.ID --, I.SOURCE_CODE, null NB_AGR_OBL_EXIST, COALESCE(c.NB_COMP_ENS, 0) NB_COMP_ENS
      FROM INTERVENANT i
      INNER JOIN TYPE_AGREMENT_STATUT tas ON i.STATUT_ID = tas.STATUT_INTERVENANT_ID AND (tas.HISTO_DESTRUCTEUR_ID IS NULL) 
          AND (i.PREMIER_RECRUTEMENT IS NULL OR i.PREMIER_RECRUTEMENT = tas.PREMIER_RECRUTEMENT) 
      INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
      --LEFT JOIN COMPOSANTES_ENSEIGN c on c.ID = i.ID
      WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
      AND i.ID = p_intervenant_id 
      AND tas.OBLIGATOIRE = 0
      AND ta.CODE = code
  
      UNION
  
      -- intervenants concernés de manière OBLIGATOIRE par le type d'agrément et possédant TOUS les agréments de ce type
      SELECT DISTINCT i.ID --, I.SOURCE_CODE, aoe.NB_AGR_OBL_EXIST, COALESCE(c.NB_COMP_ENS, 0) NB_COMP_ENS
      FROM INTERVENANT i
      INNER JOIN TYPE_AGREMENT_STATUT tas ON i.STATUT_ID = tas.STATUT_INTERVENANT_ID AND COALESCE(i.PREMIER_RECRUTEMENT, 0) = tas.PREMIER_RECRUTEMENT AND (tas.HISTO_DESTRUCTEUR_ID IS NULL)                     
      INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
      INNER JOIN AGREMENTS_OBLIG_EXIST aoe on aoe.ID = i.ID AND aoe.TYPE_AGREMENT_ID = tas.TYPE_AGREMENT_ID
      LEFT JOIN COMPOSANTES_ENSEIGN c on c.ID = i.ID
      WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
      AND i.ID = p_intervenant_id 
      AND tas.OBLIGATOIRE = 1
      AND ta.CODE = code
      -- $andCount
    );
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    code VARCHAR2(64) := 'CONSEIL_ACADEMIQUE';
  BEGIN
    SELECT count(*) INTO res FROM (
      WITH 
      COMPOSANTES_ENSEIGN AS (
          -- nombre de composantes d'enseignement par intervenant
          SELECT I.ID, I.SOURCE_CODE, COUNT(distinct s.STRUCTURE_ENS_ID) NB_COMP_ENS
          FROM SERVICE s
          INNER JOIN INTERVENANT I ON I.ID = s.INTERVENANT_ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STRUCTURE comp ON comp.ID = s.STRUCTURE_ENS_ID AND (comp.HISTO_DESTRUCTEUR_ID IS NULL)
          WHERE (s.HISTO_DESTRUCTEUR_ID IS NULL) 
          -- AND s.STRUCTURE_ENS_ID = p_structure_id
          GROUP BY I.ID, I.SOURCE_CODE
      ),
      AGREMENTS_OBLIG_EXIST AS (
          -- nombre d'agréments obligatoires obtenus par intervenant et par type d'agrément
          SELECT I.ID, I.SOURCE_CODE, a.TYPE_AGREMENT_ID, COUNT(a.ID) NB_AGR_OBL_EXIST
          FROM AGREMENT a
          INNER JOIN TYPE_AGREMENT ta ON a.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN INTERVENANT I ON a.INTERVENANT_ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN TYPE_AGREMENT_STATUT tas ON I.STATUT_ID = tas.STATUT_INTERVENANT_ID AND ta.ID = tas.TYPE_AGREMENT_ID 
              AND COALESCE(I.PREMIER_RECRUTEMENT, 0) = tas.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 AND (tas.HISTO_DESTRUCTEUR_ID IS NULL) 
          WHERE (a.HISTO_DESTRUCTEUR_ID IS NULL) 
          -- AND a.STRUCTURE_ID = p_structure_id
          GROUP BY I.ID, I.SOURCE_CODE, a.TYPE_AGREMENT_ID
      )
      -- intervenants concernés de manière FACULTATIVE par le type d'agrément
      SELECT DISTINCT i.ID --, I.SOURCE_CODE, null NB_AGR_OBL_EXIST, COALESCE(c.NB_COMP_ENS, 0) NB_COMP_ENS
      FROM INTERVENANT i
      INNER JOIN TYPE_AGREMENT_STATUT tas ON i.STATUT_ID = tas.STATUT_INTERVENANT_ID AND (tas.HISTO_DESTRUCTEUR_ID IS NULL) 
          AND (i.PREMIER_RECRUTEMENT IS NULL OR i.PREMIER_RECRUTEMENT = tas.PREMIER_RECRUTEMENT) 
      INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
      --LEFT JOIN COMPOSANTES_ENSEIGN c on c.ID = i.ID
      WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
      AND i.ID = p_intervenant_id 
      AND tas.OBLIGATOIRE = 0
      AND ta.CODE = code
  
      UNION
  
      -- intervenants concernés de manière OBLIGATOIRE par le type d'agrément et possédant TOUS les agréments de ce type
      SELECT DISTINCT i.ID --, I.SOURCE_CODE, aoe.NB_AGR_OBL_EXIST, COALESCE(c.NB_COMP_ENS, 0) NB_COMP_ENS
      FROM INTERVENANT i
      INNER JOIN TYPE_AGREMENT_STATUT tas ON i.STATUT_ID = tas.STATUT_INTERVENANT_ID AND COALESCE(i.PREMIER_RECRUTEMENT, 0) = tas.PREMIER_RECRUTEMENT AND (tas.HISTO_DESTRUCTEUR_ID IS NULL)                     
      INNER JOIN TYPE_AGREMENT ta ON tas.TYPE_AGREMENT_ID = ta.ID AND (ta.HISTO_DESTRUCTEUR_ID IS NULL)
      INNER JOIN AGREMENTS_OBLIG_EXIST aoe on aoe.ID = i.ID AND aoe.TYPE_AGREMENT_ID = tas.TYPE_AGREMENT_ID
      LEFT JOIN COMPOSANTES_ENSEIGN c on c.ID = i.ID
      WHERE (i.HISTO_DESTRUCTEUR_ID IS NULL)
      AND i.ID = p_intervenant_id 
      AND tas.OBLIGATOIRE = 1
      AND ta.CODE = code
      -- $andCount
    );
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM contrat c
    JOIN validation v ON c.validation_id = v.id AND v.histo_destruction IS NULL
    WHERE c.intervenant_id = p_intervenant_id;
    RETURN res;
  END;

END OSE_WORKFLOW;





--begin ose_workflow.update_all_intervenants_etapes(); END;
--/
--begin ose_workflow.update_intervenant_etapes(17983); end;
--/

create or replace view V_WF_INTERVENANT_ETAPE as
  select i.id, i.nom_usuel, i.prenom, i.source_code, ie.ordre, e.id etape_id, e.libelle, ie.franchie, ie.courante
  from wf_intervenant_etape ie 
  inner join intervenant i on i.id = ie.intervenant_id
  inner join wf_etape e on e.id = ie.etape_id
  order by i.nom_usuel, i.id, ie.ordre asc;

--select * from v_wf_intervenant_etape where source_code in ('34059', '15864', '24997') or id in (19573);


--------------------------------------------------------------------------------
---- Recalcul de la progression de l'intervenant dans le workflow.
--------------------------------------------------------------------------------

-- Il n'est pas possible de fonctionner simplement avec des triggers "for each row" puisque la même table ayant déclenché
-- le trigger est susceptible d'être lue dans la même transaction (exemple: insertion d'un service) ce qui provoque l'erreur
-- bien connue "ORA-04091: table XXX is mutating, trigger/function may not see it".
--
-- L'article suivant nous a donné la solution : http://oracle-base.com/articles/9i/mutating-table-exceptions.php
-- --> Utiliser une table temporaire et écrire un trigger row-level + un trigger statement-level 
-- là où auparavant on avait qu'un trigger row-level.
-- * La table temporaire est utilisée pour mémoriser l'id de l'intervenant dont il faudra regénérer la progression.
-- * Un trigger row-level est utilisé pour inscrire dans la table temporaire l'id de l'intervenant dont il faudra regénérer la progression.
-- * Un trigger statement-level (xxx_s) est utilisé pour regénérer la progression de chaque intervenant inscrit dans la table temporaire.

CREATE GLOBAL TEMPORARY TABLE wf_tmp_intervenant (
  intervenant_id NUMBER(*,0) not null
) ON COMMIT DELETE ROWS;

-- Ajout, suppression de service
CREATE OR REPLACE TRIGGER wf_trg_service AFTER DELETE OR INSERT OR UPDATE OF histo_destruction ON service 
FOR EACH ROW
BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END; 
/
CREATE OR REPLACE TRIGGER wf_trg_service_s AFTER DELETE OR INSERT OR UPDATE OF histo_destruction ON service
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/

-- Si ajout ou suppression d'agrément.
CREATE OR REPLACE TRIGGER wf_trg_agrement AFTER INSERT OR DELETE ON agrement 
FOR EACH ROW
BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END;
/
CREATE OR REPLACE TRIGGER wf_trg_agrement_s AFTER INSERT OR DELETE ON agrement
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/

-- Si validation ou dévalidation d'un contrat.
CREATE OR REPLACE TRIGGER wf_trg_contrat AFTER UPDATE OF validation_id ON contrat 
FOR EACH ROW
BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END;
/
CREATE OR REPLACE TRIGGER wf_trg_contrat_s AFTER UPDATE OF validation_id ON contrat
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/

-- Si modification ou suppression d'un dossier.
CREATE OR REPLACE TRIGGER wf_trg_dossier AFTER DELETE OR UPDATE ON dossier 
FOR EACH ROW
DECLARE
  intervenant_id NUMERIC;
BEGIN
  SELECT ID INTO intervenant_id FROM intervenant_exterieur WHERE dossier_id = :OLD.ID;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
CREATE OR REPLACE TRIGGER wf_trg_dossier_s AFTER DELETE OR UPDATE ON dossier
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/

-- Si (de)validation du Dossier.
CREATE OR REPLACE TRIGGER wf_trg_dossier_validation AFTER DELETE OR INSERT OR UPDATE OF histo_destruction ON validation 
FOR EACH ROW
DECLARE
  type_validation_id NUMERIC;
  code VARCHAR2(128);
  intervenant_id NUMERIC;
BEGIN
  type_validation_id := CASE WHEN deleting THEN :OLD.type_validation_id ELSE :NEW.type_validation_id END;
  SELECT code INTO code FROM type_validation WHERE id = type_validation_id;
  --DBMS_OUTPUT.put_line (code);
  IF code = 'DONNEES_PERSO_PAR_COMP' THEN
    intervenant_id := CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END;
    --DBMS_OUTPUT.put_line ('wf_trg_dossier_validation');
    ose_workflow.add_intervenant_to_update (intervenant_id); 
  END IF;
END;
/
CREATE OR REPLACE TRIGGER wf_trg_dossier_validation_s AFTER DELETE OR INSERT OR UPDATE OF histo_destruction ON validation 
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/

-- Si modification du lien Intervenant-->Dossier.
CREATE OR REPLACE TRIGGER wf_trg_interv_dossier AFTER UPDATE OF dossier_id ON intervenant_exterieur
FOR EACH ROW
BEGIN
  ose_workflow.add_intervenant_to_update (:NEW.ID); 
END;
/
CREATE OR REPLACE TRIGGER wf_trg_interv_dossier_s AFTER UPDATE OF dossier_id ON intervenant_exterieur
BEGIN
  ose_workflow.update_intervenants_etapes(); 
END;
/

-- Si modification du lien PJ-->Validation.
CREATE OR REPLACE TRIGGER wf_trg_pj_validation AFTER UPDATE OF validation_id ON piece_jointe 
FOR EACH ROW
DECLARE
  intervenant_id NUMERIC;
BEGIN
  SELECT ID INTO intervenant_id FROM intervenant_exterieur ie WHERE ie.dossier_id = :NEW.dossier_id;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
CREATE OR REPLACE TRIGGER wf_trg_pj_validation_s AFTER UPDATE OF validation_id ON piece_jointe
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/

-- Si modification du lien VolumeHoraire-->Validation.
CREATE OR REPLACE TRIGGER wf_trg_vh_validation AFTER DELETE OR INSERT ON validation_vol_horaire 
FOR EACH ROW
DECLARE
  vh_id NUMERIC;
  intervenant_id NUMERIC;
BEGIN
  vh_id := CASE WHEN inserting THEN :NEW.volume_horaire_id ELSE :OLD.volume_horaire_id END;
  SELECT s.intervenant_id INTO intervenant_id FROM service s JOIN volume_horaire vh ON vh.service_id = s.ID WHERE vh.ID = vh_id;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
CREATE OR REPLACE TRIGGER wf_trg_vh_validation_s AFTER DELETE OR INSERT ON validation_vol_horaire 
BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
