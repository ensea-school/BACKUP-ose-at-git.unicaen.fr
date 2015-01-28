-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/

---------------------------
--Nouveau SEQUENCE
--VOLUME_HORAIRE_REF_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."VOLUME_HORAIRE_REF_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 313 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--VALIDATION_VOL_HORAIRE__ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."VALIDATION_VOL_HORAIRE__ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau TABLE
--VOLUME_HORAIRE_REF
---------------------------
  CREATE TABLE "OSE"."VOLUME_HORAIRE_REF" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"TYPE_VOLUME_HORAIRE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_REFERENTIEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HEURES" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "VOLUME_HORAIRE_REF_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "VHR_SERVICE_REFERENTIEL_FK" FOREIGN KEY ("SERVICE_REFERENTIEL_ID")
	 REFERENCES "OSE"."SERVICE_REFERENTIEL" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "VHR_TYPE_VOLUME_HORAIRE_FK" FOREIGN KEY ("TYPE_VOLUME_HORAIRE_ID")
	 REFERENCES "OSE"."TYPE_VOLUME_HORAIRE" ("ID") ENABLE
   );
---------------------------
--Nouveau TABLE
--VALIDATION_VOL_HORAIRE_REF
---------------------------
  CREATE TABLE "OSE"."VALIDATION_VOL_HORAIRE_REF" 
   (	"VALIDATION_ID" NUMBER(*,0) NOT NULL ENABLE,
	"VOLUME_HORAIRE_REF_ID" NUMBER(*,0) NOT NULL ENABLE,
	CONSTRAINT "VALIDATION_VOL_HORAIRE_REF_PK" PRIMARY KEY ("VALIDATION_ID","VOLUME_HORAIRE_REF_ID") ENABLE,
	CONSTRAINT "VVHR_VALIDATION_FK" FOREIGN KEY ("VALIDATION_ID")
	 REFERENCES "OSE"."VALIDATION" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "VVHR_VOLUME_HORAIRE_REF_FK" FOREIGN KEY ("VOLUME_HORAIRE_REF_ID")
	 REFERENCES "OSE"."VOLUME_HORAIRE_REF" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié TABLE
--MISE_EN_PAIEMENT
---------------------------
ALTER TABLE "OSE"."MISE_EN_PAIEMENT" ADD ("FORMULE_RES_SERVICE_ID" NUMBER(*,0) NOT NULL ENABLE);
ALTER TABLE "OSE"."MISE_EN_PAIEMENT" ADD ("FORMULE_RES_SERVICE_REF_ID" NUMBER(*,0) NOT NULL ENABLE);
ALTER TABLE "OSE"."MISE_EN_PAIEMENT" DROP ("FORMULE_REFERENTIEL_ID");
ALTER TABLE "OSE"."MISE_EN_PAIEMENT" DROP ("FORMULE_SERVICE_ID");


---------------------------
--Nouveau TABLE
--FORMULE_RESULTAT_VH_REF
---------------------------
  CREATE TABLE "OSE"."FORMULE_RESULTAT_VH_REF" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_RESULTAT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"VOLUME_HORAIRE_REF_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_ASSURE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_SERVICE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_REFERENTIEL" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "FORMULE_RESULTAT_VH_REF_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "FRVHR_FORMULE_RESULTAT_FK" FOREIGN KEY ("FORMULE_RESULTAT_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "FRVHR_VOLUME_HORAIRE_REF_FK" FOREIGN KEY ("VOLUME_HORAIRE_REF_ID")
	 REFERENCES "OSE"."VOLUME_HORAIRE_REF" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié TABLE
--FORMULE_RESULTAT_VH
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT_VH" ADD ("HEURES_COMPL_FC_MAJOREES" FLOAT(126) DEFAULT 0 NOT NULL ENABLE);
ALTER TABLE "OSE"."FORMULE_RESULTAT_VH" ADD CONSTRAINT "FORMULE_RESULTAT_VH_PK" PRIMARY KEY ("ID") ENABLE;

---------------------------
--Nouveau TABLE
--FORMULE_RESULTAT_SERVICE_REF
---------------------------
DROP table FORMULE_RESULTAT_referentiel;


  CREATE TABLE "OSE"."FORMULE_RESULTAT_SERVICE_REF" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_RESULTAT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_REFERENTIEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_ASSURE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_SERVICE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_REFERENTIEL" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "FRSR_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "FRR_FORMULE_RESULTAT_FK" FOREIGN KEY ("FORMULE_RESULTAT_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "FRSR_SERVICE_REFERENTIEL_FK" FOREIGN KEY ("SERVICE_REFERENTIEL_ID")
	 REFERENCES "OSE"."SERVICE_REFERENTIEL" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié TABLE
--FORMULE_RESULTAT_SERVICE
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT_SERVICE" ADD ("HEURES_COMPL_FC_MAJOREES" FLOAT(126) DEFAULT 0 NOT NULL ENABLE);

---------------------------
--Modifié TABLE
--FORMULE_RESULTAT_MAJ
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP CONSTRAINT "FORMULE_RESULTAT_MAJ__UN";
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP ("ETAT_VOLUME_HORAIRE_ID");
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP ("TYPE_VOLUME_HORAIRE_ID");
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP CONSTRAINT "FRM_INTERVENANT_FK";
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" ADD CONSTRAINT "FORMULE_RESULTAT_MAJ__UN" UNIQUE ("INTERVENANT_ID","ANNEE_ID") ENABLE;

---------------------------
--Modifié TABLE
--FORMULE_RESULTAT
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT" ADD ("HEURES_COMPL_FC_MAJOREES" FLOAT(126) DEFAULT 0 NOT NULL ENABLE);

ALTER TABLE "OSE"."MISE_EN_PAIEMENT" DROP CONSTRAINT "MEP_FORMULE_SERVICE_FK";
ALTER TABLE "OSE"."MISE_EN_PAIEMENT" ADD CONSTRAINT "MEP_FORMULE_RESULTAT_SREF_FK" FOREIGN KEY ("FORMULE_RES_SERVICE_REF_ID") REFERENCES "OSE"."FORMULE_RESULTAT_SERVICE_REF"("ID") ENABLE;
ALTER TABLE "OSE"."MISE_EN_PAIEMENT" ADD CONSTRAINT "MEP_FORMULE_SERVICE_FK" FOREIGN KEY ("FORMULE_RES_SERVICE_ID") REFERENCES "OSE"."FORMULE_RESULTAT_SERVICE"("ID") ENABLE;

---------------------------
--Nouveau VIEW
--V_VOLUME_HORAIRE_REF_ETAT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_VOLUME_HORAIRE_REF_ETAT" 
 ( "VOLUME_HORAIRE_REF_ID", "ETAT_VOLUME_HORAIRE_ID"
  )  AS 
  SELECT 
  vhr.id volume_horaire_ref_id,
  evh.id etat_volume_horaire_id
FROM
  volume_horaire_ref vhr
  JOIN etat_volume_horaire evh ON evh.code = CASE
    WHEN EXISTS(
      SELECT * FROM validation v JOIN validation_vol_horaire_ref vvhr ON vvhr.validation_id = v.id
      WHERE vvhr.volume_horaire_ref_id = vhr.id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
    ) THEN 'valide'
    ELSE 'saisi'
  END;

/
---------------------------
--Modifié PACKAGE
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_WORKFLOW" AS 

  TYPE T_LIST_STRUCTURE_ID IS TABLE OF NUMBER INDEX BY PLS_INTEGER;

  PROCEDURE add_intervenant_to_update (p_intervenant_id NUMERIC);
  PROCEDURE update_all_intervenants_etapes;
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_intervenant_etapes (p_intervenant_id NUMERIC);
  
  PROCEDURE fetch_structures_ens_ids (p_intervenant_id NUMERIC, structures_ids IN OUT T_LIST_STRUCTURE_ID);
  
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
  FUNCTION pieces_jointes_validees (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_FORMULE" AS 

  TYPE t_intervenant IS RECORD (
    structure_id              NUMERIC,
    heures_service_statutaire FLOAT   DEFAULT 0,
    heures_service_modifie    FLOAT   DEFAULT 0
  );
  
  TYPE t_type_etat_vh IS RECORD (
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC
  );
  TYPE t_lst_type_etat_vh   IS TABLE OF t_type_etat_vh INDEX BY PLS_INTEGER;
  
  TYPE t_service_ref IS RECORD (
    id                        NUMERIC,
    structure_id              NUMERIC
  );
  TYPE t_lst_service_ref      IS TABLE OF t_service_ref INDEX BY PLS_INTEGER;
  
  TYPE t_service IS RECORD (
    id                        NUMERIC,
    taux_fi                   FLOAT   DEFAULT 1,
    taux_fa                   FLOAT   DEFAULT 0,
    taux_fc                   FLOAT   DEFAULT 0,
    ponderation_service_du    FLOAT   DEFAULT 1,
    ponderation_service_compl FLOAT   DEFAULT 1,
    structure_aff_id          NUMERIC,
    structure_ens_id          NUMERIC
  );
  TYPE t_lst_service          IS TABLE OF t_service INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire_ref IS RECORD (
    id                        NUMERIC,
    service_referentiel_id    NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0
  );
  TYPE t_lst_volume_horaire_ref   IS TABLE OF t_volume_horaire_ref INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire IS RECORD (
    id                        NUMERIC,
    service_id                NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0,
    taux_service_du           FLOAT   DEFAULT 1,
    taux_service_compl        FLOAT   DEFAULT 1
  );
  TYPE t_lst_volume_horaire   IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;

  d_intervenant         t_intervenant;
  d_type_etat_vh        t_lst_type_etat_vh;
  d_service_ref         t_lst_service_ref;
  d_service             t_lst_service;
  d_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_volume_horaire      t_lst_volume_horaire;

  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  FUNCTION NOUVEAU_RESULTAT RETURN formule_resultat%rowtype;
  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC;
  
  FUNCTION NOUVEAU_RESULTAT_SERVICE RETURN formule_resultat_service%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC;
  
  FUNCTION NOUVEAU_RESULTAT_VH RETURN formule_resultat_vh%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC;
  
  FUNCTION NOUVEAU_RESULTAT_SERVICE_REF RETURN formule_resultat_service_ref%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_SERV_REF( fr formule_resultat_service_ref%rowtype ) RETURN NUMERIC;

  FUNCTION NOUVEAU_RESULTAT_VH_REF RETURN formule_resultat_vh_ref%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_VH_REF( fvh formule_resultat_vh_ref%rowtype ) RETURN NUMERIC;

  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE CALCULER_SUR_DEMANDE; -- mise à jour de tous les items identifiés
  PROCEDURE CALCULER_TOUT;        -- mise à jour de TOUTES les données ! ! ! !

END OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--UNICAEN_OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_OSE_FORMULE" AS

  TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
  TYPE t_tableau IS RECORD (
    valeurs t_valeurs,
    total_service t_valeurs,
    total   FLOAT
  );  
  TYPE t_tableaux         IS TABLE OF t_tableau                       INDEX BY PLS_INTEGER;

  t                     t_tableaux;
  service_restant_du    t_valeurs;
  resultat              formule_resultat%rowtype;


  PROCEDURE DEBUG_TAB( tab_index PLS_INTEGER ) IS
    id PLS_INTEGER;
    id2 PLS_INTEGER;
    tab t_tableau;
  BEGIN
    tab := t(tab_index);
  
    ose_test.echo( 'Intervenant id = ' || resultat.intervenant_id );
    ose_test.echo( 'Tableau numéro ' || tab_index );
    
    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      dbms_output.put( 'Service id=' || lpad(id,6,' ') || ' Total=' || lpad(tab.total_service(id),10,' ') || ', data = ' );

      id2 := ose_formule.d_volume_horaire.FIRST;
      LOOP EXIT WHEN id2 IS NULL;
        IF ose_formule.d_volume_horaire(id2).type_volume_horaire_id = resultat.type_volume_horaire_id
        AND ose_formule.d_volume_horaire(id2).etat_volume_horaire_ordre >= resultat.etat_volume_horaire_id AND ose_formule.d_volume_horaire(id2).service_id = id THEN
          
          dbms_output.put( lpad(tab.valeurs(id2),10,' ') || ' | ' );
          
        END IF;
      id2 := ose_formule.d_volume_horaire.NEXT(id2);
      END LOOP;
      dbms_output.new_line;
      id := ose_formule.d_service.NEXT(id);
    END LOOP;
    
    
    ose_test.echo( 'TOTAL = ' || LPAD(tab.total, 10, ' ') );
  END;





  FUNCTION C_11( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_12( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_13( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_14( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;  

  FUNCTION C_15( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
    f ose_formule.t_service_ref;
  BEGIN
    f := ose_formule.d_service_ref( fr.service_referentiel_id );
  
    IF NVL(ose_formule.d_intervenant.structure_id,0) = NVL(f.structure_id,0) THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_16( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
    f ose_formule.t_service_ref;
  BEGIN
    f := ose_formule.d_service_ref( fr.service_referentiel_id );
    
    IF NVL(ose_formule.d_intervenant.structure_id,0) <> NVL(f.structure_id,0) AND NVL(f.structure_id,0) <> ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_17( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
    f ose_formule.t_service_ref;
  BEGIN
    f := ose_formule.d_service_ref( fr.service_referentiel_id );
    
    IF NVL(f.structure_id,0) = ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_21( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(11).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_22( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(12).valeurs(vh.id) * vh.taux_service_du;
  END;
  
  FUNCTION C_23( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(13).valeurs(vh.id) * vh.taux_service_du;
  END;
  
  FUNCTION C_24( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(14).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_25( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(15).valeurs( fr.id );
  END;
  
  FUNCTION C_26( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(16).valeurs( fr.id );
  END;
  
  FUNCTION C_27( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(17).valeurs( fr.id );
  END;

  FUNCTION C_31 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( resultat.service_du - t(21).total, 0 );
  END;

  FUNCTION C_32 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(31) - t(22).total, 0 );
  END;

  FUNCTION C_33 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(32) - t(23).total, 0 );
  END;

  FUNCTION C_34 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(33) - t(24).total, 0 );
  END;
  
  FUNCTION C_35 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(34) - t(25).total, 0 );
  END;

  FUNCTION C_36 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(35) - t(26).total, 0 );
  END;

  FUNCTION C_37 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(36) - t(27).total, 0 );
  END;

  FUNCTION C_41( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(21).total > 0 THEN
      RETURN t(21).valeurs(vh.id) / t(21).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_42( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(22).total > 0 THEN
      RETURN t(22).valeurs(vh.id) / t(22).total;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_43( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(23).total > 0 THEN
      RETURN t(23).valeurs(vh.id) / t(23).total;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_44( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(24).total > 0 THEN
      RETURN t(24).valeurs(vh.id) / t(24).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_45( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF t(25).total > 0 THEN
      RETURN t(25).valeurs(fr.id) / t(25).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_46( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF t(26).total > 0 THEN
      RETURN t(26).valeurs(fr.id) / t(26).total;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_47( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF t(27).total > 0 THEN
      RETURN t(27).valeurs(fr.id) / t(27).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_51( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( resultat.service_du, t(21).total ) * t(41).valeurs(vh.id);
  END;

  FUNCTION C_52( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(31), t(22).total ) * t(42).valeurs(vh.id);
  END;

  FUNCTION C_53( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(32), t(23).total ) * t(43).valeurs(vh.id);
  END;

  FUNCTION C_54( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(33), t(24).total ) * t(44).valeurs(vh.id);
  END;

  FUNCTION C_55( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(34), t(25).total ) * t(45).valeurs(fr.id);
  END;

  FUNCTION C_56( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(35), t(26).total ) * t(46).valeurs(fr.id);
  END;
  
  FUNCTION C_57( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(36), t(27).total ) * t(47).valeurs(fr.id);
  END;  

  FUNCTION C_61( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(21).valeurs(vh.id) > 0 THEN
      RETURN t(51).valeurs(vh.id) / t(21).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_62( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(22).valeurs(vh.id) > 0 THEN
      RETURN t(52).valeurs(vh.id) / t(22).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_63( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(23).valeurs(vh.id) > 0 THEN
      RETURN t(53).valeurs(vh.id) / t(23).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_64( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(24).valeurs(vh.id) > 0 THEN
      RETURN t(54).valeurs(vh.id) / t(24).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_65( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF t(25).valeurs(fr.id) > 0 THEN
      RETURN t(55).valeurs(fr.id) / t(25).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_66( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF t(26).valeurs(fr.id) > 0 THEN
      RETURN t(56).valeurs(fr.id) / t(26).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_67( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF t(27).valeurs(fr.id) > 0 THEN
      RETURN t(57).valeurs(fr.id) / t(27).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_71( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(61).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_72( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(62).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_73( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(63).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_74( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(64).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_75( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(65).valeurs(fr.id);
    END IF;
  END;

  FUNCTION C_76( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(66).valeurs(fr.id);
    END IF;
  END;
  
  FUNCTION C_77( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(67).valeurs(fr.id);
    END IF;
  END;
  
  FUNCTION C_81( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(11).valeurs(vh.id) * vh.taux_service_compl * t(71).valeurs(vh.id);
  END;

  FUNCTION C_82( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(12).valeurs(vh.id) * vh.taux_service_compl * t(72).valeurs(vh.id);
  END;

  FUNCTION C_83( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(13).valeurs(vh.id) * vh.taux_service_compl * t(73).valeurs(vh.id);
  END;
  
  FUNCTION C_84( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(14).valeurs(vh.id) * vh.taux_service_compl * t(74).valeurs(vh.id);
  END;

  FUNCTION C_85( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(15).valeurs(fr.id) * t(75).valeurs(fr.id);
  END;

  FUNCTION C_86( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(16).valeurs(fr.id) * t(76).valeurs(fr.id);
  END;

  FUNCTION C_87( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(17).valeurs(fr.id) * t(77).valeurs(fr.id);
  END;

  FUNCTION C_93( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN t(83).valeurs(vh.id) * s.ponderation_service_compl;
    ELSE
      RETURN t(83).valeurs(vh.id);
    END IF;
  END;
  
  FUNCTION C_94( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN t(84).valeurs(vh.id) * s.ponderation_service_compl;
    ELSE
      RETURN t(84).valeurs(vh.id);
    END IF;    
  END;

  FUNCTION C_101( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN t(81).valeurs(vh.id) * ( s.taux_fi + s.taux_fa );
  END;

  FUNCTION C_102( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN t(82).valeurs(vh.id) * ( s.taux_fi + s.taux_fa );
  END;
  
  FUNCTION C_103( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF t(93).valeurs(vh.id) = t(83).valeurs(vh.id) THEN
      RETURN ( t(81).valeurs(vh.id) + t(83).valeurs(vh.id)) * s.taux_fc;
    ELSE
      RETURN t(81).valeurs(vh.id) * s.taux_fc;
    END IF;
  END;
  
  FUNCTION C_104( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF t(94).valeurs(vh.id) = t(84).valeurs(vh.id) THEN
      RETURN ( t(82).valeurs(vh.id) + t(84).valeurs(vh.id)) * s.taux_fc;
    ELSE
      RETURN t(82).valeurs(vh.id) * s.taux_fc;
    END IF;
  END;

  FUNCTION C_113( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF t(93).valeurs(vh.id) <> t(83).valeurs(vh.id) THEN
      RETURN t(93).valeurs(vh.id) * s.taux_fc;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_114( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF t(94).valeurs(vh.id) <> t(84).valeurs(vh.id) THEN
      RETURN t(94).valeurs(vh.id) * s.taux_fc;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION RS_1( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(51).total_service( s.id ) + t(52).total_service( s.id ) + t(53).total_service( s.id ) + t(54).total_service( s.id );
  END;

  FUNCTION RS_2( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(101).total_service( s.id ) + t(102).total_service( s.id );
  END;

  FUNCTION RS_3( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(103).total_service( s.id ) + t(104).total_service( s.id );
  END;

  FUNCTION RS_4( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(113).total_service( s.id ) + t(114).total_service( s.id );
  END;

  FUNCTION RS_5( r ose_formule.t_service_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(55).total_service( r.id ) + t(56).total_service( r.id ) + t(57).total_service( r.id );
  END;

  FUNCTION RS_6( r ose_formule.t_service_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(85).total_service( r.id ) + t(86).total_service( r.id ) + t(87).total_service( r.id );
  END;

  FUNCTION RVH_1( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(51).valeurs(vh.id) + t(52).valeurs(vh.id) + t(53).valeurs(vh.id) + t(54).valeurs(vh.id);
  END;

  FUNCTION RVH_2( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(101).valeurs(vh.id) + t(102).valeurs(vh.id);
  END;

  FUNCTION RVH_3( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(103).valeurs(vh.id) + t(104).valeurs(vh.id);
  END;

  FUNCTION RVH_4( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(113).valeurs(vh.id) + t(114).valeurs(vh.id);
  END;

  FUNCTION RVH_5( vh ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(55).valeurs(vh.id) + t(56).valeurs(vh.id) + t(57).valeurs(vh.id);
  END;

  FUNCTION RVH_6( vh ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN t(85).valeurs(vh.id) + t(86).valeurs(vh.id) + t(87).valeurs(vh.id);
  END;

  PROCEDURE SET_T_VALUE( tab_index PLS_INTEGER, service_id PLS_INTEGER, id PLS_INTEGER, val FLOAT ) IS
  BEGIN
    t(tab_index).valeurs(id) := val;
    t(tab_index).total_service( service_id )   := t(tab_index).total_service( service_id ) + val;
    t(tab_index).total                         := t(tab_index).total + val;
  END;

  PROCEDURE CALCUL_VOLUME_HORAIRE( tab_index PLS_INTEGER, id NUMERIC ) IS
    res FLOAT;
    param ose_formule.t_volume_horaire;
  BEGIN
    param := ose_formule.d_volume_horaire(id);
    res := CASE tab_index
       WHEN  11 THEN  C_11( param ) WHEN  12 THEN  C_12( param ) WHEN  13 THEN  C_13( param ) WHEN  14 THEN  C_14( param )
       WHEN  21 THEN  C_21( param ) WHEN  22 THEN  C_22( param ) WHEN  23 THEN  C_23( param ) WHEN  24 THEN  C_24( param )
       WHEN  41 THEN  C_41( param ) WHEN  42 THEN  C_42( param ) WHEN  43 THEN  C_43( param ) WHEN  44 THEN  C_44( param )
       WHEN  51 THEN  C_51( param ) WHEN  52 THEN  C_52( param ) WHEN  53 THEN  C_53( param ) WHEN  54 THEN  C_54( param )
       WHEN  61 THEN  C_61( param ) WHEN  62 THEN  C_62( param ) WHEN  63 THEN  C_63( param ) WHEN  64 THEN  C_64( param )
       WHEN  71 THEN  C_71( param ) WHEN  72 THEN  C_72( param ) WHEN  73 THEN  C_73( param ) WHEN  74 THEN  C_74( param )
       WHEN  81 THEN  C_81( param ) WHEN  82 THEN  C_82( param ) WHEN  83 THEN  C_83( param ) WHEN  84 THEN  C_84( param )
                                                                 WHEN  93 THEN  C_93( param ) WHEN  94 THEN  C_94( param )
       WHEN 101 THEN C_101( param ) WHEN 102 THEN C_102( param ) WHEN 103 THEN C_103( param ) WHEN 104 THEN C_104( param )
                                                                 WHEN 113 THEN C_113( param ) WHEN 114 THEN C_114( param )
    END;
    SET_T_VALUE( tab_index, param.service_id, id, res );
  END;

  PROCEDURE CALCUL_SERVICE_RESTANT_DU( tab_index PLS_INTEGER ) IS
    res FLOAT;
  BEGIN
    res := CASE tab_index
      WHEN 31 THEN C_31  WHEN 32 THEN C_32  WHEN 33 THEN C_33
      WHEN 34 THEN C_34  WHEN 35 THEN C_35  WHEN 36 THEN C_36
      WHEN 37 THEN C_37
    END;
    service_restant_du(tab_index) := res;
  END;

  PROCEDURE CALCUL_VOLUME_HORAIRE_REF( tab_index PLS_INTEGER, id NUMERIC ) IS
    res FLOAT;
    param ose_formule.t_volume_horaire_ref;
  BEGIN
    param := ose_formule.d_volume_horaire_ref(id);
    res := CASE tab_index
      WHEN 15 THEN C_15( param )  WHEN 16 THEN C_16( param )  WHEN 17 THEN C_17( param )
      WHEN 25 THEN C_25( param )  WHEN 26 THEN C_26( param )  WHEN 27 THEN C_27( param )
      WHEN 45 THEN C_45( param )  WHEN 46 THEN C_46( param )  WHEN 47 THEN C_47( param )
      WHEN 55 THEN C_55( param )  WHEN 56 THEN C_56( param )  WHEN 57 THEN C_57( param )
      WHEN 65 THEN C_65( param )  WHEN 66 THEN C_66( param )  WHEN 67 THEN C_67( param )
      WHEN 75 THEN C_75( param )  WHEN 76 THEN C_76( param )  WHEN 77 THEN C_77( param )
      WHEN 85 THEN C_85( param )  WHEN 86 THEN C_86( param )  WHEN 87 THEN C_87( param )
    END;
    SET_T_VALUE( tab_index, param.service_referentiel_id, id, res );
  END;

  PROCEDURE P_CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    current_tableau           PLS_INTEGER;
    id                        PLS_INTEGER;
    id2                       PLS_INTEGER;
    val                       FLOAT;
    etat_volume_horaire_ordre NUMERIC;
    TYPE t_liste_tableaux   IS VARRAY (100) OF PLS_INTEGER;
    liste_tableaux            t_liste_tableaux;
    EVH_ORDRE NUMERIC;
  BEGIN
-- Initialisation
    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = P_CALCUL_RESULTAT_V2.ETAT_VOLUME_HORAIRE_ID;
    liste_tableaux := t_liste_tableaux();
    t.delete;
    service_restant_du.delete;

    resultat := ose_formule.nouveau_resultat;
    resultat.intervenant_id           := INTERVENANT_ID;
    resultat.annee_id                 := ANNEE_ID;
    resultat.type_volume_horaire_id   := TYPE_VOLUME_HORAIRE_ID;
    resultat.etat_volume_horaire_id   := ETAT_VOLUME_HORAIRE_ID;
    resultat.service_du               := ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie;

    liste_tableaux := t_liste_tableaux(
       11,  12,  13,  14,  15,  16,  17,
       21,  22,  23,  24,  25,  26,  27,
       31,  32,  33,  34,  35,  36,  37,
       41,  42,  43,  44,  45,  46,  47,
       51,  52,  53,  54,  55,  56,  57,
       61,  62,  63,  64,  65,  66,  67,
       71,  72,  73,  74,  75,  76,  77,
       81,  82,  83,  84,  85,  86,  87,
                 93,  94,
      101, 102, 103, 104,
                113, 114
    );

    id := ose_formule.d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        ose_formule.d_volume_horaire(id).type_volume_horaire_id = P_CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
        AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE 
      THEN
        resultat.service := resultat.service + ose_formule.d_volume_horaire( id ).heures;
      END IF;
      id := ose_formule.d_volume_horaire.NEXT(id);
    END LOOP;

    FOR i IN liste_tableaux.FIRST .. liste_tableaux.LAST
    LOOP
      current_tableau := liste_tableaux(i);

      IF current_tableau IN ( -- calcul pour les volumes horaires des services
         11,  12,  13,  14,
         21,  22,  23,  24,
         41,  42,  43,  44,
         51,  52,  53,  54,
         61,  62,  63,  64,
         71,  72,  73,  74,
         81,  82,  83,  84,
                   93,  94,
        101, 102, 103, 104,
                  113, 114
      ) THEN
        t(current_tableau).total := 0;      
        id2 := ose_formule.d_service.FIRST;
        LOOP EXIT WHEN id2 IS NULL;
          t(current_tableau).total_service(id2) := 0;
          id2 := ose_formule.d_service.NEXT(id2);
        END LOOP;
        
        id := ose_formule.d_volume_horaire.FIRST;
        LOOP EXIT WHEN id IS NULL;
          IF
            ose_formule.d_volume_horaire(id).type_volume_horaire_id = P_CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
            AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE 
          THEN
            CALCUL_VOLUME_HORAIRE( current_tableau, id );
          END IF;
          id := ose_formule.d_volume_horaire.NEXT(id);
        END LOOP;
        
      ELSIF current_tableau IN ( -- calcul des services restants dus
        31, 32, 33, 34, 35, 36, 37
      ) THEN
        CALCUL_SERVICE_RESTANT_DU( current_tableau );

      ELSIF current_tableau IN ( -- tableaux de calcul des volumes horaires référentiels
         15,  16,  17,
         25,  26,  27,
         45,  46,  47,
         55,  56,  57,
         65,  66,  67,
         75,  76,  77,
         85,  86,  87
      ) THEN

        t(current_tableau).total := 0;
        id2 := ose_formule.d_service_ref.FIRST;
        LOOP EXIT WHEN id2 IS NULL;
          t(current_tableau).total_service(id2) := 0;
          id2 := ose_formule.d_service_ref.NEXT(id2);
        END LOOP;
        id := ose_formule.d_volume_horaire_ref.FIRST;
        LOOP EXIT WHEN id IS NULL;
          IF
            ose_formule.d_volume_horaire_ref(id).type_volume_horaire_id = P_CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
            AND ose_formule.d_volume_horaire_ref(id).etat_volume_horaire_ordre >= EVH_ORDRE 
          THEN
            CALCUL_VOLUME_HORAIRE_REF( current_tableau, id );
          END IF;
          id := ose_formule.d_volume_horaire_ref.NEXT(id);
        END LOOP;

      END IF;
    END LOOP;

    resultat.enseignements            := t(51).total + t(52).total + t(53).total + t(54).total + t(81).total + t(82).total + t(93).total + t(94).total;
    resultat.referentiel              := t(55).total + t(56).total + t(57).total + t(85).total + t(86).total + t(87).total;
    resultat.service_assure           := resultat.enseignements + resultat.referentiel;
    resultat.heures_compl_fi          := t(101).total + t(102).total;
    resultat.heures_compl_fc          := t(103).total + t(104).total;
    resultat.heures_compl_fc_majorees := t(113).total + t(114).total;
    resultat.heures_compl_referentiel := t(85).total + t(86).total + t(87).total;
    resultat.heures_solde             := resultat.service_assure - resultat.service_du;
    IF resultat.heures_solde >= 0 THEN
      resultat.sous_service           := 0;
      resultat.heures_compl_total     := resultat.heures_solde;
    ELSE
      resultat.sous_service           := resultat.heures_solde * -1;
      resultat.heures_compl_total     := 0;
    END IF;
  END;


  PROCEDURE CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    id                    PLS_INTEGER;
    dev_null              NUMERIC;
    res_service           formule_resultat_service%rowtype;
    res_vh                formule_resultat_vh%rowtype;
    res_service_ref       formule_resultat_service_ref%rowtype;
    res_vh_ref            formule_resultat_vh_ref%rowtype;
    EVH_ORDRE             NUMERIC;
  BEGIN
    P_CALCUL_RESULTAT_V2( INTERVENANT_ID, ANNEE_ID, TYPE_VOLUME_HORAIRE_ID, ETAT_VOLUME_HORAIRE_ID );
    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = CALCUL_RESULTAT_V2.ETAT_VOLUME_HORAIRE_ID;
    resultat.id := OSE_FORMULE.ENREGISTRER_RESULTAT( resultat );

    -- répartition des résultats par service
    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      res_service := ose_formule.nouveau_resultat_service;
      res_service.formule_resultat_id       := resultat.id;
      res_service.service_id                := id;
      -- calcul des chiffres...
      res_service.heures_service            := RS_1( ose_formule.d_service(id) );
      res_service.heures_compl_fi           := RS_2( ose_formule.d_service(id) );
      res_service.heures_compl_fc           := RS_3( ose_formule.d_service(id) );
      res_service.heures_compl_fc_majorees  := RS_4( ose_formule.d_service(id) );
      res_service.service_assure            := res_service.heures_service + res_service.heures_compl_fi + res_service.heures_compl_fa + res_service.heures_compl_fc + res_service.heures_compl_fc_majorees;
      dev_null := ose_formule.ENREGISTRER_RESULTAT_SERVICE( res_service );
      id := ose_formule.d_service.NEXT(id);
    END LOOP;

    -- répartition des résultats par volumes horaires
    id := ose_formule.d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        ose_formule.d_volume_horaire(id).type_volume_horaire_id = CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
        AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE 
      THEN
        res_vh := ose_formule.nouveau_resultat_vh;
        res_vh.formule_resultat_id      := resultat.id;
        res_vh.volume_horaire_id        := id;
        -- calcul des chiffres...
        res_vh.heures_service           := RVH_1( ose_formule.d_volume_horaire(id) );
        res_vh.heures_compl_fi          := RVH_2( ose_formule.d_volume_horaire(id) );
        res_vh.heures_compl_fc          := RVH_3( ose_formule.d_volume_horaire(id) );
        res_vh.heures_compl_fc_majorees := RVH_4( ose_formule.d_volume_horaire(id) );
        res_vh.service_assure           := res_vh.heures_service + res_vh.heures_compl_fi + res_vh.heures_compl_fa + res_vh.heures_compl_fc + res_vh.heures_compl_fc_majorees;
        dev_null := ose_formule.ENREGISTRER_RESULTAT_VH( res_vh );
      END IF;
      id := ose_formule.d_volume_horaire.NEXT(id); 
    END LOOP;

    -- répartition des résultats par service référentiel
    id := ose_formule.d_service_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      res_service_ref := ose_formule.nouveau_resultat_service_ref;
      res_service_ref.formule_resultat_id      := resultat.id;
      res_service_ref.service_referentiel_id   := id;
      -- calcul des chiffres...
      res_service_ref.heures_service           := RS_5( ose_formule.d_service_ref(id) );
      res_service_ref.heures_compl_referentiel := RS_6( ose_formule.d_service_ref(id) );
      res_service_ref.service_assure           := res_service_ref.heures_service + res_service_ref.heures_compl_referentiel;
      dev_null := ose_formule.ENREGISTRER_RESULTAT_SERV_REF( res_service_ref );
      id := ose_formule.d_service_ref.NEXT(id);
    END LOOP;

    -- répartition des résultats par volumes horaires référentiel
    id := ose_formule.d_volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        ose_formule.d_volume_horaire_ref(id).type_volume_horaire_id = CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
        AND ose_formule.d_volume_horaire_ref(id).etat_volume_horaire_ordre >= EVH_ORDRE 
      THEN
        res_vh_ref := ose_formule.nouveau_resultat_vh_ref;
        res_vh_ref.formule_resultat_id      := resultat.id;
        res_vh_ref.volume_horaire_ref_id    := id;
        -- calcul des chiffres...
        res_vh_ref.heures_service           := RVH_5( ose_formule.d_volume_horaire_ref(id) );
        res_vh_ref.heures_compl_referentiel := RVH_6( ose_formule.d_volume_horaire_ref(id) );
        res_vh_ref.service_assure           := res_vh_ref.heures_service + res_vh_ref.heures_compl_referentiel;
        dev_null := ose_formule.ENREGISTRER_RESULTAT_VH_REF( res_vh_ref );
      END IF;
      id := ose_formule.d_volume_horaire_ref.NEXT(id); 
    END LOOP;

  END;

  PROCEDURE DEBUG_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC, TAB_ID PLS_INTEGER ) IS
  BEGIN
    OSE_FORMULE.POPULATE( INTERVENANT_ID, ANNEE_ID );
    P_CALCUL_RESULTAT_V2( INTERVENANT_ID, ANNEE_ID, TYPE_VOLUME_HORAIRE_ID, ETAT_VOLUME_HORAIRE_ID );
    DEBUG_TAB(TAB_ID);
  END;

END UNICAEN_OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS

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
      DBMS_OUTPUT.put_line ('wf_tmp_intervenant.intervenant_id = '||ti.intervenant_id);
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
---------------------------
--Modifié PACKAGE BODY
--OSE_TEST
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_TEST" AS
  TYPE OUT_LIST IS TABLE OF CLOB;

  SUCCES_SHOWN BOOLEAN DEFAULT TRUE;
  T_SUCCES_COUNT NUMERIC DEFAULT 0;
  T_ECHECS_COUNT NUMERIC DEFAULT 0;
  A_SUCCES_COUNT NUMERIC DEFAULT 0;
  A_ECHECS_COUNT NUMERIC DEFAULT 0;
  CURRENT_TEST CLOB;
  CURRENT_TEST_OUTPUT_BUFFER OUT_LIST := OUT_LIST();
  CURRENT_TEST_OUTPUT_BUFFER_ERR BOOLEAN;
  
  PROCEDURE SHOW_SUCCES IS
  BEGIN
    SUCCES_SHOWN := true;
  END SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES IS
  BEGIN
    SUCCES_SHOWN := false;
  END HIDE_SUCCES;

  PROCEDURE DEBUT( TEST_NAME CLOB ) IS
  BEGIN
    CURRENT_TEST := TEST_NAME;
    CURRENT_TEST_OUTPUT_BUFFER_ERR := FALSE;
    echo (' '); echo('TEST ' || TEST_NAME || ' >>>>>>>>>>' );
  END;

  PROCEDURE FIN IS
    TEST_NAME CLOB;
  BEGIN
    IF CURRENT_TEST_OUTPUT_BUFFER_ERR THEN
      T_ECHECS_COUNT := T_ECHECS_COUNT + 1;
      echo('>>>>>>>>>> FIN DU TEST ' || CURRENT_TEST ); echo (' ');
      CURRENT_TEST := NULL;

      FOR i IN 1 .. CURRENT_TEST_OUTPUT_BUFFER.COUNT LOOP
        echo( CURRENT_TEST_OUTPUT_BUFFER(i) );
      END LOOP;
    ELSE
      T_SUCCES_COUNT := T_SUCCES_COUNT + 1;
      TEST_NAME := CURRENT_TEST;
      CURRENT_TEST := NULL;
      echo('SUCCÈS DU TEST : ' || TEST_NAME );
    END IF;
    CURRENT_TEST_OUTPUT_BUFFER.DELETE; -- clear buffer
  END;

  PROCEDURE ECHO( MSG CLOB ) IS
  BEGIN
    IF CURRENT_TEST IS NULL THEN
      dbms_output.put_line(MSG);
    ELSE
      CURRENT_TEST_OUTPUT_BUFFER.EXTEND;
      CURRENT_TEST_OUTPUT_BUFFER (CURRENT_TEST_OUTPUT_BUFFER.LAST) := MSG;
    END IF;
  END;

  PROCEDURE INIT IS
  BEGIN
    T_SUCCES_COUNT  := 0;
    T_ECHECS_COUNT  := 0;
    A_SUCCES_COUNT  := 0;
    A_ECHECS_COUNT  := 0;
    CURRENT_TEST    := NULL;
  END INIT;

  PROCEDURE SHOW_STATS IS
  BEGIN
    echo ( ' ' );
    echo ( '********************************* STATISTIQUES *********************************' );
    echo ( ' ' );
    echo ( '   - nombre de tests passés avec succès :       ' || T_SUCCES_COUNT );
    echo ( '   - nombre de tests ayant échoué :             ' || T_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '   - nombre d''assertions passés avec succès :   ' || A_SUCCES_COUNT );
    echo ( '   - nombre d''assertions ayant échoué :         ' || A_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '********************************************************************************' );
    echo ( ' ' );
  END;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB ) IS
  BEGIN
    IF condition THEN
      A_SUCCES_COUNT := A_SUCCES_COUNT + 1;
      IF SUCCES_SHOWN THEN
        ECHO('        SUCCÈS : ' || MSG );
      END IF;
    ELSE
      A_ECHECS_COUNT := A_ECHECS_COUNT + 1;
      CURRENT_TEST_OUTPUT_BUFFER_ERR := TRUE;
      ECHO('        ** ECHEC ** : ' || MSG );
    END IF;
  END;
  
  PROCEDURE ADD_BUFFER( table_name VARCHAR2, id NUMERIC ) IS
  BEGIN
    INSERT INTO TEST_BUFFER( ID, TABLE_NAME, DATA_ID ) 
                    VALUES ( TEST_BUFFER_ID_SEQ.NEXTVAL, table_name, id );
  END;
  
  PROCEDURE DELETE_TEST_DATA IS
  BEGIN
    FOR tb IN (SELECT * FROM TEST_BUFFER)
    LOOP
      EXECUTE IMMEDIATE 'DELETE FROM ' || tb.table_name || ' WHERE ID = ' || tb.data_id;
    END LOOP;
    DELETE FROM TEST_BUFFER;
  END;
  
  FUNCTION GET_USER RETURN NUMERIC IS
  BEGIN
    RETURN 1; -- utilisateur réservé aux tests... (à revoir!!)
  END;
 
  FUNCTION GET_SOURCE RETURN NUMERIC IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.source s WHERE s.code = 'TEST';
    RETURN res_id;
  END;
  
  
  FUNCTION GET_CIVILITE( libelle_court VARCHAR2 DEFAULT NULL ) RETURN civilite%rowtype IS
    res civilite%rowtype;
  BEGIN
    SELECT * INTO res FROM civilite WHERE
      (OSE_DIVERS.LIKED( libelle_court, GET_CIVILITE.libelle_court ) = 1 OR GET_CIVILITE.libelle_court IS NULL) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENANT( code VARCHAR2 DEFAULT NULL ) RETURN type_intervenant%rowtype IS
    res type_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervenant WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_INTERVENANT.code ) = 1 OR GET_TYPE_INTERVENANT.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENANT_BY_ID( id NUMERIC ) RETURN type_intervenant%rowtype IS
    res type_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervenant WHERE
      id = GET_TYPE_INTERVENANT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_STATUT_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN statut_intervenant%rowtype IS
    res statut_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM statut_intervenant WHERE
      (OSE_DIVERS.LIKED( source_code, GET_STATUT_INTERVENANT.source_code ) = 1 OR GET_STATUT_INTERVENANT.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STATUT_INTERVENANT_BY_ID( id NUMERIC ) RETURN statut_intervenant%rowtype IS
    res statut_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM statut_intervenant WHERE id = GET_STATUT_INTERVENANT_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_STRUCTURE( code VARCHAR2 DEFAULT NULL ) RETURN type_structure%rowtype IS
    res type_structure%rowtype;
  BEGIN
    SELECT * INTO res FROM type_structure WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_STRUCTURE.code ) = 1 OR GET_TYPE_STRUCTURE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STRUCTURE( source_code VARCHAR2 DEFAULT NULL ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE
      (OSE_DIVERS.LIKED( source_code, GET_STRUCTURE.source_code ) = 1 OR GET_STRUCTURE.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE id = GET_STRUCTURE_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION GET_STRUCTURE_ENS_BY_NIVEAU( niveau NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE
      niveau = GET_STRUCTURE_ENS_BY_NIVEAU.niveau AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STRUCTURE_UNIV RETURN "STRUCTURE"%rowtype IS
    res "STRUCTURE"%rowtype;
  BEGIN
    SELECT * INTO res FROM "STRUCTURE" WHERE source_code = 'UNIV' AND histo_destruction IS NULL ;
    RETURN res;  
  END;

  FUNCTION ADD_STRUCTURE(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    parente_id    NUMERIC,
    type_id       NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    parente  structure%rowtype;
    niv2_id  NUMERIC;
  BEGIN
    entity_id := STRUCTURE_ID_SEQ.NEXTVAL;
    IF parente_id IS NOT NULL THEN
      parente := GET_STRUCTURE_BY_ID( parente_id );
      niv2_id := CASE
        WHEN parente.niveau = 1 THEN entity_id
        WHEN parente.niveau = 2 THEN parente_id
        WHEN parente.niveau = 3 THEN parente.parente_id
        WHEN parente.niveau = 4 THEN GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id
        WHEN parente.niveau = 5 THEN GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id ).parente_id
        WHEN parente.niveau = 6 THEN GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id ).parente_id ).parente_id
      END;
    END IF;
    INSERT INTO STRUCTURE (
      ID,
      LIBELLE_LONG,
      LIBELLE_COURT,
      PARENTE_ID,
      STRUCTURE_NIV2_ID,
      TYPE_ID,
      ETABLISSEMENT_ID,
      NIVEAU,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle_long,
      libelle_court,
      parente_id,
      niv2_id,
      type_id,
      OSE_PARAMETRE.GET_ETABLISSEMENT,
      NVL( parente.niveau, 1),
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'structure', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      (OSE_DIVERS.LIKED( source_code, GET_INTERVENANT.source_code ) = 1 OR GET_INTERVENANT.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_ID( id NUMERIC DEFAULT NULL ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE id = GET_INTERVENANT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_STATUT( statut_id NUMERIC ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      statut_id = GET_INTERVENANT_BY_STATUT.statut_id AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_TYPE( type_id NUMERIC ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      type_id = GET_INTERVENANT_BY_TYPE.type_id AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;  
  END;

  FUNCTION ADD_INTERVENANT(
    civilite_id     NUMERIC,
    nom_usuel       VARCHAR2,
    prenom          VARCHAR2,
    date_naissance  DATE,
    email           VARCHAR2,
    statut_id       NUMERIC,
    structure_id    NUMERIC,
    source_code     VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    statut statut_intervenant%rowtype;
    type_interv type_intervenant%rowtype;
  BEGIN
    entity_id := INTERVENANT_ID_SEQ.NEXTVAL;
    statut := GET_STATUT_INTERVENANT_BY_ID( statut_id );
    type_interv := GET_TYPE_INTERVENANT_BY_ID( statut.type_intervenant_id );
    INSERT INTO INTERVENANT (
      ID,
      CIVILITE_ID,
      NOM_USUEL,
      PRENOM,
      NOM_PATRONYMIQUE,
      DATE_NAISSANCE,
      PAYS_NAISSANCE_CODE_INSEE,
      PAYS_NAISSANCE_LIBELLE,
      EMAIL,
      TYPE_ID,
      STATUT_ID,
      STRUCTURE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      civilite_id,
      nom_usuel,
      prenom,
      nom_usuel,
      date_naissance,
      100,
      'FRANCE',
      email,
      type_interv.id,
      statut_id,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant', entity_id);
    IF type_interv.code = 'P' THEN
      INSERT INTO INTERVENANT_PERMANENT(
        ID,
        SOURCE_ID,
        SOURCE_CODE,
        HISTO_CREATEUR_ID,
        HISTO_MODIFICATEUR_ID
      )VALUES(
        entity_id,
        GET_SOURCE,
        source_code,
        GET_USER,
        GET_USER
      );
      INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant_permanent', entity_id);
    END IF;
    IF type_interv.code = 'E' THEN
      INSERT INTO INTERVENANT_EXTERIEUR(
        ID,
        SOURCE_ID,
        SOURCE_CODE,
        HISTO_CREATEUR_ID,
        HISTO_MODIFICATEUR_ID
      )VALUES(
        entity_id,
        GET_SOURCE,
        source_code,
        GET_USER,
        GET_USER
      );
      INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant_exterieur', entity_id);
    END IF;
    RETURN entity_id;
  END;

  FUNCTION GET_GROUPE_TYPE_FORMATION( source_code VARCHAR2 DEFAULT NULL ) RETURN groupe_type_formation%rowtype IS
    res groupe_type_formation%rowtype;
  BEGIN
    SELECT * INTO res FROM groupe_type_formation WHERE
      (OSE_DIVERS.LIKED( source_code, GET_GROUPE_TYPE_FORMATION.source_code ) = 1 OR GET_GROUPE_TYPE_FORMATION.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_GROUPE_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL;
    INSERT INTO GROUPE_TYPE_FORMATION (
      ID,
      LIBELLE_COURT,
      LIBELLE_LONG,
      ORDRE,
      PERTINENCE_NIVEAU,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      entity_id,
      libelle_court,
      libelle_long,
      999,
      0,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'groupe_type_formation', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_TYPE_FORMATION( source_code VARCHAR2 ) RETURN type_formation%rowtype IS
    res type_formation%rowtype;
  BEGIN
    SELECT * INTO res FROM type_formation WHERE
      (OSE_DIVERS.LIKED( source_code, GET_TYPE_FORMATION.source_code ) = 1 OR GET_TYPE_FORMATION.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    groupe_id     NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := TYPE_FORMATION_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_FORMATION(
      ID,
      LIBELLE_LONG,
      LIBELLE_COURT,
      GROUPE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      entity_id,
      libelle_long,
      libelle_court,
      groupe_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_formation', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_ETAPE( source_code VARCHAR2 DEFAULT NULL ) RETURN etape%rowtype IS
    res etape%rowtype;
  BEGIN
    SELECT * INTO res FROM etape WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ETAPE.source_code ) = 1 OR GET_ETAPE.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_ETAPE(
    libelle           VARCHAR2,
    type_formation_id NUMERIC,
    niveau            NUMERIC,
    structure_id      NUMERIC,
    source_code       VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ETAPE_ID_SEQ.NEXTVAL;
    INSERT INTO ETAPE (
      ID,
      LIBELLE,
      TYPE_FORMATION_ID,
      NIVEAU,
      SPECIFIQUE_ECHANGES,
      STRUCTURE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle,
      type_formation_id,
      niveau,
      0,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'etape', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_PERIODE( code VARCHAR2 DEFAULT NULL ) RETURN periode%rowtype IS
    res periode%rowtype;
  BEGIN
    SELECT * INTO res FROM periode WHERE
      (OSE_DIVERS.LIKED( code, GET_PERIODE.code ) = 1 OR GET_PERIODE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_ELEMENT_PEDAGOGIQUE( source_code VARCHAR2 DEFAULT NULL ) RETURN element_pedagogique%rowtype IS
    res element_pedagogique%rowtype;
  BEGIN
    SELECT * INTO res FROM element_pedagogique WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ELEMENT_PEDAGOGIQUE.source_code ) = 1 OR GET_ELEMENT_PEDAGOGIQUE.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_ELEMENT_PEDAGOGIQUE_BY_ID( ID NUMERIC ) RETURN element_pedagogique%rowtype IS
    res element_pedagogique%rowtype;
  BEGIN
    SELECT * INTO res FROM element_pedagogique WHERE id = GET_ELEMENT_PEDAGOGIQUE_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION ADD_ELEMENT_PEDAGOGIQUE(
    libelle       VARCHAR2,
    etape_id      NUMERIC,
    structure_id  NUMERIC,
    periode_id    NUMERIC,
    taux_foad     FLOAT,
    taux_fi       FLOAT,
    taux_fc       FLOAT,
    taux_fa       FLOAT,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ch_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_PEDAGOGIQUE (
      ID,
      LIBELLE,
      ETAPE_ID,
      STRUCTURE_ID,
      PERIODE_ID,
      TAUX_FOAD,
      TAUX_FI,
      TAUX_FC,
      TAUX_FA,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle,
      etape_id,
      structure_id,
      periode_id,
      taux_foad,
      taux_fi,
      taux_fc,
      taux_fa,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    ch_id := CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
    INSERT INTO CHEMIN_PEDAGOGIQUE (
      ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ORDRE,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      ch_id,
      entity_id,
      etape_id,
      9999999,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'element_pedagogique', entity_id);
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'chemin_pedagogique', ch_id);
    RETURN entity_id;
  END;

  FUNCTION GET_TYPE_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN type_modulateur%rowtype IS
    res type_modulateur%rowtype;
  BEGIN
    SELECT * INTO res FROM type_modulateur WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_MODULATEUR.code ) = 1 OR GET_TYPE_MODULATEUR.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_TYPE_MODULATEUR(
    code        VARCHAR2,
    libelle     VARCHAR2,
    publique    NUMERIC,
    obligatoire NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    tms_id    NUMERIC;
    structure_id NUMERIC;
  BEGIN
    entity_id := TYPE_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_MODULATEUR (
      ID,
      CODE,
      LIBELLE,
      PUBLIQUE,
      OBLIGATOIRE,
      SAISIE_PAR_ENSEIGNANT,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle,
      publique,
      obligatoire,
      0,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_modulateur', entity_id);
    structure_id := ose_test.get_structure_univ().id;
    tms_id := TYPE_MODULATEUR_STRUCTU_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_MODULATEUR_STRUCTURE(
      ID,
      TYPE_MODULATEUR_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      tms_id,
      entity_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_modulateur_structure', tms_id);
    RETURN entity_id;
  END;

  FUNCTION GET_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN modulateur%rowtype IS
    res modulateur%rowtype;
  BEGIN
    SELECT * INTO res FROM modulateur WHERE
      (OSE_DIVERS.LIKED( code, GET_MODULATEUR.code ) = 1 OR GET_MODULATEUR.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_MODULATEUR(
    code                      VARCHAR2,
    libelle                   VARCHAR2,
    type_modulateur_id        NUMERIC,
    ponderation_service_du    FLOAT,
    ponderation_service_compl FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO MODULATEUR (
      ID,
      CODE,
      LIBELLE,
      TYPE_MODULATEUR_ID,
      PONDERATION_SERVICE_DU,
      PONDERATION_SERVICE_COMPL,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle,
      type_modulateur_id,
      ponderation_service_du,
      ponderation_service_compl,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'modulateur', entity_id);
    RETURN entity_id;
  END;

  FUNCTION ADD_ELEMENT_MODULATEUR(
    element_id    NUMERIC,
    modulateur_id NUMERIC,
    annee_id      NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_MODULATEUR (
      ID,
      ELEMENT_ID,
      MODULATEUR_ID,
      ANNEE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      element_id,
      modulateur_id,
      annee_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'element_modulateur', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_FONCTION_REFERENTIEL( code VARCHAR2 DEFAULT NULL ) RETURN fonction_referentiel%rowtype IS
    res fonction_referentiel%rowtype;
  BEGIN
    SELECT * INTO res FROM fonction_referentiel WHERE
      (OSE_DIVERS.LIKED( code, GET_FONCTION_REFERENTIEL.code ) = 1 OR GET_FONCTION_REFERENTIEL.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_FONCTION_REFERENTIEL(
    code          VARCHAR2,
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    plafond       FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := FONCTION_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO FONCTION_REFERENTIEL (
      ID,
      CODE,
      LIBELLE_LONG,
      LIBELLE_COURT,
      PLAFOND,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle_long,
      libelle_court,
      plafond,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'fonction_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_SERVICE_REFERENTIEL(
    fonction_id     NUMERIC,
    intervenant_id  NUMERIC,
    structure_id    NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO SERVICE_REFERENTIEL (
      ID,
      FONCTION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      ANNEE_ID,
      HEURES,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      fonction_id,
      intervenant_id,
      structure_id,
      annee_id,
      heures,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_MODIFICATION_SERVICE_DU(
    intervenant_id  NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT,
    motif_id        NUMERIC,
    commentaires    CLOB DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := MODIFICATION_SERVICE_DU_ID_SEQ.NEXTVAL;
    INSERT INTO MODIFICATION_SERVICE_DU (
      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      HEURES,
      MOTIF_ID,
      COMMENTAIRES,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      annee_id,
      heures,
      motif_id,
      commentaires,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'modification_service_du', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_MOTIF_MODIFICATION_SERVICE( code VARCHAR2 DEFAULT NULL, multiplicateur FLOAT DEFAULT NULL ) RETURN motif_modification_service%rowtype IS
    res motif_modification_service%rowtype;
  BEGIN
    SELECT * INTO res FROM motif_modification_service WHERE
      (OSE_DIVERS.LIKED( code, GET_MOTIF_MODIFICATION_SERVICE.code ) = 1 OR GET_MOTIF_MODIFICATION_SERVICE.code IS NULL)
      AND (multiplicateur = GET_MOTIF_MODIFICATION_SERVICE.multiplicateur OR GET_MOTIF_MODIFICATION_SERVICE.multiplicateur IS NULL)
      AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_ETABLISSEMENT( source_code VARCHAR2 DEFAULT NULL ) RETURN etablissement%rowtype IS
    res etablissement%rowtype;
  BEGIN
    SELECT * INTO res FROM etablissement WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ETABLISSEMENT.source_code ) = 1 OR (GET_ETABLISSEMENT.source_code IS NULL AND id <> OSE_PARAMETRE.GET_ETABLISSEMENT))
      AND histo_destruction IS NULL
      AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_SERVICE_BY_ID( id NUMERIC ) RETURN service%rowtype IS
    res service%rowtype;
  BEGIN
    SELECT * INTO res FROM service WHERE id = GET_SERVICE_BY_ID.id;
    RETURN res;
  END;

  FUNCTION ADD_SERVICE(
    intervenant_id          NUMERIC,
    annee_id                NUMERIC,
    element_pedagogique_id  NUMERIC,
    etablissement_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ep element_pedagogique%rowtype;
    interv intervenant%rowtype;
  BEGIN
    entity_id := SERVICE_ID_SEQ.NEXTVAL;
    IF element_pedagogique_id IS NOT NULL THEN
      ep := GET_ELEMENT_PEDAGOGIQUE_BY_ID( element_pedagogique_id );
    END IF;
    interv := GET_INTERVENANT_BY_ID( intervenant_id );
    INSERT INTO SERVICE (
      ID,
      INTERVENANT_ID,
      STRUCTURE_AFF_ID,
      STRUCTURE_ENS_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ANNEE_ID,
      ETABLISSEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      interv.structure_id,
      ep.structure_id,
      element_pedagogique_id,
      annee_id,
      COALESCE( ADD_SERVICE.etablissement_id, OSE_PARAMETRE.GET_ETABLISSEMENT),
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_ETAT_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN etat_volume_horaire%rowtype IS
    res etat_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM etat_volume_horaire WHERE
      (OSE_DIVERS.LIKED( code, GET_ETAT_VOLUME_HORAIRE.code ) = 1 OR GET_ETAT_VOLUME_HORAIRE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN type_volume_horaire%rowtype IS
    res type_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM type_volume_horaire WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_VOLUME_HORAIRE.code ) = 1 OR GET_TYPE_VOLUME_HORAIRE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_INTERVENTION( code VARCHAR2 DEFAULT NULL ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervention WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_INTERVENTION.code ) = 1 OR GET_TYPE_INTERVENTION.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENTION_BY_ID( id NUMERIC ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervention WHERE id = GET_TYPE_INTERVENTION_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENTION_BY_ELEMT( ELEMENT_ID NUMERIC ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT
      ti.*
    INTO
      res
    FROM
      type_intervention ti
      JOIN v_element_type_intervention eti ON eti.type_intervention_id = ti.id AND eti.element_pedagogique_id = ELEMENT_ID
    WHERE
      ti.histo_destruction IS NULL
      AND rownum = 1;
    RETURN res;
  END;

  FUNCTION GET_MOTIF_NON_PAIEMENT( code VARCHAR2 DEFAULT NULL ) RETURN motif_non_paiement%rowtype IS
    res motif_non_paiement%rowtype;
  BEGIN
    SELECT * INTO res FROM motif_non_paiement WHERE
      (OSE_DIVERS.LIKED( code, GET_MOTIF_NON_PAIEMENT.code ) = 1 OR GET_MOTIF_NON_PAIEMENT.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_VOLUME_HORAIRE( id NUMERIC DEFAULT NULL ) RETURN volume_horaire%rowtype IS
    res volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM volume_horaire WHERE
      id = GET_VOLUME_HORAIRE.id OR (GET_VOLUME_HORAIRE.id IS NULL AND histo_destruction IS NULL AND ROWNUM = 1);
    RETURN res;    
  END;

  FUNCTION ADD_VOLUME_HORAIRE(
    type_volume_horaire_id  NUMERIC,
    service_id              NUMERIC,
    periode_id              NUMERIC,
    type_intervention_id    NUMERIC,
    heures                  FLOAT,
    motif_non_paiement_id   NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := VOLUME_HORAIRE_ID_SEQ.NEXTVAL;
    INSERT INTO VOLUME_HORAIRE (
      ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_ID,
      PERIODE_ID,
      TYPE_INTERVENTION_ID,
      HEURES,
      MOTIF_NON_PAIEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      type_volume_horaire_id,
      service_id,
      periode_id,
      type_intervention_id,
      heures,
      motif_non_paiement_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'volume_horaire', entity_id);
    RETURN entity_id;
  END;

  FUNCTION ADD_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC,
    intervenant_id    NUMERIC,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := VALIDATION_ID_SEQ.NEXTVAL;
    INSERT INTO VALIDATION (
      ID,
      TYPE_VALIDATION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_validation WHERE code = 'SERVICES_PAR_COMP'),
      intervenant_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        vh.histo_destruction IS NULL AND
        s.histo_destruction IS NULL
        AND (s.structure_ens_id = ADD_VALIDATION_VOLUME_HORAIRE.structure_id OR s.structure_aff_id = ADD_VALIDATION_VOLUME_HORAIRE.structure_id)
        AND (s.intervenant_id = ADD_VALIDATION_VOLUME_HORAIRE.intervenant_id)
        AND (vh.id = ADD_VALIDATION_VOLUME_HORAIRE.volume_horaire_id OR ADD_VALIDATION_VOLUME_HORAIRE.volume_horaire_id IS NULL)
        AND (s.id = ADD_VALIDATION_VOLUME_HORAIRE.service_id OR ADD_VALIDATION_VOLUME_HORAIRE.service_id IS NULL)
    ) LOOP
      INSERT INTO VALIDATION_VOL_HORAIRE(
        VALIDATION_ID,
        VOLUME_HORAIRE_ID
      )VALUES(
        entity_id,
        vh.id
      );
    END LOOP;
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'validation', entity_id);
    RETURN entity_id;
  END;

  PROCEDURE DEL_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC,
    intervenant_id    NUMERIC,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL,
    validation_id     NUMERIC DEFAULT NULL
  ) IS
    vvh_count NUMERIC;
  BEGIN
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        vh.histo_destruction IS NULL AND
        s.histo_destruction IS NULL
        AND (s.structure_ens_id = DEL_VALIDATION_VOLUME_HORAIRE.structure_id OR s.structure_aff_id = DEL_VALIDATION_VOLUME_HORAIRE.structure_id)
        AND (s.intervenant_id = DEL_VALIDATION_VOLUME_HORAIRE.intervenant_id)
        AND (vh.id = DEL_VALIDATION_VOLUME_HORAIRE.volume_horaire_id OR DEL_VALIDATION_VOLUME_HORAIRE.volume_horaire_id IS NULL)
        AND (s.id = DEL_VALIDATION_VOLUME_HORAIRE.service_id OR DEL_VALIDATION_VOLUME_HORAIRE.service_id IS NULL)
    ) LOOP
      DELETE FROM VALIDATION_VOL_HORAIRE WHERE 
        VOLUME_HORAIRE_ID = vh.id 
        AND (VALIDATION_ID = DEL_VALIDATION_VOLUME_HORAIRE.validation_id OR DEL_VALIDATION_VOLUME_HORAIRE.validation_id IS NULL);
    END LOOP;
    IF VALIDATION_ID IS NOT NULL THEN
      SELECT count(*) INTO vvh_count FROM VALIDATION_VOL_HORAIRE WHERE VALIDATION_ID = DEL_VALIDATION_VOLUME_HORAIRE.validation_id;
      IF 0 = vvh_count THEN
        DELETE FROM validation WHERE id = VALIDATION_ID;
      END IF;
    END IF;
  END;

  FUNCTION GET_CONTRAT_BY_ID( ID NUMERIC ) RETURN contrat%rowtype IS
    res contrat%rowtype;
  BEGIN
    SELECT * INTO res FROM contrat WHERE id = GET_CONTRAT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION ADD_CONTRAT(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL    
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := CONTRAT_ID_SEQ.NEXTVAL;
    INSERT INTO CONTRAT (
      ID,
      TYPE_CONTRAT_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      NUMERO_AVENANT,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_contrat WHERE code = 'CONTRAT'),
      intervenant_id,
      structure_id,
      (SELECT MAX(numero_avenant) FROM contrat) + 1,
      GET_USER,
      GET_USER
    );
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        vh.histo_destruction IS NULL
        AND s.histo_destruction IS NULL
        AND (s.intervenant_id = ADD_CONTRAT.intervenant_id OR ADD_CONTRAT.intervenant_id IS NULL)
        AND (vh.id = ADD_CONTRAT.volume_horaire_id OR ADD_CONTRAT.volume_horaire_id IS NULL)
        AND (s.id = ADD_CONTRAT.service_id OR ADD_CONTRAT.service_id IS NULL)
        AND vh.contrat_id IS NULL
    ) LOOP
      UPDATE volume_horaire SET contrat_id = entity_id WHERE volume_horaire.id = vh.id;
    END LOOP;

    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'contrat', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION SIGNATURE_CONTRAT(
    contrat_id        NUMERIC
  ) RETURN NUMERIC IS
  BEGIN
    UPDATE contrat SET date_retour_signe = SYSDATE WHERE id = SIGNATURE_CONTRAT.contrat_id;
    RETURN contrat_id;
  END;
  
  FUNCTION ADD_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ctr contrat%rowtype;
  BEGIN
    ctr := GET_CONTRAT_BY_ID( contrat_id );

    IF ctr.validation_id IS NOT NULL THEN RETURN NULL; END IF;

    entity_id := VALIDATION_ID_SEQ.NEXTVAL;
    INSERT INTO VALIDATION (
      ID,
      TYPE_VALIDATION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_validation WHERE code = 'CONTRAT_PAR_COMP'),
      ctr.intervenant_id,
      ctr.structure_id,
      GET_USER,
      GET_USER
    );
    UPDATE contrat SET validation_id = entity_id WHERE id = ADD_CONTRAT_VALIDATION.contrat_id;
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'validation', entity_id);
    RETURN entity_id;
  END;  
  
  FUNCTION DEL_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC IS
    ctr contrat%rowtype;
  BEGIN
    ctr := GET_CONTRAT_BY_ID( contrat_id );
    
    IF ctr.validation_id IS NOT NULL THEN
      UPDATE contrat SET validation_id = NULL WHERE contrat_id = DEL_CONTRAT_VALIDATION.contrat_id;
      DELETE FROM validation WHERE id = ctr.validation_id;
    END IF;
    RETURN contrat_id;
  END;
  
  FUNCTION GET_TYPE_VALIDATION( code VARCHAR2 DEFAULT NULL ) RETURN type_validation%rowtype IS
    res type_validation%rowtype;
  BEGIN
    SELECT * INTO res FROM type_validation WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_VALIDATION.code ) = 1 OR GET_TYPE_VALIDATION.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
END OSE_TEST;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_IMPORT" IS

  FUNCTION get_date_obs RETURN Date IS
  BEGIN
    RETURN v_date_obs;
  END get_date_obs;

  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    RETURN v_current_user;
  END get_current_user;
 
 
  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;


  FUNCTION get_type_intervenant_id( src_code varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.type_intervenant s WHERE s.code = src_code;
    RETURN res_id;
  END get_type_intervenant_id;


  FUNCTION get_civilite_id( src_libelle_court varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.civilite s WHERE s.libelle_court = src_libelle_court;
    RETURN res_id;
  END get_civilite_id;


  FUNCTION get_source_id( src_code Varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.source s WHERE s.code = src_code;
    RETURN res_id;
  END get_source_id;

  PROCEDURE SYNC_LOG( message CLOB ) IS
  BEGIN
    INSERT INTO OSE.SYNC_LOG("ID","DATE_SYNC","MESSAGE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message);
  END SYNC_LOG;

  PROCEDURE SYNC_MVS IS
  BEGIN
    -- Mise à jour des vues matérialisées
    DBMS_MVIEW.REFRESH('MV_HARP_IND_DER_STRUCT', 'C');
    DBMS_MVIEW.REFRESH('MV_HARP_INDIVIDU_BANQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_HARP_INDIVIDU_STATUT', 'C');
    
    DBMS_MVIEW.REFRESH('MV_ETABLISSEMENT', 'C');
    DBMS_MVIEW.REFRESH('MV_STRUCTURE', 'C');
    DBMS_MVIEW.REFRESH('MV_ADRESSE_STRUCTURE', 'C');
    
    DBMS_MVIEW.REFRESH('MV_PERSONNEL', 'C');
    DBMS_MVIEW.REFRESH('MV_ROLE', 'C');
    
    DBMS_MVIEW.REFRESH('MV_CORPS', 'C');
    
    DBMS_MVIEW.REFRESH('MV_INTERVENANT', 'C');
    DBMS_MVIEW.REFRESH('MV_INTERVENANT_EXTERIEUR', 'C');
    DBMS_MVIEW.REFRESH('MV_INTERVENANT_PERMANENT', 'C');
    DBMS_MVIEW.REFRESH('MV_AFFECTATION_RECHERCHE', 'C');
    DBMS_MVIEW.REFRESH('MV_ADRESSE_INTERVENANT', 'C');
    
    DBMS_MVIEW.REFRESH('MV_GROUPE_TYPE_FORMATION', 'C');
    DBMS_MVIEW.REFRESH('MV_TYPE_FORMATION', 'C');
    DBMS_MVIEW.REFRESH('MV_ETAPE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_PEDAGOGIQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_EFFECTIFS', 'C');
    --DBMS_MVIEW.REFRESH('MV_ELEMENT_TAUX_REGIMES', 'C'); -- Refresh manuel une fois par an ! ! !
    DBMS_MVIEW.REFRESH('MV_CHEMIN_PEDAGOGIQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_PORTEUR_PORTE', 'C');
    DBMS_MVIEW.REFRESH('MV_DISCIPLINE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_DISCIPLINE', 'C');
    DBMS_MVIEW.REFRESH('MV_VOLUME_HORAIRE_ENS', 'C');  
  END;

  PROCEDURE SYNC_VOLUME_HORAIRE_ENS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_VOLUME_HORAIRE_ENS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_VOLUME_HORAIRE_ENS;

  PROCEDURE SYNC_ELEMENT_DISCIPLINE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_DISCIPLINE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_DISCIPLINE;

  PROCEDURE SYNC_DISCIPLINE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_DISCIPLINE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_DISCIPLINE;

  PROCEDURE SYNC_CHEMIN_PEDAGOGIQUE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_CHEMIN_PEDAGOGIQUE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_CHEMIN_PEDAGOGIQUE;

  PROCEDURE SYNC_ELEMENT_PEDAGOGIQUE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_PEDAGOGIQUE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_PEDAGOGIQUE;


  PROCEDURE SYNC_EFFECTIFS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_EFFECTIFS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_EFFECTIFS;

  PROCEDURE SYNC_ELEMENT_TAUX_REGIMES IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_TAUX_REGIMES();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_TAUX_REGIMES;

  PROCEDURE SYNC_ETAPE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ETAPE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ETAPE;

  PROCEDURE SYNC_TYPE_FORMATION IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_FORMATION();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_FORMATION;

  PROCEDURE SYNC_GROUPE_TYPE_FORMATION IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_GROUPE_TYPE_FORMATION();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_GROUPE_TYPE_FORMATION;

  PROCEDURE SYNC_ADRESSE_INTERVENANT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ADRESSE_INTERVENANT('WHERE INTERVENANT_ID IS NOT NULL');
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ADRESSE_INTERVENANT;

  PROCEDURE SYNC_AFFECTATION_RECHERCHE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_AFFECTATION_RECHERCHE('WHERE INTERVENANT_ID IS NOT NULL');
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_AFFECTATION_RECHERCHE;

  PROCEDURE SYNC_TYPE_INTERVENTION_EP IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_INTERVENTION_EP();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_INTERVENTION_EP;

  PROCEDURE SYNC_TYPE_MODULATEUR_EP IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_MODULATEUR_EP();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_MODULATEUR_EP;

  PROCEDURE SYNC_ETABLISSEMENT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ETABLISSEMENT();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ETABLISSEMENT;

  PROCEDURE SYNC_STRUCTURE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_STRUCTURE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_STRUCTURE;

  PROCEDURE SYNC_ADRESSE_STRUCTURE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ADRESSE_STRUCTURE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ADRESSE_STRUCTURE;

  PROCEDURE SYNC_PERSONNEL IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_PERSONNEL();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_PERSONNEL;
  
  PROCEDURE SYNC_ROLE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ROLE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ROLE;  

  PROCEDURE SYNC_CORPS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_CORPS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_CORPS; 

  PROCEDURE SYNC_INTERVENANT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT( -- Met à jour toutes les données sauf le statut, qui sera traité à part
        'WHERE IMPORT_ACTION IN (''delete'',''update'',''undelete'')'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT; 
  
  PROCEDURE SYNC_INTERVENANT_PERMANENT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT_PERMANENT(
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT_PERMANENT; 
  
  PROCEDURE SYNC_INTERVENANT_EXTERIEUR IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT_EXTERIEUR(
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT_EXTERIEUR;   

  PROCEDURE SYNC_TABLES IS
  BEGIN
    OSE_IMPORT.SET_CURRENT_USER( OSE_PARAMETRE.GET_OSE_USER() );
 
    SYNC_ETABLISSEMENT;
    SYNC_STRUCTURE;
    SYNC_ADRESSE_STRUCTURE;

    SYNC_PERSONNEL;
    SYNC_ROLE;
    
    SYNC_CORPS;

    SYNC_INTERVENANT;
    SYNC_INTERVENANT_EXTERIEUR;
    SYNC_INTERVENANT_PERMANENT;
    
    SYNC_AFFECTATION_RECHERCHE;
    SYNC_ADRESSE_INTERVENANT;
    SYNC_GROUPE_TYPE_FORMATION;
    SYNC_TYPE_FORMATION;
    SYNC_ETAPE;
    SYNC_ELEMENT_PEDAGOGIQUE;
    SYNC_EFFECTIFS;
    --SYNC_ELEMENT_TAUX_REGIMES; -- Synchronisation manuelle ! ! !
    SYNC_CHEMIN_PEDAGOGIQUE;
    SYNC_DISCIPLINE;
    SYNC_ELEMENT_DISCIPLINE;
    SYNC_VOLUME_HORAIRE_ENS;
    
    -- Mise à jour des sources calculées en dernier
    SYNC_TYPE_INTERVENTION_EP;
    SYNC_TYPE_MODULATEUR_EP;
  END;

  PROCEDURE SYNCHRONISATION IS
  BEGIN
    SYNC_MVS;
    SYNC_TABLES;
  END SYNCHRONISATION;

  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_VOLUME_HORAIRE_ENS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_VOLUME_HORAIRE_ENS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_VOLUME_HORAIRE_ENS.* FROM V_DIFF_VOLUME_HORAIRE_ENS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.VOLUME_HORAIRE_ENS
            ( id, ANNEE_ID,ELEMENT_DISCIPLINE_ID,HEURES,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,VOLUME_HORAIRE_ENS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_DISCIPLINE_ID,diff_row.HEURES,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ELEMENT_DISCIPLINE_ID = diff_row.ELEMENT_DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.VOLUME_HORAIRE_ENS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ELEMENT_DISCIPLINE_ID = diff_row.ELEMENT_DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.VOLUME_HORAIRE_ENS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_VOLUME_HORAIRE_ENS;



  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_MODULATEUR_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_MODULATEUR_EP.* FROM V_DIFF_TYPE_MODULATEUR_EP ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_MODULATEUR_EP
            ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_MODULATEUR_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_MODULATEUR_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_MODULATEUR_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_MODULATEUR_EP;



  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_INTERVENTION_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_INTERVENTION_EP.* FROM V_DIFF_TYPE_INTERVENTION_EP ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_INTERVENTION_EP
            ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_INTERVENTION_ID,VISIBLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_INTERVENTION_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_INTERVENTION_ID,diff_row.VISIBLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_INTERVENTION_EP;



  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_FORMATION.* FROM V_DIFF_TYPE_FORMATION ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_FORMATION
            ( id, GROUPE_ID,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.GROUPE_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_FORMATION;



  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_STRUCTURE.* FROM V_DIFF_STRUCTURE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.STRUCTURE
            ( id, ETABLISSEMENT_ID,LIBELLE_COURT,LIBELLE_LONG,NIVEAU,PARENTE_ID,STRUCTURE_NIV2_ID,TYPE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,STRUCTURE_ID_SEQ.NEXTVAL), diff_row.ETABLISSEMENT_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.NIVEAU,diff_row.PARENTE_ID,diff_row.STRUCTURE_NIV2_ID,diff_row.TYPE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_STRUCTURE;



  PROCEDURE MAJ_ROLE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ROLE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ROLE.* FROM V_DIFF_ROLE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ROLE
            ( id, PERSONNEL_ID,STRUCTURE_ID,TYPE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ROLE_ID_SEQ.NEXTVAL), diff_row.PERSONNEL_ID,diff_row.STRUCTURE_ID,diff_row.TYPE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ROLE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ROLE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ROLE;



  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PERSONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PERSONNEL.* FROM V_DIFF_PERSONNEL ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.PERSONNEL
            ( id, CIVILITE_ID,EMAIL,NOM_PATRONYMIQUE,NOM_USUEL,PRENOM,STRUCTURE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,PERSONNEL_ID_SEQ.NEXTVAL), diff_row.CIVILITE_ID,diff_row.EMAIL,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.PRENOM,diff_row.STRUCTURE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.PERSONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.PERSONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_PERSONNEL;



  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_PERMANENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_PERMANENT.* FROM V_DIFF_INTERVENANT_PERMANENT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT_PERMANENT
            ( id, CORPS_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_PERMANENT_ID_SEQ.NEXTVAL), diff_row.CORPS_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_PERMANENT;



  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_EXTERIEUR%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_EXTERIEUR.* FROM V_DIFF_INTERVENANT_EXTERIEUR ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT_EXTERIEUR
            ( id, SITUATION_FAMILIALE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_EXTERIEUR_ID_SEQ.NEXTVAL), diff_row.SITUATION_FAMILIALE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_EXTERIEUR;



  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT
            ( id, BIC,CIVILITE_ID,DATE_NAISSANCE,DEP_NAISSANCE_CODE_INSEE,DEP_NAISSANCE_LIBELLE,EMAIL,IBAN,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_CODE_INSEE,PAYS_NAISSANCE_LIBELLE,PAYS_NATIONALITE_CODE_INSEE,PAYS_NATIONALITE_LIBELLE,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,TYPE_ID,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.BIC,diff_row.CIVILITE_ID,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_CODE_INSEE,diff_row.DEP_NAISSANCE_LIBELLE,diff_row.EMAIL,diff_row.IBAN,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_CODE_INSEE,diff_row.PAYS_NAISSANCE_LIBELLE,diff_row.PAYS_NATIONALITE_CODE_INSEE,diff_row.PAYS_NATIONALITE_LIBELLE,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.TYPE_ID,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_CODE_INSEE = diff_row.PAYS_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_LIBELLE = diff_row.PAYS_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_CODE_INSEE = diff_row.PAYS_NATIONALITE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_LIBELLE = diff_row.PAYS_NATIONALITE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_CODE_INSEE = diff_row.PAYS_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_LIBELLE = diff_row.PAYS_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_CODE_INSEE = diff_row.PAYS_NATIONALITE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_LIBELLE = diff_row.PAYS_NATIONALITE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT;



  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GROUPE_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GROUPE_TYPE_FORMATION.* FROM V_DIFF_GROUPE_TYPE_FORMATION ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.GROUPE_TYPE_FORMATION
            ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE,PERTINENCE_NIVEAU, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE,diff_row.PERTINENCE_NIVEAU, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;
          UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_GROUPE_TYPE_FORMATION;



  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETAPE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETAPE.* FROM V_DIFF_ETAPE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ETAPE
            ( id, LIBELLE,NIVEAU,SPECIFIQUE_ECHANGES,STRUCTURE_ID,TYPE_FORMATION_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ETAPE_ID_SEQ.NEXTVAL), diff_row.LIBELLE,diff_row.NIVEAU,diff_row.SPECIFIQUE_ECHANGES,diff_row.STRUCTURE_ID,diff_row.TYPE_FORMATION_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ETAPE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ETAPE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETAPE;



  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETABLISSEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETABLISSEMENT.* FROM V_DIFF_ETABLISSEMENT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ETABLISSEMENT
            ( id, DEPARTEMENT,LIBELLE,LOCALISATION, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ETABLISSEMENT_ID_SEQ.NEXTVAL), diff_row.DEPARTEMENT,diff_row.LIBELLE,diff_row.LOCALISATION, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ETABLISSEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ETABLISSEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETABLISSEMENT;



  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_TAUX_REGIMES%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_TAUX_REGIMES.* FROM V_DIFF_ELEMENT_TAUX_REGIMES ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_TAUX_REGIMES
            ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,TAUX_FA,TAUX_FC,TAUX_FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_TAUX_REGIMES_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_TAUX_REGIMES;



  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PEDAGOGIQUE.* FROM V_DIFF_ELEMENT_PEDAGOGIQUE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_PEDAGOGIQUE
            ( id, ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FA,TAUX_FC,TAUX_FI,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PEDAGOGIQUE;



  PROCEDURE MAJ_ELEMENT_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_DISCIPLINE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_DISCIPLINE.* FROM V_DIFF_ELEMENT_DISCIPLINE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_DISCIPLINE
            ( id, DISCIPLINE_ID,ELEMENT_PEDAGOGIQUE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_DISCIPLINE_ID_SEQ.NEXTVAL), diff_row.DISCIPLINE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_DISCIPLINE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_DISCIPLINE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_DISCIPLINE;



  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_EFFECTIFS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_EFFECTIFS.* FROM V_DIFF_EFFECTIFS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.EFFECTIFS
            ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,FA,FC,FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,EFFECTIFS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.FA,diff_row.FC,diff_row.FI, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.EFFECTIFS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          UPDATE OSE.EFFECTIFS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_EFFECTIFS;



  PROCEDURE MAJ_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DISCIPLINE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DISCIPLINE.* FROM V_DIFF_DISCIPLINE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.DISCIPLINE
            ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,DISCIPLINE_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.DISCIPLINE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.DISCIPLINE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_DISCIPLINE;



  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CORPS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CORPS.* FROM V_DIFF_CORPS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.CORPS
            ( id, LIBELLE_COURT,LIBELLE_LONG,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,CORPS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.CORPS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.CORPS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_CORPS;



  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CHEMIN_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CHEMIN_PEDAGOGIQUE.* FROM V_DIFF_CHEMIN_PEDAGOGIQUE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.CHEMIN_PEDAGOGIQUE
            ( id, ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,ORDRE,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.ORDRE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_CHEMIN_PEDAGOGIQUE;



  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION_RECHERCHE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION_RECHERCHE.* FROM V_DIFF_AFFECTATION_RECHERCHE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.AFFECTATION_RECHERCHE
            ( id, INTERVENANT_ID,STRUCTURE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,AFFECTATION_RECHERCHE_ID_SEQ.NEXTVAL), diff_row.INTERVENANT_ID,diff_row.STRUCTURE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION_RECHERCHE;



  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_STRUCTURE.* FROM V_DIFF_ADRESSE_STRUCTURE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ADRESSE_STRUCTURE
            ( id, CODE_POSTAL,LOCALITE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,STRUCTURE_ID,TELEPHONE,VALIDITE_DEBUT,VALIDITE_FIN,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ADRESSE_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.LOCALITE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.STRUCTURE_ID,diff_row.TELEPHONE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN,diff_row.VILLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_STRUCTURE;



  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_INTERVENANT.* FROM V_DIFF_ADRESSE_INTERVENANT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ADRESSE_INTERVENANT
            ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,TEL_DOMICILE,VALIDITE_DEBUT,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.TEL_DOMICILE,diff_row.VALIDITE_DEBUT,diff_row.VILLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_INTERVENANT;

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/

---------------------------
--Nouveau VIEW
--V_FORMULE_VOLUME_HORAIRE_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VOLUME_HORAIRE_REF" 
 ( "ID", "SERVICE_REFERENTIEL_ID", "INTERVENANT_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ORDRE", "HEURES"
  )  AS 
  SELECT
  vhr.id                      id,
  sr.id                       service_referentiel_id,
  sr.intervenant_id           intervenant_id,
  sr.annee_id                 annee_id,
  vhr.type_volume_horaire_id  type_volume_horaire_id,
  evh.id                      etat_volume_horaire_id,
  evh.ordre                   etat_volume_horaire_ordre,
  vhr.heures                  heures
FROM
  volume_horaire_ref               vhr
  JOIN service_referentiel          sr ON sr.id     = vhr.service_referentiel_id
  JOIN v_volume_horaire_ref_etat  vher ON vher.volume_horaire_ref_id = vhr.id
  JOIN etat_volume_horaire         evh ON evh.id = vher.etat_volume_horaire_id
WHERE
  1 = ose_divers.comprise_entre( vhr.histo_creation, vhr.histo_destruction, ose_formule.get_date_obs )
  AND 1 = ose_divers.comprise_entre( sr.histo_creation,   sr.histo_destruction,   ose_formule.get_date_obs )
  AND vhr.heures <> 0;
---------------------------
--Nouveau VIEW
--V_FORMULE_SERVICE_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_REF" 
 ( "ID", "INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID"
  )  AS 
  SELECT
  sr.id             id,
  sr.intervenant_id intervenant_id,
  sr.annee_id       annee_id,
  sr.structure_id   structure_id
FROM
  service_referentiel sr
  JOIN intervenant i ON i.id = sr.intervenant_id
WHERE
  1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs );
---------------------------
--Modifié MATERIALIZED VIEW
--MV_PERSONNEL
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_PERSONNEL";
CREATE MATERIALIZED VIEW "OSE"."MV_PERSONNEL" ("CIVILITE_ID","NOM_USUEL","PRENOM","NOM_PATRONYMIQUE","DATE_NAISSANCE","EMAIL","Z_STRUCTURE_ID","SOURCE_ID","SOURCE_CODE","VALIDITE_DEBUT","VALIDITE_FIN") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH aff_ifs (no_dossier_pers, debut, fin) AS (

  SELECT
    ifs.no_dossier_pers,
    ifs.DT_DEB_EXERC_RESP debut,
    ifs.DT_FIN_EXERC_RESP fin
  FROM
    individu_fonct_struct@harpprod ifs
    
  UNION

  SELECT
    aff.no_dossier_pers,
    aff.d_deb_affectation         debut,
    aff.d_fin_affectation         fin
  FROM
    affectation@harpprod aff
  )
  SELECT
  ose_import.get_civilite_id(CASE i.c_civilite 
    WHEN 'M.' THEN 'M.' ELSE 'Mme'
  END)                                     civilite_id,
  initcap(i.nom_usuel)                     nom_usuel,
  initcap(i.prenom)                        prenom,
  initcap(i.nom_patronymique)              nom_patronymique,
  i.d_naissance                            date_naissance,
  im.no_e_mail                             email,
  ids.c_structure                          z_structure_id,
  ose_import.get_source_id('Harpege')      source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999')) source_code,
  MIN(ai.debut)                            validite_debut,
  MAX(ai.fin)                              validite_fin
FROM
  individu@harpprod i
  JOIN aff_ifs ai ON ai.no_dossier_pers = i.no_individu
  JOIN MV_HARP_IND_DER_STRUCT ids ON ids.no_individu = i.no_individu
  JOIN individu_e_mail@harpprod    im ON (im.no_individu = i.no_individu)
WHERE
  OSE_IMPORT.GET_DATE_OBS BETWEEN ai.debut AND NVL(ai.fin,OSE_IMPORT.GET_DATE_OBS)
GROUP BY
  i.c_civilite,i.nom_usuel,i.prenom,i.nom_patronymique,i.d_naissance,im.no_e_mail,ids.c_structure,i.no_individu;
  
  
---------------------------
--Modifié VIEW
--V_TBL_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE" 
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETABLISSEMENT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "PERIODE_ID", "TYPE_INTERVENTION_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "COMMENTAIRES", "PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES", "HEURES_REF", "HEURES_NON_PAYEES", "HEURES_SERVICE_STATUTAIRE", "HEURES_SERVICE_DU_MODIFIE", "HETD", "HETD_SOLDE"
  )  AS 
  WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  s.annee_id                        annee_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  s.structure_aff_id                structure_aff_id,
  s.structure_ens_id                structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  
  vh.heures                         heures,
  0                                 heures_non_payees,
  0                                 heures_ref,
  frvh.service_assure               hetd,
  fr.heures_solde                   hetd_solde,
  null                              commentaires

FROM
  formule_resultat_vh                frvh
  JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN service                          s ON s.id = vh.service_id AND s.annee_id = fr.annee_id AND s.intervenant_id = fr.intervenant_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION

SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  s.annee_id                        annee_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  vhe.etat_volume_horaire_id        etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  s.structure_aff_id                structure_aff_id,
  s.structure_ens_id                structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  
  vh.heures                         heures,
  1                                 heures_non_payees,
  0                                 heures_ref,
  0                                 hetd,
  fr.heures_solde                   hetd_solde,
  null                              commentaires
  
FROM
  volume_horaire                  vh
  JOIN service                     s ON s.id = vh.service_id
  JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
  JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.annee_id = s.annee_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
WHERE
  vh.motif_non_paiement_id IS NOT NULL
  AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION

SELECT
  'vh_ref_' || vhr.id               id,
  sr.id                             service_id,
  sr.intervenant_id                 intervenant_id,
  sr.annee_id                       annee_id,
  fr.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  sr.structure_id                   structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  
  0                                 heures,
  0                                 heures_non_payees,
  vhr.heures                        heures_ref,
  frvr.service_assure               hetd,
  fr.heures_solde                   hetd_solde,
  sr.commentaires                   commentaires
  
FROM
  formule_resultat_vh_ref       frvr
  JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
  JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id
  JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.annee_id = fr.annee_id AND 1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction )
)
SELECT
  t.id                            id,
  t.service_id                    service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,  
  t.annee_id                      annee_id,
  t.type_volume_horaire_id        type_volume_horaire_id,
  t.etat_volume_horaire_id        etat_volume_horaire_id,
  etab.id                         etablissement_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, etp.niveau ) niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,
  t.periode_id                    periode_id,
  t.type_intervention_id          type_intervention_id,
  
  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  gtf.libelle_court               groupe_type_formation_libelle,
  tf.libelle_court                type_formation_libelle,
  etp.niveau                      etape_niveau,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  null                            commentaires,
  p.libelle_court                 periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,
  
  t.heures                        heures,
  t.heures_ref                    heures_ref,
  t.heures_non_payees             heures_non_payees,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  t.hetd                          hetd,
  t.hetd_solde                    hetd_solde

FROM
  t
  JOIN intervenant                        i ON i.id    = t.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant                si ON si.id   = i.statut_id            
  JOIN type_intervenant                  ti ON ti.id   = si.type_intervenant_id 
  JOIN etablissement                   etab ON etab.id = t.etablissement_id
  LEFT JOIN structure                  saff ON saff.id = NVL(t.structure_aff_id, i.structure_id) AND ti.code = 'P'
  LEFT JOIN structure                  sens ON sens.id = t.structure_ens_id
  LEFT JOIN element_pedagogique          ep ON ep.id   = t.element_pedagogique_id
  LEFT JOIN periode                       p ON p.id    = t.periode_id
  LEFT JOIN source                      src ON src.id  = ep.source_id or src.code = 'OSE'
  LEFT JOIN etape                       etp ON etp.id  = ep.etape_id
  LEFT JOIN type_formation               tf ON tf.id   = etp.type_formation_id AND ose_divers.comprise_entre( tf.histo_creation, tf.histo_destruction ) = 1
  LEFT JOIN groupe_type_formation       gtf ON gtf.id  = tf.groupe_id AND ose_divers.comprise_entre( gtf.histo_creation, gtf.histo_destruction ) = 1
  LEFT JOIN v_formule_service_modifie   fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = t.annee_id
  LEFT JOIN v_formule_service            fs ON fs.id   = t.service_id;
  

---------------------------
--Nouveau TRIGGER
--WF_TRG_VH_REF_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VH_REF_VALIDATION_S"
  AFTER INSERT OR DELETE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_VH_REF_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VH_REF_VALIDATION"
  AFTER INSERT OR DELETE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenant_id NUMERIC;
  validation_id NUMERIC;
BEGIN
  validation_id := CASE WHEN deleting THEN :OLD.validation_id ELSE :NEW.validation_id END;
  SELECT V.INTERVENANT_ID INTO intervenant_id FROM validation v WHERE id = validation_id;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_PJ_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_PJ_S"
  AFTER INSERT OR DELETE ON "OSE"."PIECE_JOINTE_FICHIER"
  BEGIN
  --DBMS_OUTPUT.put_line ('WF_TRG_PJ_FICHIER_S ');
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_PJ
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_PJ"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTEUR_ID ON "OSE"."PIECE_JOINTE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenant_id NUMERIC;
  dossierId NUMERIC;
BEGIN
  dossierId := CASE WHEN deleting THEN :OLD.dossier_id ELSE :NEW.dossier_id END;
  SELECT ie.ID INTO intervenant_id FROM intervenant_exterieur ie WHERE ie.dossier_id = dossierId;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
---------------------------
--Nouveau TRIGGER
--VOLUME_HORAIRE_REF_DEL_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_REF_DEL_CK"
  BEFORE DELETE ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  DECLARE has_validation INTEGER;
  pragma autonomous_transaction;
  BEGIN
    SELECT COUNT(*)
    INTO has_validation
    FROM VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v
    ON v.id                    = VVH.VALIDATION_ID
    WHERE V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_REF_ID  = :OLD.ID;
    IF 0                      <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de supprimer des heures référentiel déjà validées.');
    END IF;
  END;
/
---------------------------
--Nouveau TRIGGER
--VOLUME_HORAIRE_REF_COMPAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_REF_COMPAT"
  AFTER INSERT OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  DECLARE 
  total_heures NUMERIC;  
  PRAGMA AUTONOMOUS_TRANSACTION;
BEGIN
  
  SELECT SUM(heures) INTO total_heures
  FROM volume_horaire_ref 
  WHERE
    service_referentiel_id = :NEW.service_referentiel_id
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE(histo_creation, histo_destruction);
    
  UPDATE service_referentiel SET heures = total_heures WHERE id = :NEW.service_referentiel_id AND 1 = OSE_DIVERS.COMPRISE_ENTRE(histo_creation, histo_destruction);
  
END;
/
---------------------------
--Nouveau TRIGGER
--VOLUME_HORAIRE_REF_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_REF_CK"
  BEFORE UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  DECLARE
    has_validation NUMERIC;
  BEGIN  
    
  SELECT 
    COUNT(*)
  INTO
    has_validation
  FROM
    VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_REF_ID  = :NEW.ID;
    
  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier des heures référentiel déjà validées.');
  END IF;
END;
/

---------------------------
--Modifié PACKAGE BODY
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_FORMULE" AS

  v_date_obs DATE;



  FUNCTION GET_DATE_OBS RETURN DATE AS
  BEGIN
    RETURN COALESCE( v_date_obs, SYSDATE );
  END;

  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
  BEGIN
    v_date_obs := DATE_OBS;
    RETURN v_date_obs;
  END;


  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_resultat_maj frm USING dual ON (
          frm.INTERVENANT_ID                = DEMANDE_CALCUL.INTERVENANT_ID
      AND frm.ANNEE_ID                      = DEMANDE_CALCUL.ANNEE_ID
    )
    WHEN NOT MATCHED THEN INSERT ( 
      ID,
      INTERVENANT_ID, 
      ANNEE_ID
    ) VALUES (
      FORMULE_RESULTAT_MAJ_ID_SEQ.NEXTVAL,
      DEMANDE_CALCUL.INTERVENANT_ID, 
      DEMANDE_CALCUL.ANNEE_ID
    );
  END;



  PROCEDURE CALCULER_TOUT IS
    a_id NUMERIC;
  BEGIN
    a_id := OSE_PARAMETRE.GET_ANNEE;
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id 
      FROM 
        service s
        JOIN intervenant i ON i.id = s.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs )
      WHERE
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
        AND s.annee_id = a_id
        
      UNION
      
      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs )
      WHERE
        1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
        AND sr.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id, a_id );
    END LOOP;
  END;


  PROCEDURE CALCULER_SUR_DEMANDE IS
  BEGIN
    FOR mp IN (SELECT DISTINCT intervenant_id, annee_id FROM formule_resultat_maj)
    LOOP
      CALCULER( mp.intervenant_id, mp.annee_id );
    END LOOP;
    DELETE FROM formule_resultat_maj;
  END;


  FUNCTION NOUVEAU_RESULTAT RETURN formule_resultat%rowtype IS
    resultat formule_resultat%rowtype;
  BEGIN
    resultat.id                       := NULL;
    resultat.intervenant_id           := NULL;
    resultat.annee_id                 := NULL;
    resultat.type_volume_horaire_id   := NULL;
    resultat.etat_volume_horaire_id   := NULL;
    resultat.service_du               := 0;
    resultat.enseignements            := 0;
    resultat.service                  := 0;
    resultat.referentiel              := 0;
    resultat.service_assure           := 0;
    resultat.heures_solde             := 0;
    resultat.heures_compl_fi          := 0;
    resultat.heures_compl_fa          := 0;
    resultat.heures_compl_fc          := 0;
    resultat.heures_compl_fc_majorees := 0;
    resultat.heures_compl_referentiel := 0;
    resultat.heures_compl_total       := 0;
    resultat.sous_service             := 0;
    resultat.a_payer                  := 0;
    RETURN resultat;
  END;


  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat tfr USING dual ON (
    
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.annee_id               = fr.annee_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id
      
    ) WHEN MATCHED THEN UPDATE SET
    
      service_du                     = fr.service_du,
      enseignements                  = fr.enseignements,
      service                        = fr.service,
      referentiel                    = fr.referentiel,
      service_assure                 = fr.service_assure,
      heures_solde                   = fr.heures_solde,
      heures_compl_fi                = fr.heures_compl_fi,
      heures_compl_fa                = fr.heures_compl_fa,
      heures_compl_fc                = fr.heures_compl_fc,
      heures_compl_fc_majorees       = fr.heures_compl_fc_majorees,
      heures_compl_referentiel       = fr.heures_compl_referentiel,
      heures_compl_total             = fr.heures_compl_total,
      sous_service                   = fr.sous_service,
      a_payer                        = fr.a_payer,
      to_delete                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      ETAT_VOLUME_HORAIRE_ID,
      SERVICE_DU,
      SERVICE,
      ENSEIGNEMENTS,
      REFERENTIEL,
      SERVICE_ASSURE,
      HEURES_SOLDE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_MAJOREES,
      HEURES_COMPL_REFERENTIEL,
      HEURES_COMPL_TOTAL,
      SOUS_SERVICE,
      A_PAYER,
      TO_DELETE
      
    ) VALUES (
    
      FORMULE_RESULTAT_ID_SEQ.NEXTVAL,
      fr.intervenant_id,
      fr.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      fr.service_du,
      fr.service,
      fr.enseignements,
      fr.referentiel,
      fr.service_assure,
      fr.heures_solde,
      fr.heures_compl_fi,
      fr.heures_compl_fa,
      fr.heures_compl_fc,
      fr.heures_compl_fc_majorees,
      fr.heures_compl_referentiel,
      fr.heures_compl_total,
      fr.sous_service,
      fr.a_payer,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat tfr WHERE
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.annee_id               = fr.annee_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id;
    RETURN id;
  END;



  FUNCTION NOUVEAU_RESULTAT_SERVICE RETURN formule_resultat_service%rowtype IS
    fs formule_resultat_service%rowtype;
  BEGIN
    fs.id                       := NULL;
    fs.formule_resultat_id      := NULL;
    fs.service_id               := NULL;
    fs.service_assure           := 0;
    fs.heures_service           := 0;
    fs.heures_compl_fi          := 0;
    fs.heures_compl_fa          := 0;
    fs.heures_compl_fc          := 0;
    fs.heures_compl_fc_majorees := 0;
    RETURN fs;
  END;
  


  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service tfs USING dual ON (
    
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_assure                 = fs.service_assure,
      heures_service                 = fs.heures_service,
      heures_compl_fi                = fs.heures_compl_fi,
      heures_compl_fa                = fs.heures_compl_fa,
      heures_compl_fc                = fs.heures_compl_fc,
      heures_compl_fc_majorees       = fs.heures_compl_fc_majorees,
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_MAJOREES,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fs.formule_resultat_id,
      fs.service_id,
      fs.service_assure,
      fs.heures_service,
      fs.heures_compl_fi,
      fs.heures_compl_fa,
      fs.heures_compl_fc,
      fs.heures_compl_fc_majorees,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_service tfs WHERE
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id;
    RETURN id;
  END;
  
  
  FUNCTION NOUVEAU_RESULTAT_VH RETURN formule_resultat_vh%rowtype IS
    fvh formule_resultat_vh%rowtype;
  BEGIN
    fvh.id                        := NULL;
    fvh.formule_resultat_id       := NULL;
    fvh.volume_horaire_id         := NULL;
    fvh.service_assure            := 0;
    fvh.heures_service            := 0;
    fvh.heures_compl_fi           := 0;
    fvh.heures_compl_fa           := 0;
    fvh.heures_compl_fc           := 0;
    fvh.heures_compl_fc_majorees  := 0;
    RETURN fvh;
  END;
  


  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh tfvh USING dual ON (
    
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_assure                 = fvh.service_assure,
      heures_service                 = fvh.heures_service,
      heures_compl_fi                = fvh.heures_compl_fi,
      heures_compl_fa                = fvh.heures_compl_fa,
      heures_compl_fc                = fvh.heures_compl_fc,
      heures_compl_fc_majorees       = fvh.heures_compl_fc_majorees,
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_MAJOREES,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_id,
      fvh.service_assure,
      fvh.heures_service,
      fvh.heures_compl_fi,
      fvh.heures_compl_fa,
      fvh.heures_compl_fc,
      fvh.heures_compl_fc_majorees,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_vh tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id;
    RETURN id;
  END;
  
  
  FUNCTION NOUVEAU_RESULTAT_SERVICE_REF RETURN formule_resultat_service_ref%rowtype IS
    fr formule_resultat_service_ref%rowtype;
  BEGIN
    fr.id                       := NULL;
    fr.formule_resultat_id      := NULL;
    fr.service_referentiel_id   := NULL;
    fr.service_assure           := 0;
    fr.heures_service           := 0;
    fr.heures_compl_referentiel := 0;
    RETURN fr;
  END;
  
  
  
  FUNCTION ENREGISTRER_RESULTAT_SERV_REF( fr formule_resultat_service_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service_ref tfr USING dual ON (

          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id

    ) WHEN MATCHED THEN UPDATE SET

      service_assure                 = fr.service_assure,
      heures_service                 = fr.heures_service,
      heures_compl_referentiel       = fr.heures_compl_referentiel,
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_REFERENTIEL_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_REFERENTIEL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fr.formule_resultat_id,
      fr.service_referentiel_id,
      fr.service_assure,
      fr.heures_service,
      fr.heures_compl_referentiel,
      0

    );

    SELECT id INTO id FROM formule_resultat_service_ref tfr WHERE
          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id;
      
    RETURN id;
  END;
  
  
  FUNCTION NOUVEAU_RESULTAT_VH_REF RETURN formule_resultat_vh_ref%rowtype IS
    fvh formule_resultat_vh_ref%rowtype;
  BEGIN
    fvh.id                       := NULL;
    fvh.formule_resultat_id      := NULL;
    fvh.volume_horaire_ref_id    := NULL;
    fvh.service_assure           := 0;
    fvh.heures_service           := 0;
    fvh.heures_compl_referentiel := 0;
    RETURN fvh;
  END;
  

  FUNCTION ENREGISTRER_RESULTAT_VH_REF( fvh formule_resultat_vh_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh_ref tfvh USING dual ON (
    
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_ref_id      = fvh.volume_horaire_ref_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_assure                 = fvh.service_assure,
      heures_service                 = fvh.heures_service,
      heures_compl_referentiel       = fvh.heures_compl_referentiel,
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_REF_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_REFERENTIEL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_ref_id,
      fvh.service_assure,
      fvh.heures_service,
      fvh.heures_compl_referentiel,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_vh_ref tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_ref_id  = fvh.volume_horaire_ref_id;
    RETURN id;
  END;
  
  
  PROCEDURE POPULATE_INTERVENANT( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_intervenant OUT t_intervenant ) IS
  BEGIN

    SELECT
      structure_id,
      heures_service_statutaire
    INTO
      d_intervenant.structure_id,
      d_intervenant.heures_service_statutaire
    FROM
      v_formule_intervenant fi
    WHERE
      fi.id = POPULATE_INTERVENANT.INTERVENANT_ID;

    SELECT
      NVL( SUM(heures), 0)
    INTO
      d_intervenant.heures_service_modifie
    FROM
      v_formule_service_modifie fsm
    WHERE
      fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID
      AND fsm.annee_id   = POPULATE_INTERVENANT.ANNEE_ID;
  
  EXCEPTION WHEN NO_DATA_FOUND THEN
    d_intervenant.structure_id := null;
    d_intervenant.heures_service_statutaire := null;
  END;
  

  PROCEDURE POPULATE_SERVICE_REF( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_service_ref OUT t_lst_service_ref ) IS
    i PLS_INTEGER;
  BEGIN
    d_service_ref.delete;

    FOR d IN (
      SELECT
        fr.id,
        fr.structure_id
      FROM
        v_formule_service_ref fr
      WHERE
        fr.intervenant_id = POPULATE_SERVICE_REF.INTERVENANT_ID
        AND fr.annee_id   = POPULATE_SERVICE_REF.ANNEE_ID
    ) LOOP
      d_service_ref( d.id ).id           := d.id;
      d_service_ref( d.id ).structure_id := d.structure_id;
    END LOOP;
  END;


  PROCEDURE POPULATE_SERVICE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_service OUT t_lst_service ) IS
  BEGIN
    d_service.delete;

    FOR d IN (
      SELECT
        id,
        taux_fi,
        taux_fa,
        taux_fc,
        structure_aff_id,
        structure_ens_id,
        ponderation_service_du,
        ponderation_service_compl
      FROM
        v_formule_service fs
      WHERE
        fs.intervenant_id = POPULATE_SERVICE.INTERVENANT_ID
        AND fs.annee_id   = POPULATE_SERVICE.ANNEE_ID
    ) LOOP
      d_service( d.id ).id                        := d.id;
      d_service( d.id ).taux_fi                   := d.taux_fi;
      d_service( d.id ).taux_fa                   := d.taux_fa;
      d_service( d.id ).taux_fc                   := d.taux_fc;
      d_service( d.id ).ponderation_service_du    := d.ponderation_service_du;
      d_service( d.id ).ponderation_service_compl := d.ponderation_service_compl;
      d_service( d.id ).structure_aff_id          := d.structure_aff_id;
      d_service( d.id ).structure_ens_id          := d.structure_ens_id;
    END LOOP;
  END;

  PROCEDURE POPULATE_VOLUME_HORAIRE_REF( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_volume_horaire_ref OUT t_lst_volume_horaire_ref ) IS
  BEGIN
    d_volume_horaire_ref.delete;

    FOR d IN (
      SELECT
        id,
        service_referentiel_id,
        heures,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire_ref fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE_REF.INTERVENANT_ID
        AND fvh.annee_id                  = POPULATE_VOLUME_HORAIRE_REF.ANNEE_ID
    ) LOOP
      d_volume_horaire_ref( d.id ).id                        := d.id;
      d_volume_horaire_ref( d.id ).service_referentiel_id    := d.service_referentiel_id;
      d_volume_horaire_ref( d.id ).heures                    := d.heures;
      d_volume_horaire_ref( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire_ref( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire_ref( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;

  PROCEDURE POPULATE_VOLUME_HORAIRE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_volume_horaire OUT t_lst_volume_horaire ) IS
  BEGIN
    d_volume_horaire.delete;

    FOR d IN (
      SELECT
        id,
        service_id,
        heures,
        taux_service_du,
        taux_service_compl,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE.INTERVENANT_ID
        AND fvh.annee_id                  = POPULATE_VOLUME_HORAIRE.ANNEE_ID
    ) LOOP
      d_volume_horaire( d.id ).id                        := d.id;
      d_volume_horaire( d.id ).service_id                := d.service_id;
      d_volume_horaire( d.id ).heures                    := d.heures;
      d_volume_horaire( d.id ).taux_service_du           := d.taux_service_du;
      d_volume_horaire( d.id ).taux_service_compl        := d.taux_service_compl;
      d_volume_horaire( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
    TYPE t_ordres IS TABLE OF NUMERIC INDEX BY PLS_INTEGER;
  
    ordres_found t_ordres;
    ordres_exists t_ordres;
    type_volume_horaire_id PLS_INTEGER;
    etat_volume_horaire_ordre PLS_INTEGER;
    id PLS_INTEGER;
  BEGIN
    d_type_etat_vh.delete;

    -- récupération des ID et ordres de volumes horaires
    FOR evh IN (
      SELECT   id, ordre
      FROM     etat_volume_horaire evh
      WHERE    OSE_DIVERS.COMPRISE_ENTRE( evh.histo_creation, evh.histo_destruction ) = 1
      ORDER BY ordre
    ) LOOP
      ordres_exists( evh.ordre ) := evh.id;
    END LOOP;

    -- récupération des ordres maximum par type d'intervention
    id := d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF NOT ordres_found.EXISTS(d_volume_horaire(id).type_volume_horaire_id) THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      ELSIF ordres_found( d_volume_horaire(id).type_volume_horaire_id ) < d_volume_horaire(id).etat_volume_horaire_ordre THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      END IF;
      id := d_volume_horaire.NEXT(id);
    END LOOP;
    
    -- peuplement des t_lst_type_etat_vh
    type_volume_horaire_id := ordres_found.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_ordre := ordres_exists.FIRST;
      LOOP EXIT WHEN etat_volume_horaire_ordre IS NULL;
        IF etat_volume_horaire_ordre <= ordres_found(type_volume_horaire_id) THEN
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).type_volume_horaire_id := type_volume_horaire_id;
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).etat_volume_horaire_id := ordres_exists( etat_volume_horaire_ordre );
        END IF;
        etat_volume_horaire_ordre := ordres_exists.NEXT(etat_volume_horaire_ordre);
      END LOOP;
      
      type_volume_horaire_id := ordres_found.NEXT(type_volume_horaire_id);
    END LOOP;
    
  END;


  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
  BEGIN
    POPULATE_INTERVENANT    ( INTERVENANT_ID, ANNEE_ID, d_intervenant );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
      POPULATE_SERVICE_REF        ( INTERVENANT_ID, ANNEE_ID, d_service_ref         );
      POPULATE_SERVICE            ( INTERVENANT_ID, ANNEE_ID, d_service             );
      POPULATE_VOLUME_HORAIRE_REF ( INTERVENANT_ID, ANNEE_ID, d_volume_horaire_ref  );
      POPULATE_VOLUME_HORAIRE     ( INTERVENANT_ID, ANNEE_ID, d_volume_horaire      );
      POPULATE_TYPE_ETAT_VH       ( d_volume_horaire, d_type_etat_vh );
    END IF;
  END;


  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
    id PLS_INTEGER;
    found BOOLEAN;
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    -- détection de suppression des lignes de résultat obsolètes
    UPDATE formule_resultat SET TO_DELETE = 1 WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID;
    UPDATE FORMULE_RESULTAT_SERVICE_REF SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    UPDATE FORMULE_RESULTAT_SERVICE     SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    UPDATE FORMULE_RESULTAT_VH_REF      SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    UPDATE FORMULE_RESULTAT_VH          SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);

    POPULATE( INTERVENANT_ID, ANNEE_ID );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!

      -- lancement du calcul sur les nouvelles lignes ou sur les lignes existantes
      id := d_type_etat_vh.FIRST;
      LOOP EXIT WHEN id IS NULL;
        -- délégation du calcul à la formule choisie (à des fins de paramétrage)
        EXECUTE IMMEDIATE 
          'BEGIN ' || package_name || '.' || function_name || '( :1, :2, :3, :4 ); END;'
        USING
          INTERVENANT_ID, ANNEE_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id;
  
        id := d_type_etat_vh.NEXT(id);
      END LOOP;
    END IF;

    DELETE FROM FORMULE_RESULTAT_SERVICE_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM FORMULE_RESULTAT_VH_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM formule_resultat WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID;

  END;

END OSE_FORMULE;
/


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END; 
/