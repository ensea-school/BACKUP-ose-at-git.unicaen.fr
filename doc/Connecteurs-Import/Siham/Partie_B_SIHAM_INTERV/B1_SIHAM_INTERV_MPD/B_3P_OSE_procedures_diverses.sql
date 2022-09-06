/* ====================================================================================================
	# Detail du connecteur PARTIE B/ SIHAM_INTERV : synchro des intervenants - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	PROCEDURES DIVERSES POUR LA SYNCHRO DES INTERVENANTS : à lancer en 1ere installation ou maj de version
		diverses petites procédures autres que SELECT_INTERVENANT ET SYNCHRO_INTERVENANT
			
	--- procedures ------------------ 
	OSE.UM_PURGE_UM_TRANSFERT_INDIVIDU
	OSE.UM_SYNCHRO_ADRESSE_INTERVENANT
	OSE.UM_INIT_RIB_HORS_SEPA
	OSE.UM_MAJ_UM_SYNCHRO_A_VALIDER
	OSE.UM_SELECT_MULTI_STATUT
	OSE.UM_MAJ_INSERT_STATUT
	OSE.UM_INSERT_UM_STATUT
	----------------------------
	
	-- v2.1 - 03/07/20 MYP : pour Ose v14
	-- v2.2 - 03/12/20 MYP : pour OSE V15 : découpage adresses intervenant avec numero compl et voirie
	-- v2.3 - 18/06/21 MYP : raz numero_compl_code si inexistant dans OSE.ADRESSE_NUMERO_COMPL 
		                     + correction car si MULTI_AUTO 'AI' tous les flags et dates ne suivent pas 
							 
	-- v2.4 - 19/07/21 MYP : mail perso rempli meme si VAC et compte um validé
	-- v2.4b- 04/02/22 MYP : dblink .world
	-- v2.5 - 14/06/22 MYP : ajout UM_INSERT_UM_STATUT + remplacer UM_STATUT_INTERVENANT par UM_STATUT + jointure annee_id
=====================================================================================================*/


CREATE OR REPLACE PROCEDURE OSE.UM_PURGE_UM_TRANSFERT_INDIVIDU(p_annee_id IN number) IS
/* ===================================================================================================
    PROCEDURE UM_PURGE_UM_TRANSFERT_INDIVIDU 
====================================================================================================*/   
-- purge la table  OSE.UM_TRANSFERT_INDIVIDU pour une annee (on ne conserve l historique que de la derniere synchro de l'annee en question)                       
-- v1.12 19/09/2019

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_insert            number(9) := 0;

BEGIN
    dbms_output.put_line('   - Lancement purge UM_TRANSFERT_INDIVIDU annee : '||p_annee_id||' - Début '||to_char(sysdate, 'DD/MM/YYYY HH24:MI:SS'));
    delete from OSE.UM_TRANSFERT_INDIVIDU where annee_id = p_annee_id;
    commit;
    dbms_output.put_line('   - Apres delete : '||to_char(sysdate, 'DD/MM/YYYY HH24:MI:SS'));
END;
/


CREATE OR REPLACE PROCEDURE OSE.UM_SYNCHRO_ADRESSE_INTERVENANT (p_source_id number, p_annee_id number, p_date_horodatage date) IS
/* ===================================================================================================
			PROCEDURE UM_SYNCHRO_ADRESSE_INTERVENANT
====================================================================================================*/					   

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_insert				number(9) := 0;
v_nb_update				number(9) := 0;
v_nb_total				number(9) := 0;
v_nb_traites			number(9) := 0;	--- MYP

v_existe_adr			number(9);
v_tel_domicile			varchar2(25);
v_batiment				varchar2(60);
v_no_voie				varchar2(20);
v_nom_voie				varchar2(120);
v_localite				varchar2(120);
v_code_postal			varchar2(15);
v_ville					varchar2(120);
v_pays_code_insee		varchar2(3);
v_pays_libelle			varchar2(50);
v_w_mail_perso			varchar2(255);	

v_numero_compl_code		varchar2(5) := '';		-- v2.2 - 26/01/2021
v_voirie_code			varchar2(5) := '';						   

/*================== curseur cur_adr_interv ===============================*/
cursor cur_adr_interv_OSE is
	select ose_i.id, ose_i.source_code as matricule		-- v2.2 26/01/2021
	 ,ose_i.source_code
	 ,ose_i.date_horodatage
	 ,ose_i.annee_id
	 -- si VACATAIRE remonter tel perso et pas mail perso -- ##A_PERSONNALISER_CHOIX_SIHAM## 
	 -- si PERMANENT remonter mail_perso et pas tel_perso -- ##A_PERSONNALISER_CHOIX_SIHAM## 
	 ,case when typ.code = 'E' then v_tel.tel_perso else '' end			as tel_domicile
	 ,v_tel.mail_perso as mail_perso  -- ,case when typ.code = 'E' then '' else v_tel.mail_perso end 		as mail_perso -- v2.4
	 ,v_adr.batiment			as batiment
	 --,v_adr.no_voie||' '||v_adr.bis_ter 	as no_voie
	 ,v_adr.no_voie			 	as no_voie		-- v2.2 OSE V15
	 ,v_adr.nom_voie 			as nom_voie
	 ,v_adr.localite
	 ,v_adr.code_postal
	 ,v_adr.ville
	 ,v_adr.pays_code_insee
	 ,v_adr.pays_libelle
	 --,UM_EXISTE_ADR_NUM_COMPL(v_adr.NUMERO_COMPL) 	as NUMERO_COMPL_ID 	-- v2.2 03/12/2020
	 ,v_adr.NUMERO_COMPL						 	as NUMERO_COMPL_CODE 	-- v2.2 26/01/2021
	 ,UM_EXISTE_VOIRIE_LIB(v_adr.VOIRIE) 			as VOIRIE_CODE			-- v2.2 26/01/2021
	from OSE.UM_INTERVENANT ose_i
		,hr.zy00@SIHAM.WORLD i
		 ,(    ---- v_tel -----------------------------------------------------------------
			select 
				nudoss
				,trim(TRANSLATE(upper(trim(max(decode(typtel,'TPR', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_pro            
				,trim(TRANSLATE(upper(trim(max(decode(typtel,'TPE', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_perso
				,trim(TRANSLATE(upper(trim(max(decode(typtel,'PPR', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_mobile_pro
				,trim(TRANSLATE(upper(trim(max(decode(typtel,'PPE', numtel,'')))), '? -_./@ABCDEFGHIJKLMNOPQRSTUVWXYZ', ' ' )) as tel_mobile_perso
				,trim(max(decode(typtel,'MPR', numtel,''))) as mail_pro
				,trim(max(decode(typtel,'MPE', numtel,''))) as mail_perso
			from hr.zy0h@SIHAM.WORLD
			where typtel in ('TPR','TPE','PPR','PPE','MPR','MPE') -- ##A_PERSONNALISER_CHOIX_SIHAM## suivant vos types de coordonnees
			group by nudoss
		 ) v_tel
		 ,(    ---- v_adr -----------------------------------------------------------------
			select nudoss
				, datdeb, datfin
				,trim(zonada) 							as batiment 
				,trim(substr(zonadb,1,4)) 				as no_voie
				-- ,decode(trim(substr(zonadb,5,1)),'B','Bis','T','Ter','') as bis_ter 							-- v2.2 03/12/2020
				--,upper(trim(substr(zonadb,5,2))) 		as NUMERO_COMPL 										-- v2.2 03/12/2020
				,compl.code						 		as NUMERO_COMPL 										-- v2.3 11/06/2020
				,upper(trim(substr(trim(substr(zonadb,7,32)) , 1, instr(trim(substr(zonadb,7,32)),' ')-1))) as VOIRIE 	-- v2.2 03/12/2020  -- !!!!!!!!!!!!! prévoir : GRAND RUE (avec espace!! )
				--,trim(substr(zonadb,7,32)) 	as nom_voie														-- v2.2 03/12/2020
				,trim(substr(trim(substr(zonadb,7,32)), instr(trim(substr(zonadb,7,32)),' ')+1,length(trim(substr(zonadb,7,32)) ))) as nom_voie  -- v2.2 03/12/2020
				,trim(zonadc) 				as localite
				,trim(cdpost) 				as code_postal
				,trim(substr(zonadd,7,32)) 	as ville
				,cdpays 					as pays_code_insee
				,trim(p.libelle_court)		as pays_libelle
			from hr.zy0f@SIHAM.WORLD,
				UM_PAYS p,
				OSE.ADRESSE_NUMERO_COMPL compl		-- v2.3 11/06/2020
			where temadd = 1
			 and cdpays = p.source_code(+)
			 and upper(trim(substr(zonadb,5,2))) = compl.code(+)		-- v2.3 11/06/2020
		 ) v_adr
		,OSE.UM_STATUT st
        ,OSE.TYPE_INTERVENANT typ
	where  ose_i.annee_id = p_annee_id 			-- v1.12 ordre champs clés
        and ose_i.date_horodatage >= p_date_horodatage
        and OSE_I.STATUT_ID = st.id
		and ose_i.annee_id = st.annee_id		-- v2.5 14/06/22
        and st.type_intervenant_id = typ.id
        and OSE_I.SOURCE_CODE = i.matcle		-- v2.2 26/01/2021
        and i.nudoss = v_tel.nudoss(+)
        and i.nudoss = v_adr.nudoss
;

/*========= PROG PRINCIPAL PROCEDURE UM_SYNCHRO_ADRESSE_INTERVENANT ===============================*/
BEGIN
	/*======================================== MAJ ADR OSE ================================*/
	dbms_output.put_line(' ');
	dbms_output.put_line('   Lancement synchro UM_ADRESSE_INTERVENANT OSE : ');
	FOR c1 in cur_adr_interv_OSE LOOP
		--v_nb_traites := v_nb_traites+1;	-- MYP
		--dbms_output.put_line('   Nb adr traitees : '||v_nb_traites); -- MYP
		
		v_existe_adr := 0;
	    v_existe_adr := UM_EXISTE_ADR_INTERVENANT(c1.id);
		
		IF v_existe_adr <> 0 then -- UPDATE

			select trim(tel_domicile), trim(batiment), trim(no_voie),  trim(nom_voie), trim(localite), trim(code_postal), trim(ville), trim(pays_code_insee), trim(pays_libelle), trim(w_mail_perso)
					, NUMERO_COMPL_CODE, VOIRIE_CODE
                INTO v_tel_domicile, v_batiment, v_no_voie, v_nom_voie, v_localite, v_code_postal, v_ville, v_pays_code_insee, v_pays_libelle, v_w_mail_perso
					, v_numero_compl_code, v_voirie_code
					
            from OSE.UM_ADRESSE_INTERVENANT
            where intervenant_id = c1.id;

            IF ( v_tel_domicile 		<> c1.tel_domicile 		or v_tel_domicile is null or c1.tel_domicile is null    -- v2.4 19/07/21
                    or v_batiment 		<> c1.batiment			or v_batiment is null or c1.batiment is null    		-- v2.4 19/07/21
					or v_no_voie 		<> c1.no_voie			or v_no_voie is null or c1.no_voie is null    			-- v2.4 19/07/21
					or v_nom_voie 		<> c1.nom_voie			or v_nom_voie is null or c1.nom_voie is null			-- v2.4 19/07/21
					or v_localite 		<> c1.localite			or v_localite is null or c1.localite is null    		-- v2.4 19/07/21
					or v_code_postal 	<> c1.code_postal		or v_code_postal is null or c1.code_postal is null    	-- v2.4 19/07/21
					or v_ville		 	<> c1.ville				or v_ville is null or c1.ville is null    				-- v2.4 19/07/21
					or v_pays_code_insee <> c1.pays_code_insee	or v_pays_code_insee is null or c1.pays_code_insee is null	-- v2.4 19/07/21
					or v_pays_libelle 	<> c1.pays_libelle		or v_pays_libelle is null or c1.pays_libelle is null	-- v2.4 19/07/21
					or v_w_mail_perso 	<> c1.mail_perso 		or v_w_mail_perso is null or c1.mail_perso is null  -- v2.4 19/07/21
					or v_numero_compl_code <> c1.numero_compl_code  or v_numero_compl_code is null or c1.numero_compl_code is null 	-- v2.2 - 03/12/2020
					or v_voirie_code 	<> c1.voirie_code 		or v_voirie_code is null or c1.voirie_code is null	-- v2.2 - 26/01/2021
				) THEN
			BEGIN
				v_nb_update := v_nb_update+1 ;
				update OSE.UM_ADRESSE_INTERVENANT SET 
					tel_domicile 		= c1.tel_domicile
					,batiment 			= c1.batiment
					,no_voie 			= c1.no_voie
					,nom_voie			= c1.nom_voie
					,localite 			= c1.localite
					,code_postal 		= c1.code_postal
					,ville				= c1.ville
					,pays_code_insee 	= c1.pays_code_insee
					,pays_libelle 		= c1.pays_libelle
					,W_mail_perso		= c1.mail_perso
					,NUMERO_COMPL_CODE 	= c1.numero_compl_code	-- v2.2 - 26/01/2021
					,VOIRIE_CODE			= c1.voirie_code	-- v2.2 - 26/01/2021	
			WHERE intervenant_id = c1.id;
			EXCEPTION
			-- when no_data_found then null;
			when others then
					rollback;
					dbms_output.put_line('   !!! Pb update UM_ADRESSE_INTERVENANT : '||trim(c1.matricule)||'id_interv : '||c1.id);
				end;
			END IF;
		ELSE
			-- INSERT si adr renseignée
			IF c1.pays_code_insee||c1.pays_libelle is not null THEN 
				BEGIN
				v_nb_insert := v_nb_insert+1 ;
			
				insert into OSE.UM_ADRESSE_INTERVENANT(intervenant_id, tel_domicile, batiment, no_voie, nom_voie, localite, code_postal, ville
					, pays_code_insee, pays_libelle, SOURCE_ID, SOURCE_CODE, W_mail_perso, NUMERO_COMPL_CODE, VOIRIE_CODE) 	-- v2.2 - 26/01/2021
				values (c1.id
					,c1.tel_domicile
					,c1.batiment
					,c1.no_voie
					,c1.nom_voie
					,c1.localite
					,c1.code_postal
					,c1.ville
					,c1.pays_code_insee
					,c1.pays_libelle
					,p_source_id
					,c1.source_code||'_'||c1.annee_id
					,c1.mail_perso
					,c1.numero_compl_code  					-- v2.2 - 26/01/2021
					,c1.voirie_code							-- v2.2 - 26/01/2021
					);
				EXCEPTION
					-- when no_data_found then null;
					when others then
							rollback;
							dbms_output.put_line('   !!! Pb insert UM_ADRESSE_INTERVENANT : '||trim(c1.matricule)||'intervenant_id : '||c1.id);
				END;
			END IF;
		END IF;
					
	END LOOP;
	COMMIT;
	select count(*) INTO v_nb_total from OSE.UM_ADRESSE_INTERVENANT;
	dbms_output.put_line('      => nb_insert :'||v_nb_insert||' - nb update :'||v_nb_update||' - nb enreg total :'||v_nb_total);
	
	-- v1.8c suppr adresse si um_intervenant inexistant
	delete from OSE.UM_ADRESSE_INTERVENANT
	where intervenant_id in (select adr.intervenant_id
				from OSE.um_adresse_intervenant adr, OSE.um_intervenant i
				where adr.intervenant_id = i.id(+)
				and i.id is null);													  
	
END;
/

CREATE OR REPLACE PROCEDURE OSE.UM_INIT_RIB_HORS_SEPA IS
/* ===================================================================================================
			PROCEDURE UM_INIT_RIB_HORS_SEPA
====================================================================================================*/					   

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_update				number(9) := 0;
v_existe_ind 			number(9) := 0;
 
/*================== curseur cur_adr_interv ===============================*/
cursor cur_rib_hors_sepa is
select bq.nudoss
		, trim(i.matcle) as matcle
		, i.nomuse
		, i.prenom
        ,trim(bq.cpiban)     as iban
        ,trim(bq.swift)     as bic
        ,trunc(bq.datdeb)      as datdeb
        ,case when trim(d.liblon) = 'Virement hors SEPA' then 1 else 0 end as rib_hors_sepa     -- v3.0 07/01/2021
        from hr.zy0i@SIHAM.WORLD bq,         -- coord bancaires
            hr.ZD00@SIHAM.WORLD c,         	-- reglementation        -- v3.0 07/01/2021
            hr.ZD01@SIHAM.WORLD d,			-- v3.0 07/01/2021
			hr.zy00@SIHAM.WORLD i
        where bq.modpai= c.CDCODE 
            and c.cdstco = 'DRN'
            and c.nudoss = d.nudoss
            and d.cdlang = 'F'
            and trim(d.liblon) = 'Virement hors SEPA'
			and bq.nudoss = i.nudoss
-- le 07/01/21 : 6 enreg
;

/*========= PROG PRINCIPAL PROCEDURE UM_INIT_RIB_HORS_SEPA ===============================*/
BEGIN
	dbms_output.put_line(' ');
	dbms_output.put_line('   Lancement maj rib_hors_sepa : ');
	FOR c1 in cur_rib_hors_sepa LOOP
		
		v_existe_ind := 0;
	    v_existe_ind := UM_EXISTE_IBAN(c1.matcle, c1.iban);
		
		IF v_existe_ind <> 0 then -- UPDATE INTERVENANT
			BEGIN
			v_nb_update := v_nb_update +1;
			update OSE.UM_INTERVENANT SET 
				rib_hors_sepa = 1
			WHERE source_code = c1.matcle and iban = c1.iban;
			EXCEPTION
			-- when no_data_found then null;
			when others then
					rollback;
					dbms_output.put_line('   !!! Pb update UM_INTERVENANT.rib_hors_sepa : '||trim(c1.matcle)||'iban : '||c1.iban);
				end;
		END IF;
					
	END LOOP;
	COMMIT;
	
	dbms_output.put_line('  nb update :'||v_nb_update);	
	
END;
/	

CREATE OR REPLACE PROCEDURE OSE.UM_MAJ_UM_SYNCHRO_A_VALIDER(p_siham_matricule IN VARCHAR2, p_annee_id IN number) IS
/* ===================================================================================================
			PROCEDURE UM_MAJ_UM_SYNCHRO_A_VALIDER	-- v2.2
====================================================================================================*/					   

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_update				number(9) := 0;
v_existe_ind 			number(9) := 0;
 
/*================== curseur cur_adr_interv ===============================*/
CURSOR cur_synchro_a_valider IS
	select id
	from OSE.UM_SYNCHRO_A_VALIDER s
	where s.matcle = p_siham_matricule
		and s.annee_id = p_annee_id  -- v1.13
		-- -- si changement validé par DRH-BGME  pour etre inséré en plus du précedent
		and tem_validation = 'I'
		and d_validation is not null
		-- et pas encore transfere dans ose ou en cours de transfert dans ose
		and d_transfert_force is null
	;

/*========= PROG PRINCIPAL PROCEDURE UM_MAJ_UM_SYNCHRO_A_VALIDER ===============================*/
BEGIN
	--dbms_output.put_line(' ');
	--dbms_output.put_line('   Lancement maj UM_SYNCHRO_A_VALIDER : ');

	FOR c1 in cur_synchro_a_valider LOOP
			BEGIN
			-- maj d_transfert_force pour ne pas traiter la ligne la prochaine fois
			update OSE.UM_SYNCHRO_A_VALIDER SET 
				d_transfert_force = sysdate	
			WHERE id = c1.id;
			EXCEPTION
			-- when no_data_found then null;
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_SYNCHRO_A_VALIDER : '||' id: '||c1.id||' '||p_siham_matricule);
			END;
					
	END LOOP;
	COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE OSE.UM_SELECT_MULTI_STATUT(p_annee_id IN number) IS
/* ===================================================================================================
			PROCEDURE UM_SELECT_MULTI_STATUT	-- v2.2

 -- appelée directement dans script lance_synchro_Siham_Ose_2020 _MANUEL.sql
====================================================================================================*/					   

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_update				number(9) := 0;
v_existe_ind 			number(9) := 0;
 
/*================== curseur cur_adr_interv ===============================*/
CURSOR cur_multi_statut_auto IS
	select *
	from OSE.UM_SYNCHRO_A_VALIDER s
	where s.annee_id = p_annee_id
		-- -- si changement validé par DRH-BGME  pour etre inséré en plus du précedent
		and tem_validation = 'AI'
		and d_validation is not null
		-- et pas encore transfere dans ose ou en cours de transfert dans ose
		and d_transfert_force is null
	;

/*========= PROG PRINCIPAL PROCEDURE UM_INIT_RIB_HORS_SEPA ===============================*/
BEGIN
	dbms_output.put_line(' ');
	dbms_output.put_line('   Lancement maj UM_SYNCHRO_A_VALIDER : ');

	FOR c1 in cur_multi_statut_auto LOOP
	
			BEGIN  -- maj d_transfert_force pour traiter la ligne en INSERTION et pas en UPDATE 
				update OSE.UM_TRANSFERT_INDIVIDU SET tem_ose_insert = 'A_INS', tem_ose_update = 'N'
				WHERE matcle = c1.matcle and annee_id = p_annee_id 
					and tem_ose_update = 'A_INS';
			EXCEPTION
				when others then
					rollback;
					dbms_output.put_line('   !!! Pb update UM_TRANSFERT_INDIVIDU pour Multi-statut auto : '||c1.matcle);
			END;
			commit;  -- v2.3
			BEGIN -- maj date fin période précédente + d_transfert_force pour ne pas traiter la ligne la prochaine fois
			update OSE.UM_SYNCHRO_A_VALIDER SET actu_date_fin_statut = new_date_fin_statut - 1,
				d_validation = sysdate, d_transfert_force = sysdate	
			WHERE id = c1.id;
			EXCEPTION
			-- when no_data_found then null;
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_SYNCHRO_A_VALIDER pour Multi-statut auto : '||' id: '||c1.id||' '||c1.matcle);
			END;			
			commit;  -- v2.3	   
			
			BEGIN -- maj date fin période précédente
				update OSE.UM_INTERVENANT SET date_fin_statut = c1.new_date_fin_statut - 1			-- v2.3 
				WHERE source_code = c1.matcle and annee_id = p_annee_id and statut_id = c1.actu_statut_id and date_deb_statut = c1.actu_date_deb_statut;
			EXCEPTION
			-- when no_data_found then null;
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_INTERVENANT pour Multi-statut auto (date_fin_statut) : '||c1.matcle||' pour statut :'||c1.actu_statut_id||'-'||c1.actu_code_statut);
			END;
			commit;  -- v2.3	   
			
	END LOOP;
	COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE OSE.UM_MAJ_INSERT_STATUT(p_siham_matricule IN VARCHAR2, p_annee_id IN number) IS
/* ===================================================================================================
			PROCEDURE UM_SELECT_MULTI_STATUT	-- v2.2

 -- appelée directement dans script lance_synchro_Siham_Ose_2020 _MANUEL.sql
====================================================================================================*/					   

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_update				number(9) := 0;
v_existe_ind 			number(9) := 0;
 
/*================== curseur cur_adr_interv ===============================*/
CURSOR cur_multi_statut_auto IS
	select *
	from OSE.UM_SYNCHRO_A_VALIDER s
	where s.annee_id = p_annee_id and s.matcle = p_siham_matricule
		-- -- si changement validé par DRH-BGME  pour etre inséré en plus du précedent
		and tem_validation = 'I'
		and d_validation is not null
		-- et pas encore transfere dans ose ou en cours de transfert dans ose
		and d_transfert_force is null
	;

/*========= PROG PRINCIPAL PROCEDURE UM_MAJ_INSERT_STATUT ===============================*/
BEGIN
	--dbms_output.put_line(' ');
	--dbms_output.put_line('   Lancement maj UM_SYNCHRO_A_VALIDER quand validation = I pour INSERT : ');

	FOR c1 in cur_multi_statut_auto LOOP
			dbms_output.put_line(c1.matcle);
			BEGIN -- maj date fin période précédente + d_transfert_force pour ne pas traiter la ligne la prochaine fois
				update OSE.UM_SYNCHRO_A_VALIDER SET actu_date_fin_statut = new_date_deb_statut-1 WHERE id = c1.id;
				commit;
			EXCEPTION
			-- when no_data_found then null;
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_SYNCHRO_A_VALIDER pour Multi-statut manuel : '||c1.matcle);
			END;
			
			BEGIN -- maj date fin période précédente
				update OSE.UM_INTERVENANT SET date_fin_statut = c1.new_date_deb_statut-1
				WHERE source_code = c1.matcle and annee_id = p_annee_id and statut_id = c1.actu_statut_id and trunc(date_deb_statut) = trunc(c1.actu_date_deb_statut);
				commit;
			EXCEPTION
			-- when no_data_found then null;
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_INTERVENANT pour Multi-statut manuel (date_fin_statut) : '||c1.matcle||' pour statut :'||c1.actu_statut_id||'-'||c1.actu_code_statut);
			END;
			
	END LOOP;
	COMMIT;
END;
/

CREATE OR REPLACE PROCEDURE OSE.UM_INSERT_UM_STATUT(p_annee_id IN number) IS
/* ===================================================================================================
    PROCEDURE UM_INSERT_UM_STATUT  -- v2.5
====================================================================================================*/   
-- A partir de UM_STATUT, créé les enregistrements pour l'année donnée en paramètre

BEGIN
	dbms_output.put_line('   - Insertion UM_STATUT pour l''annee : '||p_annee_id);
    INSERT INTO OSE.UM_STATUT(ANNEE_ID,CODE_STATUT,LIBELLE,SERVICE_STATUTAIRE
				,DEPASSEMENT
				,PLAFOND_REFERENTIEL
				,MAXIMUM_HETD
				,FONCTION_E_C
				,TYPE_INTERVENANT_ID
				,SOURCE_ID
				,SOURCE_CODE
				,ORDRE
				,NON_AUTORISE
				,PEUT_SAISIR_SERVICE
				,PEUT_CHOISIR_DANS_DOSSIER
				,PEUT_SAISIR_DOSSIER
				,PEUT_SAISIR_MOTIF_NON_PAIEMENT
				,PEUT_AVOIR_CONTRAT
				,PEUT_SAISIR_REFERENTIEL
				,PLAFOND_HC_HORS_REMU_FC
				,PLAFOND_HC_REMU_FC
				,DEPASSEMENT_SERVICE_DU_SANS_HC
				,PEUT_CLOTURER_SAISIE
				,TEM_BIATSS
				,PEUT_SAISIR_SERVICE_EXT
				,TEM_ATV
				,PROSE_LIB_STATUT)
		SELECT 
			p_annee_id
			,CODE_STATUT
			,LIBELLE
			,SERVICE_STATUTAIRE
			,DEPASSEMENT
			,PLAFOND_REFERENTIEL
			,MAXIMUM_HETD
			,FONCTION_E_C
			,TYPE_INTERVENANT_ID
			,SOURCE_ID
			,SOURCE_CODE
			,ORDRE
			,NON_AUTORISE
			,PEUT_SAISIR_SERVICE
			,PEUT_CHOISIR_DANS_DOSSIER
			,PEUT_SAISIR_DOSSIER
			,PEUT_SAISIR_MOTIF_NON_PAIEMENT
			,PEUT_AVOIR_CONTRAT
			,PEUT_SAISIR_REFERENTIEL
			,PLAFOND_HC_HORS_REMU_FC
			,PLAFOND_HC_REMU_FC
			,DEPASSEMENT_SERVICE_DU_SANS_HC
			,PEUT_CLOTURER_SAISIE
			,TEM_BIATSS
			,PEUT_SAISIR_SERVICE_EXT
			,TEM_ATV
			,PROSE_LIB_STATUT
			FROM UM_STATUT
			order by id;
		exception
			when others then
				dbms_output.put_line (p_annee_id||'   !!! Pb insert UM_STATUT - OTHERS : '||SQLERRM);
    commit;
END;
/