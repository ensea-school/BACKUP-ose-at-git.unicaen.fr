/*===================== ALTER TABLES OSETEST à partir dela version de 05/2019  ==================*/
-- MYP le 12/09/2019
alter table UM_STRUCTURE ADD TEM_STRUCT_MANU VARCHAR2(1	CHAR);
update UM_STRUCTURE SET TEM_STRUCT_MANU = 'N';

-- MYP le 12/09/2019
alter table UM_STRUCTURE ADD TEM_STRUCT_MANU VARCHAR2(1	CHAR);
update UM_STRUCTURE SET TEM_STRUCT_MANU = 'N';

CREATE INDEX IDX_UM_TRANSFERT_FORCE on UM_TRANSFERT_FORCE(NUDOSS,D_VERIF_MANUELLE);


-- OSEPROD : rajouté myp le 14/10/2019
alter table UM_TRANSFERT_INDIVIDU ADD ANNEE_ID	NUMBER(9);
update UM_TRANSFERT_INDIVIDU set annee_id = 2018;  -- ##A_PERSONNALISER_CHOIX_OSE## -- mettre l'annee en cours que vous avez synchronisée dernièrement
commit;
alter table UM_TRANSFERT_INDIVIDU drop constraint UK_TRANSF_IND_MATCLE; 
drop index UK_TRANSF_IND_MATCLE;  
commit;
alter table UM_TRANSFERT_INDIVIDU ADD constraint UK_TRANSF_IND_MATCLE UNIQUE (ANNEE_ID, MATCLE);


alter table UM_SYNCHRO_A_VALIDER ADD ANNEE_ID	NUMBER(9);
update UM_SYNCHRO_A_VALIDER set annee_id = 2018;
commit;

ALTER table UM_SYNCHRO_A_VALIDER DROP CONSTRAINT UK_SYNCHRO_A_VAL;
DROP INDEX UK_SYNCHRO_A_VAL;
commit;
ALTER table UM_SYNCHRO_A_VALIDER ADD CONSTRAINT UK_SYNCHRO_A_VAL UNIQUE (ANNEE_ID, MATCLE, CHANGEMENT_STATUT);


-- MYP le 16/09/2019 le 04/10/2019 OSEPROD Remplacement du témoin HOREC en HOSE
select * from um_synchro_a_valider
where changement_statut like '%->HOREC()%'

update um_synchro_a_valider
set changement_statut = replace(changement_statut, '->HOREC()', '->HOSE()')
where changement_statut like '%->HOREC()%';


-- MYP le 02/12/2019 OSEPREP + OSEPROD + OSETEST
alter table UM_STRUCTURE ADD EOTP_DU_DEFAUT	VARCHAR2(100	CHAR);
alter table UM_STRUCTURE ADD EOTP_DN_DEFAUT	VARCHAR2(100	CHAR);
commit;











