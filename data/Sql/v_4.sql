-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 

/

update statut_intervenant set tem_atv = 1 WHERE source_code IN (

'ETUD_HORS_UCBN',
'ETUD_UCBN',
'RETR_HORS_UCBN'

);


--select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_etablissement',
  'L''université de Caen',
  'Contrat : établissement',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_etablissement_represente',
  'représentée par son Président, Pierre DENISE',
  'Contrat : représentant',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_civilite_president',
  'le Président',
  'Contrat : civilité du président (avec article)',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_lieu_signature',
  'Caen',
  'LContrat : lieu de signature',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);



INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'PREVU'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'P'), -- P ou E
    'affectation'
  );
  
  INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'PREVU'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'E'), -- P ou E
    'enseignement'
  );
  
  INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'REALISE'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'P'), -- P ou E
    'enseignement'
  );
  
  INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'REALISE'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'E'), -- P ou E
    'enseignement'
  );
  


Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('180','191','1','0','1','39','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('180','194','0','1','0','40','1','1');
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('184','182','0','0','0','1','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('185','184','0','0','1','2','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('186','182','0','1','0','3','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('187','185','0','0','0','4','0',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('187','186','0','0','0','5','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('187','183','1','0','1','6','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('188','183','1','0','1','7','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('189','187','1','0','1','11','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('189','185','0','0','0','12','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('189','186','0','0','0','10','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('190','189','0','0','1','13','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('191','190','1','0','0','14','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('192','189','0','0','0','15','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('196','192','0','0','1','16','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('179','180','1','0','1','22','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('193','192','1','0','1','18','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('194','192','1','0','1','19','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('180','196','0','0','0','20','1','1');
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('180','193','0','1','0','21','1','1');
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('192','190','0','1','0','23','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('192','191','0','0','1','24','1',null);
Insert into WF_ETAPE_DEP (ETAPE_SUIV_ID,ETAPE_PREC_ID,LOCALE,INTEGRALE,PARTIELLE,ID,ACTIVE,TYPE_INTERVENANT_ID) values ('191','189','1','0','1','26','1',null);




Insert into PACKAGE_DEPS (ID,P1,P2) values ('1','OSE_AGREMENT','OSE_FORMULE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('2','OSE_WORKFLOW','OSE_AGREMENT');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('3','OSE_WORKFLOW','OSE_CLOTURE_REALISE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('4','OSE_WORKFLOW','OSE_CONTRAT');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('5','OSE_WORKFLOW','OSE_DOSSIER');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('6','OSE_WORKFLOW','OSE_PAIEMENT');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('7','OSE_WORKFLOW','OSE_PIECE_JOINTE_FOURNIE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('8','OSE_WORKFLOW','OSE_PIECE_JOINTE_DEMANDE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('11','OSE_WORKFLOW','OSE_SERVICE_SAISIE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('12','OSE_PIECE_JOINTE','OSE_PIECE_JOINTE_FOURNIE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('13','OSE_PIECE_JOINTE','OSE_PIECE_JOINTE_DEMANDE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('14','OSE_PAIEMENT','OSE_FORMULE');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('15','OSE_WORKFLOW','OSE_VALIDATION_ENSEIGNEMENT');
Insert into PACKAGE_DEPS (ID,P1,P2) values ('16','OSE_WORKFLOW','OSE_VALIDATION_REFERENTIEL');



UPDATE PERSONNEL SET CODE = SOURCE_CODE;
UPDATE PERSONNEL SET SUPANN_EMP_ID = SOURCE_CODE;

update formule_resultat set
  type_intervenant_code = (SELECT ti.code FROM type_intervenant ti JOIN statut_intervenant si ON si.type_intervenant_id = ti.id JOIN intervenant i ON i.statut_id = si.id WHERE i.id = formule_resultat.intervenant_id)
;




-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/