-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/

---------------------------
--Modifié TABLE
--VOLUME_HORAIRE
---------------------------
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--SERVICE_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--PERIODE
---------------------------
ALTER TABLE "OSE"."PERIODE" DROP ("TYPE_INTERVENANT_ID");
ALTER TABLE "OSE"."PERIODE" DROP CONSTRAINT "PERIODE_TYPE_INTERVENANT_FK";

---------------------------
--Modifié TABLE
--SERVICE
---------------------------
ALTER TABLE "OSE"."SERVICE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."SERVICE" DROP ("VALIDITE_FIN");

---------------------------
--Nouveau TABLE
--NOTIFICATION_INDICATEUR
---------------------------
  CREATE TABLE "OSE"."NOTIFICATION_INDICATEUR" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"INDICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"PERSONNEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"STRUCTURE_ID" NUMBER(*,0),
	"FREQUENCE" NUMBER(*,0) NOT NULL ENABLE,
	"DATE_ABONNEMENT" DATE NOT NULL ENABLE,
	"DATE_DERN_NOTIF" DATE,
	CONSTRAINT "NOTIF_INDICATEUR_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "NOTIF_INDICATEUR_IFK" FOREIGN KEY ("INDICATEUR_ID")
	 REFERENCES "OSE"."INDICATEUR" ("ID") ENABLE,
	CONSTRAINT "NOTIF_INDICATEUR_SFK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE,
	CONSTRAINT "NOTIF_INDICATEUR_UFK" FOREIGN KEY ("PERSONNEL_ID")
	 REFERENCES "OSE"."PERSONNEL" ("ID") ENABLE
   );
---------------------------
--Modifié TABLE
--MODIFICATION_SERVICE_DU
---------------------------
ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" DROP ("VALIDITE_FIN");

---------------------------
--Modifié VIEW
--V_TBL_SERVICE_RESUME_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_RESUME_REF" 
 ( "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "HEURES_REFERENTIEL"
  )  AS 
  SELECT
  fr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  -1                              service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  i.structure_id                  structure_aff_id,
  
  sr.structure_id                 structure_ens_id,
  -1                              niveau_formation_id,
  -1                              etape_id,
  -1                              element_pedagogique_id,

  sr.heures                       heures_referentiel
FROM
  formule_resultat                    fr
  JOIN intervenant                     i ON i.id    = fr.intervenant_id
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN service_referentiel            sr ON sr.INTERVENANT_ID = FR.INTERVENANT_ID AND sr.ANNEE_ID = FR.ANNEE_ID AND 1 = ose_divers.comprise_entre(sr.histo_creation, sr.histo_destruction);
---------------------------
--Modifié VIEW
--V_TBL_SERVICE_RESUME
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_RESUME" 
 ( "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_TYPE_CODE", "SERVICE_DU", "HEURES_SOLDE", "HEURES_COMPL"
  )  AS 
  SELECT
  fr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  s.id                            service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE s.structure_aff_id END structure_aff_id,
  
  s.structure_ens_id              structure_ens_id,
  enf.niveau_formation_id         niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  ti.code                         intervenant_type_code,
  fr.service_du                   service_du,
  fr.heures_solde                 heures_solde,
  fr.heures_compl_total           heures_compl
FROM
  formule_resultat                    fr
  JOIN intervenant                     i ON i.id    = fr.intervenant_id
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  LEFT JOIN service                    s ON s.intervenant_id = fr.intervenant_id AND s.annee_id = fr.annee_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
  LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
  LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
  LEFT JOIN v_etape_niveau_formation enf ON enf.etape_id = etp.id;
---------------------------
--Modifié VIEW
--V_TBL_SERVICE_EXPORT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_EXPORT" 
 ( "SERVICE_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "INTERVENANT_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "COMMENTAIRES", "ELEMENT_PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES_SERVICE", "HEURES_REELLES", "HEURES_SERVICE_STATUTAIRE", "HEURES_SERVICE_DU_MODIFIE", "HEURES_ASSUREES", "HEURES_SOLDE", "HEURES_NON_PAYEES", "HEURES_REFERENTIEL"
  )  AS 
  SELECT
  s.id                            service_id,
  fr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE saff.id END                         structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  enf.niveau_formation_id         niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  null                            commentaires,
  p.libelle_court                 element_periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,

  fr.service                      heures_service,
  fr.service + fr.referentiel     heures_reelles,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  fr.service_assure               heures_assurees,
  fr.heures_solde                 heures_solde,
  
  (SELECT
      COALESCE( SUM( vh.heures * CASE WHEN vh.motif_non_paiement_id IS NULL THEN 0 ELSE 1 END ), 0)
    FROM
      volume_horaire vh
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
    WHERE
      ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction ) = 1
      AND vh.service_id = s.id
      AND NVL(vh.type_volume_horaire_id,1) = fr.type_volume_horaire_id 
      AND NVL(vhe.etat_volume_horaire_id,1) >= fr.etat_volume_horaire_id) heures_non_payees,
  0                               heures_referentiel  

FROM
  service s
  JOIN intervenant                     i ON i.id    = s.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN structure                    sens ON sens.id = s.structure_ens_id
  JOIN etablissement                etab ON etab.id = s.etablissement_id
  JOIN formule_resultat               fr ON fr.intervenant_id = i.id
  LEFT JOIN structure               saff ON saff.id = s.structure_aff_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
  LEFT JOIN periode                    p ON p.id    = ep.periode_id
  LEFT JOIN source                   src ON src.id  = ep.source_id
  LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
  LEFT JOIN v_etape_niveau_formation enf ON enf.etape_id = etp.id
  LEFT JOIN v_formule_service_modifie fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = fr.annee_id
  LEFT JOIN v_formule_service         fs ON fs.id = s.id
WHERE
  ose_divers.comprise_entre( s.histo_creation, s.histo_destruction ) = 1

UNION

SELECT
  -1                              service_id,
  sr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE saff.id END structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  -1                              niveau_formation_id,
  -1                              etape_id,
  -1                              element_pedagogique_id,


  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  null                            etablissement_libelle,
  null                            etape_code,
  null                            etape_libelle,
  fonc.code                       element_code,
  fonc.libelle_court              element_libelle,
  sr.commentaires                 commentaires,
  null                            element_periode_libelle,
  null                            element_ponderation_compl,
  src.libelle                     element_source_libelle,

  fr.service                      heures_service,
  fr.service + fr.referentiel     heures_reelles,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  fr.service_assure               heures_assurees,
  fr.heures_solde                 heures_solde,
  0                               heures_non_payees,
  sr.heures                       heures_referentiel  

FROM
  service_referentiel                 sr
  JOIN fonction_referentiel         fonc ON fonc.id = sr.fonction_id
  JOIN intervenant                     i ON i.id    = sr.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN structure                    sens ON sens.id = sr.structure_id
  JOIN formule_resultat               fr ON fr.intervenant_id = i.id
  JOIN source                        src ON src.code = 'OSE'
  LEFT JOIN structure               saff ON saff.id = i.structure_id AND ti.code = 'P'
  LEFT JOIN v_formule_service_modifie fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = fr.annee_id
WHERE
  ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction ) = 1;
---------------------------
--Modifié VIEW
--V_PJ_HEURES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_PJ_HEURES" 
 ( "NOM_USUEL", "PRENOM", "INTERVENANT_ID", "SOURCE_CODE", "ANNEE_ID", "CATEG", "TOTAL_HEURES"
  )  AS 
  SELECT i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, s.annee_id, 'service' categ, sum(vh.HEURES) as total_heures
  from INTERVENANT i 
  join SERVICE s on s.INTERVENANT_ID = i.id                                   and s.HISTO_DESTRUCTEUR_ID is null
  join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id                         and vh.HISTO_DESTRUCTEUR_ID is null
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID AND (tvh.code = 'PREVU')
  join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ep.HISTO_DESTRUCTEUR_ID is null
  join ETAPE e on ep.ETAPE_ID = e.id and e.HISTO_DESTRUCTEUR_ID is null
  where i.HISTO_DESTRUCTEUR_ID is null
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.annee_id, 'service'
UNION
  select i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, s.annee_id, 'referentiel' categ, sum(s.HEURES) as total_heures
  from INTERVENANT i 
  join SERVICE_REFERENTIEL s on s.INTERVENANT_ID = i.id and s.HISTO_DESTRUCTEUR_ID is null
  where i.HISTO_DESTRUCTEUR_ID is null
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.annee_id, 'referentiel';
---------------------------
--Modifié VIEW
--V_ELEMENT_TYPE_INTERVENTION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_ELEMENT_TYPE_INTERVENTION" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID"
  )  AS 
  SELECT
  ep.id element_pedagogique_id,
  ti.id type_intervention_id
FROM
  element_pedagogique ep
  JOIN structure s_ep ON s_ep.id = ep.structure_id
  JOIN type_intervention ti ON 1=1
  LEFT JOIN TYPE_INTERVENTION_EP ti_ep ON TI_EP.ELEMENT_PEDAGOGIQUE_ID = EP.ID AND TI_EP.TYPE_INTERVENTION_ID = TI.ID AND 1 = ose_divers.comprise_entre( TI_EP.HISTO_CREATION, TI_EP.HISTO_DESTRUCTION )
  LEFT JOIN TYPE_INTERVENTION_STRUCTURE ti_s ON S_EP.STRUCTURE_NIV2_ID = TI_S.STRUCTURE_ID AND TI_S.TYPE_INTERVENTION_ID = TI.ID AND 1 = ose_divers.comprise_entre( TI_S.HISTO_CREATION, TI_S.HISTO_DESTRUCTION )
WHERE
  CASE
    WHEN TI_EP.VISIBLE IS NULL THEN
      CASE
        WHEN TI_S.VISIBLE IS NULL THEN TI.VISIBLE
        ELSE TI_S.VISIBLE
      END
    ELSE TI_EP.VISIBLE
  END = 1
ORDER BY
  TI.ORDRE;
---------------------------
--Nouveau VIEW
--V_TMP_WF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TMP_WF" 
 ( "ID", "SOURCE_CODE", "NB_COMP", "NB_AGREM"
  )  AS 
  WITH 
  composantes_enseign AS (
      -- composantes d'enseignement par intervenant
      SELECT DISTINCT i.ID, i.source_code, s.structure_ens_id
      FROM service s
      INNER JOIN intervenant i ON i.ID = s.intervenant_id AND (i.histo_destructeur_id IS NULL)
      INNER JOIN STRUCTURE comp ON comp.ID = s.structure_ens_id AND (comp.histo_destructeur_id IS NULL)
      WHERE s.histo_destructeur_id IS NULL
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
      AND ta.code = 'CONSEIL_RESTREINT'
  ), 
  v_agrement AS (
    -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
    SELECT DISTINCT i.ID, i.source_code, 
      ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp, 
      ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
    FROM intervenant i 
    WHERE i.histo_destructeur_id IS NULL
  )
  SELECT "ID","SOURCE_CODE","NB_COMP","NB_AGREM"
  FROM v_agrement v
  WHERE v.nb_comp <= nb_agrem;
  --AND v.id = p_intervenant_id;
---------------------------
--Modifié VIEW
--V_WF_INTERVENANT_ETAPE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_WF_INTERVENANT_ETAPE" 
 ( "ID", "NOM_USUEL", "PRENOM", "SOURCE_CODE", "TYPE", "ORDRE", "LIBELLE", "STRUCTURE_ID", "LIBELLE_COURT", "ATTEIGNABLE", "FRANCHIE", "COURANTE", "DATE_CREATION"
  )  AS 
  select i.id, i.nom_usuel, i.prenom, i.source_code, TI.code type, ie.ordre, e.libelle, s.id structure_id, s.libelle_court, ie.atteignable, ie.franchie, ie.courante, IE.DATE_CREATION
  from wf_intervenant_etape ie 
  inner join intervenant i on i.id = ie.intervenant_id
  inner join wf_etape e on e.id = ie.etape_id
  inner join STATUT_INTERVENANT si on si.id = I.STATUT_ID
  inner join TYPE_INTERVENANT ti on ti.id = SI.TYPE_INTERVENANT_ID
  left join structure s on s.id = IE.STRUCTURE_ID
  order by i.nom_usuel, i.id, ie.ordre asc;
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."NOTIF_INDICATEUR_PK" ON "OSE"."NOTIFICATION_INDICATEUR" ("ID");
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE"
  AFTER DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN FOR p IN
    ( SELECT DISTINCT s.intervenant_id,
      s.annee_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND 1                           = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    ) LOOP OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_MOTIF_MODIFICATION_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      intervenant_id, 
      annee_id
    FROM
      modification_service_du msd
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( msd.histo_creation, msd.histo_destruction )
      AND (msd.motif_id = :NEW.id OR msd.motif_id = :OLD.id)
      
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      service s
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)
      
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
    
  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END;
/
---------------------------
--Nouveau TRIGGER
--F_ELEMENT_MODULATEUR_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--F_CONTRAT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT_S"
  AFTER DELETE OR UPDATE ON "OSE"."CONTRAT"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--F_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  ose_divers.sync_log('f_service_s' );
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_id OR vh.id = :OLD.volume_horaire_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;
END;
/

---------------------------
--Nouveau TRIGGER
--WF_TRG_SERVICE_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  DECLARE
  type_validation_id NUMERIC;
  code VARCHAR2(128);
  intervenant_id NUMERIC;
BEGIN
  type_validation_id := CASE WHEN deleting THEN :OLD.type_validation_id ELSE :NEW.type_validation_id END;
  SELECT code INTO code FROM type_validation WHERE id = type_validation_id;
  IF code = 'SERVICES_PAR_COMP' THEN
    intervenant_id := CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END;
    ose_workflow.add_intervenant_to_update (intervenant_id); 
  END IF;
END;
/
  ALTER TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION" DISABLE;
/
---------------------------
--Nouveau TRIGGER
--F_MODULATEUR_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR_S"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_DOSSIER_S
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_DOSSIER_S" ENABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_INTERV_DOSSIER_S
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_INTERV_DOSSIER_S" ENABLE;
/
---------------------------
--Modifié TRIGGER
--VOLUME_HORAIRE_CK
---------------------------
ALTER TRIGGER "OSE"."VOLUME_HORAIRE_CK" DISABLE;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_SERVICE_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
  ALTER TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION_S" DISABLE;
/
---------------------------
--Nouveau TRIGGER
--F_MOTIF_MODIFICATION_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE_S"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenant_id NUMERIC;
  service_id NUMERIC;
BEGIN
  service_id := CASE WHEN deleting THEN :OLD.service_id ELSE :NEW.service_id END;
  SELECT s.intervenant_id into intervenant_id from service s where id = service_id;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_INTERV_DOSSIER
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_INTERV_DOSSIER" ENABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_CONTRAT_S
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_CONTRAT_S" ENABLE;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_PJ_VALIDATION
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_PJ_VALIDATION" ENABLE;
/
---------------------------
--Nouveau TRIGGER
--F_VALIDATION_VOL_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--F_MODIF_SERVICE_DU_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.annee_id,
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT"
  AFTER UPDATE ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  FOR p IN (
      
    SELECT DISTINCT
      fr.intervenant_id,
      fr.annee_id
    FROM
      formule_resultat fr
    WHERE
      fr.intervenant_id = :NEW.id OR fr.intervenant_id = :OLD.id
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;
  
END;
/
---------------------------
--Nouveau TRIGGER
--F_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--F_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT_S"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_AGREMENT_S
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_AGREMENT_S" ENABLE;
/
---------------------------
--Nouveau TRIGGER
--F_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT_S"
  AFTER UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.annee_id,
      s.intervenant_id
    FROM
      service s
      JOIN element_modulateur em ON 
        em.element_id   = s.element_pedagogique_id 
        AND em.annee_id = s.annee_id 
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction )
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (em.modulateur_id = :OLD.id OR em.modulateur_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_AGREMENT
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_AGREMENT" ENABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_CONTRAT
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_CONTRAT" ENABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_VH_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VH_VALIDATION"
  AFTER INSERT OR DELETE ON "OSE"."VALIDATION_VOL_HORAIRE"
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
--F_TYPE_INTERVENTION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION_S"
  AFTER UPDATE ON "OSE"."TYPE_INTERVENTION"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_PJ_VALIDATION_S
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_PJ_VALIDATION_S" ENABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_VH_VALIDATION_S
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_VH_VALIDATION_S" ENABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_DOSSIER
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_DOSSIER" ENABLE;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;
  ose_divers.sync_log('f_service S.ID=' || NVL(:NEW.id,:OLD.id) );
END;
/
---------------------------
--Nouveau TRIGGER
--F_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE_S"
  AFTER DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_TYPE_INTERVENTION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION"
  AFTER UPDATE ON "OSE"."TYPE_INTERVENTION"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      fr.intervenant_id,
      fr.annee_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION"
  AFTER UPDATE ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN

  FOR p IN ( -- validations de volume horaire

    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      validation_vol_horaire vvh
      JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (vvh.validation_id = :OLD.ID OR vvh.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;

  FOR p IN ( -- validations de contrat

    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      contrat c
      JOIN volume_horaire vh ON vh.contrat_id = c.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (c.validation_id = :OLD.ID OR c.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;

END;
/
---------------------------
--Nouveau TRIGGER
--F_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_S"
  AFTER UPDATE ON "OSE"."VALIDATION"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
  ALTER TRIGGER "OSE"."F_VALIDATION_S" DISABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_DOSSIER_VALIDATION
---------------------------
ALTER TRIGGER "OSE"."WF_TRG_DOSSIER_VALIDATION" ENABLE;
/
---------------------------
--Nouveau TRIGGER
--F_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODIF_SERVICE_DU
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      service s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_id OR s.id = :OLD.service_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  ose_divers.sync_log('f_volume_horaire VH.ID=' || NVL(:NEW.id,:OLD.id) || ', heures=' || :NEW.heures || ', histo=' || :NEW.histo_destruction );
  END LOOP;
END;
/


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

select 'alter trigger "OSE"."' || trigger_name || '" disable;' from ALL_TRIGGERS where trigger_name like 'WF_%';

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END; 
/