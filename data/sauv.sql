INSERT
INTO FONCTION_REFERENTIEL
  (
    ID,
    CODE,
    LIBELLE_LONG,
    LIBELLE_COURT,
    PLAFOND,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  VALUES
  (
    FONCTION_REFERENTIEL_ID_SEQ.nextval,
    'VAE_VAP',
    'Validation des acquis (VAE/VAP)',
    'VAE/VAP',
    9999,
    1,
    1
  );
  
  
  
  CREATE TABLE "OSE"."CONTRAT_FICHIER" (	
   "CONTRAT_ID" NUMBER(*,0) NOT NULL ENABLE, 
   "FICHIER_ID" NUMBER(*,0) NOT NULL ENABLE, 
   CONSTRAINT "CONTRAT_FICHIER_PK" PRIMARY KEY ("CONTRAT_ID", "FICHIER_ID"), 
   CONSTRAINT "CONTRAT_FICHIER_FFK" FOREIGN KEY ("FICHIER_ID") REFERENCES "OSE"."FICHIER" ("ID") ON DELETE CASCADE ENABLE, 
   CONSTRAINT "CONTRAT_FICHIER_cFK" FOREIGN KEY ("CONTRAT_ID") REFERENCES "OSE"."CONTRAT" ("ID") ON DELETE CASCADE ENABLE
 ) ;
 
 
 -- copie dossier.premier_recrutement --> intervenant.premier_recrutement
UPDATE INTERVENANT I
set i.premier_recrutement = (
  select d.premier_recrutement
  from DOSSIER D
  inner join INTERVENANT_EXTERIEUR IE on ie.dossier_id = d.id
  where ie.id = i.id 
);
-- verif
select i.premier_recrutement, d.premier_recrutement
from intervenant i
inner join intervenant_exterieur ie on ie.id = i.id
inner join dossier d on d.id = ie.dossier_id
--where i.source_code = '30688'
;


-- recréation séquence TYPE_AGREMENT_ID_SEQ et insert 2 types agrément
drop SEQUENCE TYPE_AGREMENT_ID_SEQ;
CREATE SEQUENCE TYPE_AGREMENT_ID_SEQ  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
delete from TYPE_AGREMENT;
Insert into TYPE_AGREMENT (ID,CODE,LIBELLE,HISTO_CREATEUR_ID,HISTO_MODIFICATEUR_ID) values 
(type_agrement_id_seq.nextval,'CONSEIL_RESTREINT', 'Conseil Restreint', 1, 1);
Insert into TYPE_AGREMENT (ID,CODE,LIBELLE,HISTO_CREATEUR_ID,HISTO_MODIFICATEUR_ID) values 
(type_agrement_id_seq.nextval,'CONSEIL_ACADEMIQUE','Conseil Académique', 1, 1);
select * from TYPE_AGREMENT;
commit;

--truncate table type_agrement_statut;

----
-- conseil restreint : vacataires, BIATSS
----
INSERT INTO TYPE_AGREMENT_STATUT
  (
    ID,
    TYPE_AGREMENT_ID,
    STATUT_INTERVENANT_ID,
    OBLIGATOIRE,
    SEUIL_HETD,
    PREMIER_RECRUTEMENT,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  select 
    TYPE_AGREMENT_STATUT_id_seq.nextval,
    ta.id TYPE_AGREMENT_ID,
    si.id STATUT_INTERVENANT_ID,
    1 OBLIGATOIRE,
    null SEUIL_HETD,
    1 PREMIER_RECRUTEMENT, -- 1er recrutement
    1 HISTO_CREATEUR_ID,
    1 HISTO_MODIFICATEUR_ID
  from STATUT_INTERVENANT si, type_agrement ta
  where si.source_code in ('BIATSS', 'SALAR_PRIVE', 'SALAR_PUBLIC', 'NON_SALAR', 'RETR_HORS_UCBN', 'ETUD_UCBN', 'ETUD_HORS_UCBN', 'CHARG_ENS_1AN') 
  and ta.code = 'CONSEIL_RESTREINT';
INSERT INTO TYPE_AGREMENT_STATUT
  (
    ID,
    TYPE_AGREMENT_ID,
    STATUT_INTERVENANT_ID,
    OBLIGATOIRE,
    SEUIL_HETD,
    PREMIER_RECRUTEMENT,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  select 
    TYPE_AGREMENT_STATUT_id_seq.nextval,
    ta.id TYPE_AGREMENT_ID,
    si.id STATUT_INTERVENANT_ID,
    1 OBLIGATOIRE,
    null SEUIL_HETD,
    0 PREMIER_RECRUTEMENT, -- 2nd recrutement
    1 HISTO_CREATEUR_ID,
    1 HISTO_MODIFICATEUR_ID
  from STATUT_INTERVENANT si, type_agrement ta
  where si.source_code in ('BIATSS', 'SALAR_PRIVE', 'SALAR_PUBLIC', 'NON_SALAR', 'RETR_HORS_UCBN', 'ETUD_UCBN', 'ETUD_HORS_UCBN', 'CHARG_ENS_1AN') 
  and ta.code = 'CONSEIL_RESTREINT';
  
----
-- conseil academique : vacataires (recrutés pour la 1ère fois depuis septembre 2012) qui sont salariés du public ou du privé, ou non salariés
----
INSERT INTO TYPE_AGREMENT_STATUT
  (
    ID,
    TYPE_AGREMENT_ID,
    STATUT_INTERVENANT_ID,
    OBLIGATOIRE,
    SEUIL_HETD,
    PREMIER_RECRUTEMENT,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  select 
    TYPE_AGREMENT_STATUT_id_seq.nextval,
    ta.id TYPE_AGREMENT_ID,
    si.id STATUT_INTERVENANT_ID,
    1 OBLIGATOIRE,
    null SEUIL_HETD,
    1 PREMIER_RECRUTEMENT,
    1 HISTO_CREATEUR_ID,
    1 HISTO_MODIFICATEUR_ID
  from STATUT_INTERVENANT si, type_agrement ta
  where si.source_code in ('SALAR_PRIVE', 'SALAR_PUBLIC', 'NON_SALAR') 
  and ta.code = 'CONSEIL_ACADEMIQUE';
  
  
  
  
  
  

-- ajouts de pièces justificatives à joindre
INSERT INTO TYPE_PIECE_JOINTE_STATUT
  (
    ID,
    TYPE_PIECE_JOINTE_ID,
    STATUT_INTERVENANT_ID,
    OBLIGATOIRE,
    SEUIL_HETD,
    PREMIER_RECRUTEMENT,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
  select
    TYPE_PIECE_JOINTE_STATU_id_seq.nextval id,
    tpj.id TYPE_PIECE_JOINTE_ID,
    si.id STATUT_INTERVENANT_ID,
    1 OBLIGATOIRE,
    null SEUIL_HETD,
    1 PREMIER_RECRUTEMENT, 
    u.id HISTO_CREATEUR_ID,
    u.id HISTO_MODIFICATEUR_ID
  from TYPE_PIECE_JOINTE tpj, STATUT_INTERVENANT si, UTILISATEUR u
  where tpj.CODE = 'CV' and si.SOURCE_CODE in ('RETR_HORS_UCBN', 'ETUD_HORS_UCBN') and u.USERNAME = 'oseappli';
  
select * from type_role;

select libelle_long, source_code from structure where libelle_court = 'ESPE'; 
select * from personnel p where upper(p.nom_usuel) = 'MAHAUT'; 

-- ajouts de gestionnaires
insert into ROLE (
    ID,
    STRUCTURE_ID,
    PERSONNEL_ID,
    TYPE_ID,
    SOURCE_ID,
    SOURCE_CODE,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID
  )
SELECT 
  role_id_seq.nextval id,
  s.id STRUCTURE_ID,
  p.ID personnel_id,
  tr.id TYPE_ID,
  src.id SOURCE_ID,
  role_id_seq.currval SOURCE_CODE,
  u.id HISTO_CREATEUR_ID,
  u.id HISTO_MODIFICATEUR_ID
FROM 
  personnel p,
  STRUCTURE s,
  TYPE_ROLE tr,
  source src,
  UTILISATEUR u
WHERE s.SOURCE_CODE    = 'E01'
AND tr.CODE            = 'gestionnaire-composante'
AND src.CODE           = 'OSE'
AND u.USERNAME         = 'oseappli'
AND upper(p.nom_usuel) = 'MAHAUT';



select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '21255' and s.source_code = 'I11' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '26771' and s.source_code = 'I12' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '4173' and s.source_code = 'I13' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '65415' and s.source_code = 'M17' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '255' and s.source_code = 'M17' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '2489' and s.source_code = 'M17' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '63573' and s.source_code = 'M17' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '52917' and s.source_code = 'M17' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '4552' and s.source_code = 'U01' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '2311' and s.source_code = 'U02' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '5781' and s.source_code = 'U02' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '987' and s.source_code = 'U02' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '26902' and s.source_code = 'U02' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '26902' and s.source_code = 'U26' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '25236' and s.source_code = 'U03' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '25428' and s.source_code = 'U04' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '476' and s.source_code = 'U07' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '46070' and s.source_code = 'U09' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '154' and s.source_code = 'U10' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '5471' and s.source_code = 'U10' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '27' and s.source_code = 'U10' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '15139' and s.source_code = 'U10' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '26899' and s.source_code = 'U10' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '68179' and s.source_code = 'U26' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '4640' and s.source_code = 'U26' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '5177' and s.source_code = 'U26' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '4628' and s.source_code = 'U36' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '5170' and s.source_code = 'U36' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '63191' and s.source_code = 'U55' union 
select 'insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, '||p.id||', '||s.id||');' from personnel p, structure s where p.source_code = '5378' and s.source_code = 'U55' 
; 

insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1008, 8680);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1242, 8468);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1247, 8495);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1371, 8757);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1476, 8472);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1560, 8474);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 1711, 8473);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2000, 8474);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2132, 8467);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 221, 8529);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2515, 8498);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2599, 8529);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 261, 8498);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2775, 8467);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2807, 8494);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 2843, 8474);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 297, 8467);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 3004, 8469);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 3199, 8474);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 353, 8466);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 357, 8529);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 3766, 8493);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 3853, 8467);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 3853, 8529);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 3866, 8498);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 4030, 8498);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 4057, 8474);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 702, 8498);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 800, 8757);
insert into role (ID , TYPE_ID , SOURCE_CODE , SOURCE_ID , HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID, PERSONNEL_ID , STRUCTURE_ID) values (role_id_seq.nextval, 8, 10000+role_id_seq.currval, 2, 1, 1, 911, 8680);




delete from TYPE_PIECE_JOINTE_STATUT;

INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    1,
    1, --cv
    11, --sal sect priv
    1, --oblig
    20 --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    2,
    1, --cv
    12, --sal sect priv
    1, --oblig
    20 --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    3,
    1, --cv
    13, --sal sect priv
    1, --oblig
    20 --seuil
  );
  
  
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    4,
    2, --rib
    11, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    5,
    2, --rib
    12, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    6,
    2, --rib
    13, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    7,
    2, --rib
    15, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    8,
    2, --rib
    16, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    9,
    2, --rib
    17, --sal sect priv
    1, --oblig
    null --seuil
  );
  
   
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    10,
    3, --rib
    11, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    11,
    3, --rib
    12, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    12,
   3, --rib
    13, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    13,
    3, --rib
    15, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    14,
    3, --rib
    16, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    15,
    3, --rib
    17, --sal sect priv
    1, --oblig
    null --seuil
  );
  
  

INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    16,
    4, --rib
    11, --sal sect priv
    1, --oblig
    null --seuil
  );
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    17,
    4, --rib
    12, --sal sect priv
    1, --oblig
    null --seuil
  );
  


INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    18,
    5, --rib
    11, --sal sect priv
    1, --oblig
    null --seuil
  );
  
  
  
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    19,
    6, --rib
    12, --sal sect priv
    1, --oblig
    null --seuil
  );
  
  
  
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    20,
    7, --rib
    13, --sal sect priv
    1, --oblig
    20 --seuil
  );
  
  
  
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    21,
    8, --rib
    15, --sal sect priv
    1, --oblig
    null --seuil
  );
  
  
  
INSERT INTO TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD) VALUES (
    22,
    9, --rib
    17, --sal sect priv
    1, --oblig
    null --seuil
  );
  
  
  Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('23','2','11','0',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('24','2','12','0',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('25','2','13','0',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('26','2','15','0',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('27','2','16','0',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('28','2','17','1',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('29','4','12','1',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('30','5','11','1',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('31','6','12','1',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('32','7','13','1','20',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('33','9','17','1',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');
Insert into TYPE_PIECE_JOINTE_STATUT (ID,TYPE_PIECE_JOINTE_ID,STATUT_INTERVENANT_ID,OBLIGATOIRE,SEUIL_HETD,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID,PREMIER_RECUTEMENT) values ('34','2','18','0',null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),null,to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',to_date('05/06/14 13:31:34','DD/MM/RR HH24:MI:SS'),'1',null,null,'0');


  













create or replace view v_resume_referentiel as
-- totaux des heures de référentiel
  select 
    i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, ti.CODE type_intervenant_code,
    s.STRUCTURE_ID STRUCTURE_ENS_ID, i.STRUCTURE_ID STRUCTURE_AFF_ID,
    s.id service_id, s.annee_id,
    sum(nvl(s.HEURES, 0)) as total_heures
  from INTERVENANT i 
  join TYPE_INTERVENANT ti on i.TYPE_ID = ti.id 
  join SERVICE_REFERENTIEL s on s.INTERVENANT_ID = i.id               and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  where i.HISTO_DESTRUCTEUR_ID is null
  group by 
    i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, ti.CODE,
    s.STRUCTURE_ID, i.STRUCTURE_ID,
    s.id, s.annee_id
  order by i.NOM_USUEL, i.PRENOM;

create or replace view v_resume_service as
-- totaux des heures de services prévisionnels
  select 
    i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, ti.CODE type_intervenant_code,
    vh.TYPE_INTERVENTION_ID, 
    s.STRUCTURE_ENS_ID STRUCTURE_ENS_ID,
    s.STRUCTURE_AFF_ID STRUCTURE_AFF_ID,
    s.id service_id, ep.id element_pedagogique_id, e.id etape_id, s.annee_id,
    sum(nvl(vh.HEURES, 0)) as total_heures
  from INTERVENANT i 
  join TYPE_INTERVENANT ti on i.TYPE_ID = ti.id 
  join SERVICE s on s.INTERVENANT_ID = i.id                                   and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  left join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id                         and vh.HISTO_DESTRUCTEUR_ID is null and sysdate between vh.VALIDITE_DEBUT and nvl(vh.VALIDITE_FIN, sysdate)
  left join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ep.HISTO_DESTRUCTEUR_ID is null and sysdate between ep.VALIDITE_DEBUT and nvl(ep.VALIDITE_FIN, sysdate)
  left join ETAPE e on ep.ETAPE_ID = e.id and e.HISTO_DESTRUCTEUR_ID is null  and sysdate between e.VALIDITE_DEBUT and nvl(e.VALIDITE_FIN, sysdate)
  where i.HISTO_DESTRUCTEUR_ID is null
  group by vh.TYPE_INTERVENTION_ID, s.STRUCTURE_ENS_ID, s.STRUCTURE_AFF_ID, ep.id, e.id, s.annee_id, i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, ti.CODE, s.id
  order by i.NOM_USUEL, i.PRENOM;



create or replace view v_resume_services as
  SELECT 
    i.NOM_USUEL,
    i.PRENOM,
    i.id intervenant_id,
    i.SOURCE_CODE,
    vh.TYPE_INTERVENTION_ID,
    s.STRUCTURE_ENS_ID STRUCTURE_ENS_ID,
    s.STRUCTURE_AFF_ID STRUCTURE_AFF_ID,
    s.id service_id,
    ep.id element_pedagogique_id,
    e.id etape_id,
    SUM(NVL(sr.HEURES, 0)) AS total_ref,
    SUM(NVL(vh.HEURES, 0)) AS total_vh
  from INTERVENANT i
  left join SERVICE_REFERENTIEL sr on sr.INTERVENANT_ID = i.id and sr.HISTO_DESTRUCTEUR_ID is null and sysdate between sr.VALIDITE_DEBUT and nvl(sr.VALIDITE_FIN, sysdate)
  left join SERVICE s on s.INTERVENANT_ID = i.id               and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  left join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id          and vh.HISTO_DESTRUCTEUR_ID is null and sysdate between vh.VALIDITE_DEBUT and nvl(vh.VALIDITE_FIN, sysdate)
  left join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id and ep.HISTO_DESTRUCTEUR_ID is null and sysdate between ep.VALIDITE_DEBUT and nvl(ep.VALIDITE_FIN, sysdate)
  left join ETAPE e on ep.ETAPE_ID = e.id and e.HISTO_DESTRUCTEUR_ID is null and sysdate between e.VALIDITE_DEBUT and nvl(e.VALIDITE_FIN, sysdate)
  where i.HISTO_DESTRUCTEUR_ID is null
  group by vh.TYPE_INTERVENTION_ID, s.STRUCTURE_ENS_ID, s.STRUCTURE_AFF_ID, ep.id, e.id, i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.id
  having sum(nvl(sr.HEURES, 0)) > 0 or sum(nvl(vh.HEURES, 0)) > 0
  order by i.NOM_USUEL, i.PRENOM, vh.TYPE_INTERVENTION_ID;









update utilisateur set id = 3 where id = 1;

update TYPE_STRUCTURE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_STRUCTURE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_STRUCTURE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update PERIODE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update PERIODE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update PERIODE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update STRUCTURE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update STRUCTURE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update STRUCTURE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update STATUT_INTERVENANT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update STATUT_INTERVENANT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update STATUT_INTERVENANT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update DISCIPLINE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update DISCIPLINE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update DISCIPLINE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update INTERVENANT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update INTERVENANT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update INTERVENANT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update VOLUME_HORAIRE_ENS set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update VOLUME_HORAIRE_ENS set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update VOLUME_HORAIRE_ENS set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ELEMENT_DISCIPLINE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ELEMENT_DISCIPLINE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ELEMENT_DISCIPLINE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ETAPE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ETAPE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ETAPE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update TYPE_INTERVENTION set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_INTERVENTION set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_INTERVENTION set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ELEMENT_PEDAGOGIQUE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ELEMENT_PEDAGOGIQUE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ELEMENT_PEDAGOGIQUE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update GROUPE_TYPE_FORMATION set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update GROUPE_TYPE_FORMATION set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update GROUPE_TYPE_FORMATION set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update TYPE_FORMATION set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_FORMATION set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_FORMATION set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ELEMENT_PORTEUR_PORTE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ELEMENT_PORTEUR_PORTE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ELEMENT_PORTEUR_PORTE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update MOTIF_NON_PAIEMENT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update MOTIF_NON_PAIEMENT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update MOTIF_NON_PAIEMENT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ROLE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ROLE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ROLE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update TYPE_ROLE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_ROLE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_ROLE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update PERSONNEL set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update PERSONNEL set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update PERSONNEL set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update SERVICE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update SERVICE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update SERVICE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ADRESSE_STRUCTURE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ADRESSE_STRUCTURE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ADRESSE_STRUCTURE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update INTERVENANT_PERMANENT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update INTERVENANT_PERMANENT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update INTERVENANT_PERMANENT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update INTERVENANT_EXTERIEUR set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update INTERVENANT_EXTERIEUR set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update INTERVENANT_EXTERIEUR set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ADRESSE_INTERVENANT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ADRESSE_INTERVENANT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ADRESSE_INTERVENANT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update AFFECTATION_RECHERCHE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update AFFECTATION_RECHERCHE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update AFFECTATION_RECHERCHE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update CHEMIN_PEDAGOGIQUE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update CHEMIN_PEDAGOGIQUE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update CHEMIN_PEDAGOGIQUE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update TYPE_INTERVENANT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_INTERVENANT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_INTERVENANT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ADRESSE_INTERVENANT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ADRESSE_INTERVENANT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ADRESSE_INTERVENANT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update VOLUME_HORAIRE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update VOLUME_HORAIRE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update VOLUME_HORAIRE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update TYPE_POSTE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_POSTE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_POSTE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update TYPE_INTERVENANT_EXTERIEUR set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update TYPE_INTERVENANT_EXTERIEUR set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update TYPE_INTERVENANT_EXTERIEUR set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update SITUATION_FAMILIALE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update SITUATION_FAMILIALE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update SITUATION_FAMILIALE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update REGIME_SECU set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update REGIME_SECU set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update REGIME_SECU set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update PARAMETRE set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update PARAMETRE set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update PARAMETRE set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update FONCTION_REFERENTIEL set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update FONCTION_REFERENTIEL set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update FONCTION_REFERENTIEL set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update ETABLISSEMENT set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update ETABLISSEMENT set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update ETABLISSEMENT set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;
update CORPS set HISTO_CREATEUR_ID = 2 where HISTO_CREATEUR_ID = 1;
update CORPS set HISTO_MODIFICATEUR_ID = 2 where HISTO_MODIFICATEUR_ID = 1;
update CORPS set HISTO_DESTRUCTEUR_ID = 2 where HISTO_DESTRUCTEUR_ID = 1;













begin ose_historique.set_current_user(1); end;
/

-- année
select 'insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (' || ID || ', ''' || LIBELLE || ''', ''' || DATE_DEBUT || ''', ''' || DATE_FIN || ''', HISTORIQUE_ID_SEQ.currval);' FROM ANNEE ;
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1950, '1950-1951', '01/09/50 00:00:00', '31/08/51 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1951, '1951-1952', '01/09/51 00:00:00', '31/08/52 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1952, '1952-1953', '01/09/52 00:00:00', '31/08/53 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1953, '1953-1954', '01/09/53 00:00:00', '31/08/54 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1954, '1954-1955', '01/09/54 00:00:00', '31/08/55 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1955, '1955-1956', '01/09/55 00:00:00', '31/08/56 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1956, '1956-1957', '01/09/56 00:00:00', '31/08/57 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1957, '1957-1958', '01/09/57 00:00:00', '31/08/58 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1958, '1958-1959', '01/09/58 00:00:00', '31/08/59 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1959, '1959-1960', '01/09/59 00:00:00', '31/08/60 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1960, '1960-1961', '01/09/60 00:00:00', '31/08/61 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1961, '1961-1962', '01/09/61 00:00:00', '31/08/62 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1962, '1962-1963', '01/09/62 00:00:00', '31/08/63 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1963, '1963-1964', '01/09/63 00:00:00', '31/08/64 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1964, '1964-1965', '01/09/64 00:00:00', '31/08/65 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1965, '1965-1966', '01/09/65 00:00:00', '31/08/66 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1966, '1966-1967', '01/09/66 00:00:00', '31/08/67 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1967, '1967-1968', '01/09/67 00:00:00', '31/08/68 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1968, '1968-1969', '01/09/68 00:00:00', '31/08/69 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1969, '1969-1970', '01/09/69 00:00:00', '31/08/70 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1970, '1970-1971', '01/09/70 00:00:00', '31/08/71 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1971, '1971-1972', '01/09/71 00:00:00', '31/08/72 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1972, '1972-1973', '01/09/72 00:00:00', '31/08/73 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1973, '1973-1974', '01/09/73 00:00:00', '31/08/74 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1974, '1974-1975', '01/09/74 00:00:00', '31/08/75 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1975, '1975-1976', '01/09/75 00:00:00', '31/08/76 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1976, '1976-1977', '01/09/76 00:00:00', '31/08/77 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1977, '1977-1978', '01/09/77 00:00:00', '31/08/78 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1978, '1978-1979', '01/09/78 00:00:00', '31/08/79 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1979, '1979-1980', '01/09/79 00:00:00', '31/08/80 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1980, '1980-1981', '01/09/80 00:00:00', '31/08/81 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1981, '1981-1982', '01/09/81 00:00:00', '31/08/82 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1982, '1982-1983', '01/09/82 00:00:00', '31/08/83 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1983, '1983-1984', '01/09/83 00:00:00', '31/08/84 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1984, '1984-1985', '01/09/84 00:00:00', '31/08/85 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1985, '1985-1986', '01/09/85 00:00:00', '31/08/86 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1986, '1986-1987', '01/09/86 00:00:00', '31/08/87 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1987, '1987-1988', '01/09/87 00:00:00', '31/08/88 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1988, '1988-1989', '01/09/88 00:00:00', '31/08/89 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1989, '1989-1990', '01/09/89 00:00:00', '31/08/90 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1990, '1990-1991', '01/09/90 00:00:00', '31/08/91 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1991, '1991-1992', '01/09/91 00:00:00', '31/08/92 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1992, '1992-1993', '01/09/92 00:00:00', '31/08/93 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1993, '1993-1994', '01/09/93 00:00:00', '31/08/94 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1994, '1994-1995', '01/09/94 00:00:00', '31/08/95 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1995, '1995-1996', '01/09/95 00:00:00', '31/08/96 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1996, '1996-1997', '01/09/96 00:00:00', '31/08/97 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1997, '1997-1998', '01/09/97 00:00:00', '31/08/98 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1998, '1998-1999', '01/09/98 00:00:00', '31/08/99 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (1999, '1999-2000', '01/09/99 00:00:00', '31/08/00 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2000, '2000-2001', '01/09/00 00:00:00', '31/08/01 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2001, '2001-2002', '01/09/01 00:00:00', '31/08/02 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2002, '2002-2003', '01/09/02 00:00:00', '31/08/03 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2003, '2003-2004', '01/09/03 00:00:00', '31/08/04 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2004, '2004-2005', '01/09/04 00:00:00', '31/08/05 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2005, '2005-2006', '01/09/05 00:00:00', '31/08/06 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2006, '2006-2007', '01/09/06 00:00:00', '31/08/07 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2007, '2007-2008', '01/09/07 00:00:00', '31/08/08 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2008, '2008-2009', '01/09/08 00:00:00', '31/08/09 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2009, '2009-2010', '01/09/09 00:00:00', '31/08/10 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2010, '2010-2011', '01/09/10 00:00:00', '31/08/11 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2011, '2011-2012', '01/09/11 00:00:00', '31/08/12 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2012, '2012-2013', '01/09/12 00:00:00', '31/08/13 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2013, '2013-2014', '01/09/13 00:00:00', '31/08/14 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2014, '2014-2015', '01/09/14 00:00:00', '31/08/15 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2015, '2015-2016', '01/09/15 00:00:00', '31/08/16 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2016, '2016-2017', '01/09/16 00:00:00', '31/08/17 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2017, '2017-2018', '01/09/17 00:00:00', '31/08/18 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2018, '2018-2019', '01/09/18 00:00:00', '31/08/19 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2019, '2019-2020', '01/09/19 00:00:00', '31/08/20 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2020, '2020-2021', '01/09/20 00:00:00', '31/08/21 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2021, '2021-2022', '01/09/21 00:00:00', '31/08/22 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2022, '2022-2023', '01/09/22 00:00:00', '31/08/23 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2023, '2023-2024', '01/09/23 00:00:00', '31/08/24 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2024, '2024-2025', '01/09/24 00:00:00', '31/08/25 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2025, '2025-2026', '01/09/25 00:00:00', '31/08/26 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2026, '2026-2027', '01/09/26 00:00:00', '31/08/27 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2027, '2027-2028', '01/09/27 00:00:00', '31/08/28 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2028, '2028-2029', '01/09/28 00:00:00', '31/08/29 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2029, '2029-2030', '01/09/29 00:00:00', '31/08/30 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2030, '2030-2031', '01/09/30 00:00:00', '31/08/31 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2031, '2031-2032', '01/09/31 00:00:00', '31/08/32 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2032, '2032-2033', '01/09/32 00:00:00', '31/08/33 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2033, '2033-2034', '01/09/33 00:00:00', '31/08/34 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2034, '2034-2035', '01/09/34 00:00:00', '31/08/35 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2035, '2035-2036', '01/09/35 00:00:00', '31/08/36 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2036, '2036-2037', '01/09/36 00:00:00', '31/08/37 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2037, '2037-2038', '01/09/37 00:00:00', '31/08/38 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2038, '2038-2039', '01/09/38 00:00:00', '31/08/39 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2039, '2039-2040', '01/09/39 00:00:00', '31/08/40 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2040, '2040-2041', '01/09/40 00:00:00', '31/08/41 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2041, '2041-2042', '01/09/41 00:00:00', '31/08/42 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2042, '2042-2043', '01/09/42 00:00:00', '31/08/43 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2043, '2043-2044', '01/09/43 00:00:00', '31/08/44 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2044, '2044-2045', '01/09/44 00:00:00', '31/08/45 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2045, '2045-2046', '01/09/45 00:00:00', '31/08/46 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2046, '2046-2047', '01/09/46 00:00:00', '31/08/47 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2047, '2047-2048', '01/09/47 00:00:00', '31/08/48 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2048, '2048-2049', '01/09/48 00:00:00', '31/08/49 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2049, '2049-2050', '01/09/49 00:00:00', '31/08/50 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2050, '2050-2051', '01/09/50 00:00:00', '31/08/51 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2051, '2051-2052', '01/09/51 00:00:00', '31/08/52 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2052, '2052-2053', '01/09/52 00:00:00', '31/08/53 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2053, '2053-2054', '01/09/53 00:00:00', '31/08/54 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2054, '2054-2055', '01/09/54 00:00:00', '31/08/55 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2055, '2055-2056', '01/09/55 00:00:00', '31/08/56 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2056, '2056-2057', '01/09/56 00:00:00', '31/08/57 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2057, '2057-2058', '01/09/57 00:00:00', '31/08/58 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2058, '2058-2059', '01/09/58 00:00:00', '31/08/59 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2059, '2059-2060', '01/09/59 00:00:00', '31/08/60 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2060, '2060-2061', '01/09/60 00:00:00', '31/08/61 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2061, '2061-2062', '01/09/61 00:00:00', '31/08/62 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2062, '2062-2063', '01/09/62 00:00:00', '31/08/63 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2063, '2063-2064', '01/09/63 00:00:00', '31/08/64 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2064, '2064-2065', '01/09/64 00:00:00', '31/08/65 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2065, '2065-2066', '01/09/65 00:00:00', '31/08/66 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2066, '2066-2067', '01/09/66 00:00:00', '31/08/67 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2067, '2067-2068', '01/09/67 00:00:00', '31/08/68 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2068, '2068-2069', '01/09/68 00:00:00', '31/08/69 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2069, '2069-2070', '01/09/69 00:00:00', '31/08/70 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2070, '2070-2071', '01/09/70 00:00:00', '31/08/71 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2071, '2071-2072', '01/09/71 00:00:00', '31/08/72 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2072, '2072-2073', '01/09/72 00:00:00', '31/08/73 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2073, '2073-2074', '01/09/73 00:00:00', '31/08/74 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2074, '2074-2075', '01/09/74 00:00:00', '31/08/75 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2075, '2075-2076', '01/09/75 00:00:00', '31/08/76 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2076, '2076-2077', '01/09/76 00:00:00', '31/08/77 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2077, '2077-2078', '01/09/77 00:00:00', '31/08/78 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2078, '2078-2079', '01/09/78 00:00:00', '31/08/79 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2079, '2079-2080', '01/09/79 00:00:00', '31/08/80 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2080, '2080-2081', '01/09/80 00:00:00', '31/08/81 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2081, '2081-2082', '01/09/81 00:00:00', '31/08/82 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2082, '2082-2083', '01/09/82 00:00:00', '31/08/83 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2083, '2083-2084', '01/09/83 00:00:00', '31/08/84 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2084, '2084-2085', '01/09/84 00:00:00', '31/08/85 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2085, '2085-2086', '01/09/85 00:00:00', '31/08/86 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2086, '2086-2087', '01/09/86 00:00:00', '31/08/87 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2087, '2087-2088', '01/09/87 00:00:00', '31/08/88 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2088, '2088-2089', '01/09/88 00:00:00', '31/08/89 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2089, '2089-2090', '01/09/89 00:00:00', '31/08/90 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2090, '2090-2091', '01/09/90 00:00:00', '31/08/91 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2091, '2091-2092', '01/09/91 00:00:00', '31/08/92 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2092, '2092-2093', '01/09/92 00:00:00', '31/08/93 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2093, '2093-2094', '01/09/93 00:00:00', '31/08/94 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2094, '2094-2095', '01/09/94 00:00:00', '31/08/95 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2095, '2095-2096', '01/09/95 00:00:00', '31/08/96 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2096, '2096-2097', '01/09/96 00:00:00', '31/08/97 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2097, '2097-2098', '01/09/97 00:00:00', '31/08/98 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2098, '2098-2099', '01/09/98 00:00:00', '31/08/99 00:00:00');
insert into ANNEE ( ID, LIBELLE, DATE_DEBUT, DATE_FIN) values (2099, '2099-2100', '01/09/99 00:00:00', '31/08/00 00:00:00');

create sequence historique_id_seq;
create sequence regime_secu_id_seq;

-- civilité
select 'insert into CIVILITE ( ID, LIBELLE_COURT, LIBELLE_LONG, SEXE) values (' || ROWNUM || ', ''' || LIBELLE || ''', ''' || SEXE || ''');' FROM CIVILITE ;
insert into CIVILITE ( ID, LIBELLE_COURT, LIBELLE_LONG, SEXE) values (2, 'M.', 'Monsieur', 'M');
insert into CIVILITE ( ID, LIBELLE_COURT, LIBELLE_LONG, SEXE) values (1, 'Mme', 'Madame', 'F');

-- REGIME_SECU
--select 'insert into REGIME_SECU ( ID, LIBELLE, TAUX_TAXE, HISTORIQUE_ID) values (REGIME_SECU_ID_SEQ.nextval, ''' || LIBELLE || ''', ''' || TAUX_TAXE || ''', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);' FROM REGIME_SECU ;
insert into REGIME_SECU ( ID, CODE, LIBELLE, TAUX_TAXE, HISTORIQUE_ID) values (REGIME_SECU_ID_SEQ.nextval, '61', 'Régime général (salaire brut sup. au plafond sécu.)', '0', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into REGIME_SECU ( ID, CODE, LIBELLE, TAUX_TAXE, HISTORIQUE_ID) values (REGIME_SECU_ID_SEQ.nextval, '12', 'Régime général (salaire brut inf. au plafond sécu.)', '0', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into REGIME_SECU ( ID, CODE, LIBELLE, TAUX_TAXE, HISTORIQUE_ID) values (REGIME_SECU_ID_SEQ.nextval, '01', 'Régime fonctionnaire (agents titulaires)', '0', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);

-- SITUATION_FAMILIALE
select 'insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, ''' || LIBELLE || ''', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);' FROM SITUATION_FAMILIALE ;
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'Célibataire', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'Marié(e)', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'Veuf(ve)', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'Divorcé(e)', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'Concubinage', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'Séparé(e)', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into SITUATION_FAMILIALE ( ID, LIBELLE, HISTORIQUE_ID) values (SITUATION_FAMILIALE_ID_SEQ.nextval, 'PACS', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);

-- SOURCE
select 'insert into SOURCE ( ID, LIBELLE, HISTORIQUE_ID) values (SOURCE_ID_SEQ.nextval, ''' || LIBELLE || ''', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);' FROM SOURCE ;
insert into SOURCE ( ID, LIBELLE) values (SOURCE_ID_SEQ.nextval, 'Harpège');
insert into SOURCE ( ID, LIBELLE) values (SOURCE_ID_SEQ.nextval, 'Test');
insert into SOURCE ( ID, LIBELLE) values (SOURCE_ID_SEQ.nextval, 'OSE');

-- TYPE_INTERVENANT
select 'insert into TYPE_INTERVENANT ( ID, LIBELLE, HISTORIQUE_ID) values (' || ROWNUM || ', ''' || LIBELLE || ''', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);' FROM TYPE_INTERVENANT ;
insert into TYPE_INTERVENANT ( ID, LIBELLE, HISTORIQUE_ID) values (1, 'Intervenant permanent', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT ( ID, LIBELLE, HISTORIQUE_ID) values (2, 'Intervenant extérieur', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);

-- TYPE_INTERVENANT_EXTERIEUR
select 'insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (' || ROWNUM || ', ''' || LIBELLE || ''', ' || LIMITE_HEURES_COMPLEMENTAIRES || ', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);' FROM TYPE_INTERVENANT_EXTERIEUR ;
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (1, 'Retraité', 96, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (2, 'Intermittent du spectacle', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (3, 'Salarié du secteur privé', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (4, 'Travailleur indépendant', 150, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (5, 'Etudiant', 96, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (6, 'Allocataire de recherche', 96, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (7, 'Personnel Etabl. d''ens. sup. privé (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (8, 'Personnel Etabl. du 1er degré privé (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (9, 'Personnel Etabl. du 2nd degré privé (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (10, 'Fonctionnaire autre administration (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (11, 'Personnel Etabl. d''ens. sup. public (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (12, 'Personnel Etabl. du 1er degré public (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (13, 'Personnel Etabl. du 2nd degré public (titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (14, 'Personnel Etabl. du 2nd degré public (non titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (15, 'Personnel Etabl. d''ens. sup. privé (non titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (16, 'Personnel Etabl. d''ens. sup. public (non titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (17, 'Personnel Etabl. du 1er degré privé (non titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (18, 'Personnel Etabl. du 1er degré public (non titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (19, 'Personnel Etabl. du 2nd degré privé (non titulaire)', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_INTERVENANT_EXTERIEUR ( ID, LIBELLE, LIMITE_HEURES_COMPLEMENTAIRES, HISTORIQUE_ID) values (20, 'Contractuel autre administration', 187, OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);

-- TYPE_POSTE
select 'insert into TYPE_POSTE ( ID, LIBELLE_COURT, LIBELLE_LONG, HISTORIQUE_ID) values (' || ROWNUM || ', ''' || ID || ''', ''' || LIBELLE || ''', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);' FROM TYPE_POSTE ;
insert into TYPE_POSTE ( ID, LIBELLE, HISTORIQUE_ID) values (1, 'Fonction publique - Titulaire', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_POSTE ( ID, LIBELLE, HISTORIQUE_ID) values (2, 'Privé', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);
insert into TYPE_POSTE ( ID, LIBELLE, HISTORIQUE_ID) values (3, 'Fonction publique - Contractuel ', OSE_HISTORIQUE.CREATE_HISTORIQUE_ID);

