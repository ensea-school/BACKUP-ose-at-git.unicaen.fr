/* ====================================================================================================
	A_3_P_OSE_procedure_insert_tables_src.sql
	# Detail du connecteur PARTIE A/ SIHAM_REF : des tables de référentiel - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	PROCEDURES DE SYNCHRO DES TABLES DE REFERENTIEL : à lancer en 1ere installation ou maj de version
		synchronisation des tables UM de référentiel pour OSE MAJ en UPDATE / INSERT
			
	--- procedures ------------------ 
	OSE.UM_SYNCHRO_PAYS
	OSE.UM_SYNCHRO_DEPARTEMENT
	OSE.UM_SYNCHRO_VOIRIE
	OSE.UM_ALIM_ADRESSE_NUMERO_COMPL
	OSE.UM_SYNCHRO_STRUCTURE
	OSE.UM_SYNCHRO_GRADE
	----------------------------
	
	-- v2.1 - 03/07/20 MYP : ll_grade recup sur 39 au lieu de 40 car trop long pour ose ensuite car utf8  
	-- v2.2 - 03/12/20 MYP : V15 : UM_SYNCHRO_VOIRIE + ajout NUMERO_COMPL et VOIRIE dans adresses structure
	-- v2.3 - 28/05/21 MYP : retaillage zones adresse
	-- v2.4 - 11/06/21 MYP : raz numero_compl_code si inexistant dans OSE.ADRESSE_NUMERO_COMPL
	-- v2.5 - 05/10/21 MYP : UM_ALIM_ADRESSE_NUMERO_COMPL : prevoir retour null qd aucun enreg : commencer à 1
	-- v2.5b- 04/02/22 MYP : dblink .world
=====================================================================================================*/

CREATE OR REPLACE PROCEDURE OSE.UM_SYNCHRO_PAYS (p_source_id number) IS
/* ===================================================================================================
    PROCEDURE UM_SYNCHRO_PAYS
====================================================================================================*/                           

-- VARIABLES DE TRAITEMENT ----------------------------

v_nb_insert				number(9) := 0;
v_nb_update				number(9) := 0;
v_nb_total   			number(9) := 0;

v_id_pays               number(9);
v_ll                    varchar2(120);
v_lc                    varchar2(60);
v_temoin_ue				number(1);
v_validite_debut		date;
v_validite_fin			date;

/*================== curseur cur_pays ===============================*/
cursor cur_pays is
    select
        trim(lreg.liblon) as ll_pays
        , trim(lreg.libabr) as lc_pays
        ,case when v_europe.continent='EUROPE' then 1 else 0
        end as temoin_ue
        , trunc(reg.dtdva) as date_deb_val
        , trunc(reg.dtfva) as date_fin_val
        , trim(reg.cdcode) as source_code  -- code SIHAM
    from 
        hr.zd00@SIHAM.WORLD reg      	-- reglementation pour dept naissance
        ,hr.zd01@SIHAM.WORLD lreg     -- libelle reglementation
        ,(
            select trim(cont.cdcode) as continent, trim(cont_pays.idcoun) as code_pays
            from
                hr.zd00@SIHAM.WORLD cont
                ,hr.zd4k@SIHAM.WORLD cont_pays
            where cont.cdcode = 'EUROPE' 
                and cont.nudoss = cont_pays.nudoss
        ) v_europe
    where
        -- pays)
        reg.cdstco(+)='UIN'
        and reg.nudoss=lreg.nudoss
        and lreg.cdlang = 'F'
        and trim(reg.cdcode) = v_europe.code_pays(+)
    order by trim(reg.cdcode)
;

/*========= PROG PRINCIPAL PROCEDURE UM_SYNCHRO_PAYS ===============================*/
BEGIN
    --dbms_output.put_line('Lancement synchro pays pour OSE : ');
    FOR c1 in cur_pays LOOP

        v_id_pays := UM_EXISTE_PAYS(c1.source_code);  -- recup ID si existe deja
        IF v_id_pays <> 0 then 
        
            select LIBELLE_LONG, LIBELLE_COURT, TEMOIN_UE
                    -- v0.3 to_date
                    ,trunc(VALIDITE_DEBUT) as validite_debut
                    ,trunc(VALIDITE_FIN) as    validite_fin 
                    INTO v_ll, v_lc, v_temoin_ue, v_validite_debut, v_validite_fin
            from OSE.UM_PAYS
            where id = v_id_pays;
            
            IF ( v_ll <> c1.ll_pays 
                    or v_lc <> c1.lc_pays
                    or v_temoin_ue <> c1.temoin_ue
                    or v_validite_debut <> c1.date_deb_val
                    or v_validite_fin <> c1.date_fin_val) THEN
                begin
                    v_nb_update := v_nb_update+1 ;
                    update OSE.UM_PAYS SET 
                        LIBELLE_LONG = c1.ll_pays
                        ,LIBELLE_COURT = c1.lc_pays
                        ,TEMOIN_UE = c1.temoin_ue
                        ,VALIDITE_DEBUT = c1.date_deb_val
                        ,VALIDITE_FIN = c1.date_fin_val
                    WHERE id = v_id_pays;
                    EXCEPTION
                    -- when no_data_found then null;
                    when others then
                            rollback;
                            dbms_output.put_line(' Pb update pays : '||trim(c1.source_code));
                end;
            END IF;    
        ELSE
            BEGIN
            v_nb_insert := v_nb_insert+1 ;
            --dbms_output.put_line(' => insert dans UM_PAYS : '||c1.source_code);         
            insert into UM_PAYS(LIBELLE_LONG, LIBELLE_COURT, TEMOIN_UE,
                                        VALIDITE_DEBUT,    VALIDITE_FIN, 
                                        SOURCE_ID, SOURCE_CODE)
            values (c1.ll_pays
                ,c1.lc_pays
                ,c1.temoin_ue
                ,c1.date_deb_val
                ,c1.date_fin_val
                ,p_source_id
                ,c1.source_code
                );
            EXCEPTION
                -- when no_data_found then null;
                when others then
                        rollback;
                        dbms_output.put_line(' Pb insert pays : '||trim(c1.source_code));
            END;
        END IF;
                        
    END LOOP;
    COMMIT;
    select count(*) INTO v_nb_total from OSE.UM_PAYS;
    dbms_output.put_line(rpad(' synchro UM_PAYS  ',35,' ')||'-  nb_insert :'||v_nb_insert||' - nb update :'||v_nb_update||' - nb enreg total :'||v_nb_total);
END;
/

CREATE OR REPLACE PROCEDURE OSE.UM_SYNCHRO_DEPARTEMENT IS
/* ===================================================================================================
    PROCEDURE UM_SYNCHRO_DEPARTEMENT
====================================================================================================*/                           
-- VARIABLES DE TRAITEMENT ----------------------------

v_nb_insert			number(9) := 0;
v_nb_update			number(9) := 0;
v_nb_total			number(9) := 0;
v_code_dept			varchar2(5) := '';
v_ll				varchar2(120);
v_lc				varchar2(60);

/*================== curseur cur_departement ===============================*/
cursor cur_departement is
    select 
    '000' as source_code
    , 'UNDEF' as ll_dept
    , 'UNDEF' as lc_dept
    , 'Siham' as source
    from dual
UNION
    select
        decode(trim(reg.cdcode),'004','404', lpad(nvl(trim(reg.cdcode),'0'), 3, '0')) as source_code  -- code SIHAM spécial pour ne pas confondre avec dép. 04
        , trim(lreg.liblon) as ll_dept
        , trim(lreg.libabr) as lc_dept
        , 'Siham' as source
    from 
        hr.zd00@SIHAM.WORLD reg      -- reglementation pour dept naissance
        ,hr.zd01@SIHAM.WORLD lreg     -- libelle reglementation
    where
        reg.cdstco(+)in ('UGJ')
        and reg.nudoss=lreg.nudoss
        and lreg.cdlang = 'F'
        and trunc(sysdate) between trunc(reg.dtdva) and trunc(reg.dtfva)
order by 1
;


/*========= PROG PRINCIPAL PROCEDURE UM_SYNCHRO_DEPARTEMENT ===============================*/
BEGIN
    --dbms_output.put_line('Lancement synchro departement pour OSE : ');
    FOR c1 in cur_departement LOOP

        v_code_dept := UM_EXISTE_DEPARTEMENT(c1.source_code);
        IF v_code_dept is not null then 
        
            select trim(LIBELLE_LONG), trim(LIBELLE_COURT)
                    INTO v_ll, v_lc
            from OSE.UM_DEPARTEMENT
            where code = v_code_dept;
            
            IF ( v_ll <> c1.ll_dept 
                    or v_lc <> c1.lc_dept
				) THEN
                begin
                    v_nb_update := v_nb_update+1 ;
                    update OSE.UM_DEPARTEMENT SET 
                        LIBELLE_LONG = c1.ll_dept
                        ,LIBELLE_COURT = c1.lc_dept
                    WHERE code = v_code_dept;
                    EXCEPTION
                    -- when no_data_found then null;
                    when others then
                            rollback;
                            dbms_output.put_line(' Pb update département : '||trim(c1.source_code));
                end;
            END IF;    
        ELSE
            BEGIN
            v_nb_insert := v_nb_insert+1 ;
          
            insert into UM_DEPARTEMENT(CODE, LIBELLE_LONG, LIBELLE_COURT, SOURCE_ID, SOURCE_CODE)
            values (c1.source_code
				,c1.ll_dept
                ,c1.lc_dept
                ,c1.source
                ,c1.source_code
                );
            EXCEPTION
                -- when no_data_found then null;
                when others then
                        rollback;
                        dbms_output.put_line(' Pb insert département : '||trim(c1.source_code));
            END;
        END IF;
                        
    END LOOP;
    COMMIT;
    select count(*) INTO v_nb_total from OSE.UM_DEPARTEMENT;
    dbms_output.put_line(rpad(' synchro UM_DEPARTEMENT',35,' ')||'-  nb_insert :'||v_nb_insert||' - nb update :'||v_nb_update||' - nb enreg total :'||v_nb_total);
END;
/



CREATE OR REPLACE PROCEDURE OSE.UM_SYNCHRO_VOIRIE(p_source_id number) IS
/* ===================================================================================================
    PROCEDURE UM_SYNCHRO_VOIRIE -- v2.2 03/12/2020
====================================================================================================*/                           

-- VARIABLES DE TRAITEMENT ----------------------------

v_nb_insert				number(9) := 0;
v_nb_update				number(9) := 0;
v_nb_total   			number(9) := 0;

v_id_voirie             number(9);
v_ll                    varchar2(120);


/*================== curseur cur_voirie ===============================*/
cursor cur_voirie is
		select distinct trim(reg.cdcode) as code_voie
			, upper(trim(l_reg.liblon)) as ll_voie
		 from zd00@SIHAM.WORLD reg	-- reglementation
			, zd01@SIHAM.WORLD l_reg  -- libelle reglementation
		where cdstco = 'VNT'	-- adresse VNT ou WAM
		and reg.nudoss = l_reg.nudoss
	UNION
		select distinct trim(reg.cdcode) as code_voie
			, upper(trim(l_reg.liblon)) as ll_voie
		 from zd00@SIHAM.WORLD reg
			, zd01@SIHAM.WORLD l_reg
		where cdstco = 'WAM'
		-- code de WAM qui n existent pas en VNT car libelles pas identiques pour meme code !
		and trim(reg.cdcode) in (select distinct trim(reg.cdcode)
						   from zd00@SIHAM.WORLD reg
							where cdstco = 'WAM'
						   minus
							 select distinct trim(reg.cdcode)
						   from zd00@SIHAM.WORLD reg
							where cdstco = 'VNT'
							)
		and reg.nudoss = l_reg.nudoss
	order by code_voie
;


/*========= PROG PRINCIPAL PROCEDURE UM_SYNCHRO_VOIRIE ===============================*/
BEGIN
    --dbms_output.put_line('Lancement synchro voirie pour OSE : ');
    FOR c1 in cur_voirie LOOP

        v_id_voirie := UM_EXISTE_VOIRIE(c1.code_voie);  -- recup ID si existe deja
        IF v_id_voirie <> 0 then 
        
            select LIBELLE INTO v_ll
            from OSE.UM_VOIRIE
            where id = v_id_voirie;
            
            IF v_ll <> c1.ll_voie THEN
                begin
                    v_nb_update := v_nb_update+1 ;
                    update OSE.UM_VOIRIE SET 
                        LIBELLE = c1.ll_voie
                    WHERE id = v_id_voirie;
                    EXCEPTION
                    -- when no_data_found then null;
                    when others then
                            rollback;
                            dbms_output.put_line(' Pb update voirie : '||trim(c1.code_voie));
                end;
            END IF;    
        ELSE
            BEGIN
            v_nb_insert := v_nb_insert+1 ;
            --dbms_output.put_line(' => insert dans UM_VOIRIE : '||c1.code_voie);         
            insert into UM_VOIRIE(CODE, LIBELLE, SOURCE_ID, SOURCE_CODE)
            values (c1.code_voie
				,c1.ll_voie
                ,p_source_id
                ,c1.code_voie
                );
            EXCEPTION
                -- when no_data_found then null;
                when others then
                        rollback;
                        dbms_output.put_line(' Pb insert voirie : '||trim(c1.code_voie));
            END;
        END IF;
                        
    END LOOP;
    COMMIT;
    select count(*) INTO v_nb_total from OSE.UM_VOIRIE;
    dbms_output.put_line(rpad(' synchro UM_VOIRIE  ',35,' ')||'-  nb_insert :'||v_nb_insert||' - nb update :'||v_nb_update||' - nb enreg total :'||v_nb_total);
END;
/

CREATE OR REPLACE PROCEDURE OSE.UM_ALIM_ADRESSE_NUMERO_COMPL IS
/* ===================================================================================================
			PROCEDURE UM_ALIM_ADRESSE_NUMERO_COMPL
====================================================================================================*/
-- VARIABLES DE TRAITEMENT ----------------------------

v_nb_insert				number(9) := 0;
v_nb_total   			number(9) := 0;
v_new_id				number(9) := 0;

/*================== curseur cur_numero_compl ===============================*/
cursor cur_numero_compl is
 select distinct trim(reg.cdcode) as code_adr_num_compl
            , trim(l_reg.liblon) as ll_adr_num_compl
         from zd00@SIHAM.WORLD reg    -- reglementation
            , zd01@SIHAM.WORLD l_reg  -- libelle reglementation
        where cdstco = 'WAN'    
        and reg.nudoss = l_reg.nudoss
        and trim(reg.cdcode) in 
		(	-- code pas deja ixistant dans table livree
		    select distinct trim(reg.cdcode) 
			from zd00@SIHAM.WORLD reg    -- reglementation
				,zd01@SIHAM.WORLD l_reg  -- libelle reglementation
			where cdstco = 'WAN'    
			and reg.nudoss = l_reg.nudoss
			minus 
			select code from OSE.adresse_numero_compl
		)
		and not exists
		( select code from OSE.ADRESSE_NUMERO_COMPL
			where code = trim(reg.cdcode)
		)
 order by trim(reg.cdcode)
;

/*========= PROG PRINCIPAL PROCEDURE UM_ALIM_ADRESSE_NUMERO_COMPL ===============================*/
BEGIN
	-- pour l'instant V15 de base : table livrée par Caen avec 4 enreg Bis, Ter, Quater, C Quinquies
	--	pas synchronisable dans Ose donc j'ajoute simplement les codes manquants / SIHAM
	-- a lancer une seule fois lors dela V15
    FOR c1 in cur_numero_compl LOOP

           BEGIN
            v_nb_insert := v_nb_insert+1 ;
			
			select decode(max(id)+1,null,1, max(id)+1) into v_new_id 		-- v2.5 - 05/10/21 
			from OSE.adresse_numero_compl;
			
            insert into OSE.ADRESSE_NUMERO_COMPL(ID, CODE, LIBELLE)
            values ( v_new_id
				,c1.code_adr_num_compl
                ,c1.ll_adr_num_compl
                );
            EXCEPTION
                -- when no_data_found then null;
                when others then
                        rollback;
                        dbms_output.put_line(' Pb insert adresse_numero_compl : '||trim(c1.code_adr_num_compl));
            END;
                        
    END LOOP;
    COMMIT;
    select count(*) INTO v_nb_total from OSE.ADRESSE_NUMERO_COMPL;
    dbms_output.put_line(rpad(' synchro OSE.ADRESSE_NUMERO_COMPL  ',35,' ')||'-  nb_insert :'||v_nb_insert||' - nb enreg total :'||v_nb_total);
END;
/



CREATE OR REPLACE PROCEDURE OSE.UM_SYNCHRO_STRUCTURE(p_source_id number, p_date_systeme date) IS
/* ===================================================================================================
			PROCEDURE UM_SYNCHRO_STRUCTURE
====================================================================================================*/							   

-- VARIABLES DE TRAITEMENT ----------------------------
v_structure_mere		varchar2(100) := '';		-- ##A_PERSONNALISER_CHOIX_SIHAM## : table UM_PARAM_ETABL
v_org_rattach			varchar2(100) := '';
v_uo_a_exclure			varchar2(100) := '';

v_nb_insert				number(9) 	:= 0;
v_nb_update				number(9) 	:= 0;
v_nb_total				number(9) 	:= 0;

v_id_structure			number(10) 	:= 0;
v_ll					varchar2(70);  		-- norm sur 60 mais obligé sinon erreur buffer -- car accentués en utf8 prennent plus de place
v_lc					varchar2(26);
v_type_id				number(1);
v_etabl					number(9);
v_niveau				number(1);
v_contact_pj			varchar2(256);
v_aff_adr				number(1);
v_enseignement			number(1);

v_id_adr_structure		number(9);
v_principale			number(1);
v_telephone				varchar2(20);
v_no_voie				varchar2(10);
v_nom_voie				varchar2(60);
v_localite				varchar2(40);			-- v2.3 - 28/05/2021
v_code_postal			varchar2(15);
v_ville					varchar2(26);
v_pays_code_insee		varchar2(3);

v_numero_compl_code		varchar2(5) := '';		-- v2.2 - 03/12/2020
v_voirie_code			varchar2(5) := '';


/*================== curseur cur_structure ===============================*/
cursor cur_structure_mere is
	SELECT distinct
	-- ##A_PERSONNALISER_CHOIX_SIHAM## -- on veut le libelle Siham avant le tiret sinon la 2eme ligne suffit avec ll_uo
	substr(uo_ose.ll_uo,1, case when instr(uo_ose.ll_uo,'-')-2 <0 then 60 
							    else instr(uo_ose.ll_uo,'-')-2 
						   end  
	) as ll_uo
	--uo_ose.ll_uo as ll_uo 	-- LIBELLE_LONG SANS MODIF
	,uo_ose.lc_uo as lc_uo		-- LIBELLE_COURT
	,0 											as type_id 				-- TYPE_ID
	,0											as etablissement_id		-- ETABLISSEMENT_ID ?  -- quel Id pour UM
	,1											as niveau				-- NIVEAU
	,p_source_id								as source_id 			-- SOURCE_ID id correspondant à SIHAM
	,uo_ose.code_uo_niveau_voulu				as c_uo					-- SOURCE_CODE
	,''											as contact_pj			-- CONTACT_PJ
	,0											as aff_adr_contrat 		-- AFF_ADRESSE_CONTRAT
	,case when UM_UO_TYPE_ENS(uo_ose.code_uo_niveau_voulu) is null then 0
		when UM_UO_TYPE_ENS(uo_ose.code_uo_niveau_voulu) = 1 then 1
		else 0
	end as enseignement	-- ENSEIGNEMENT
	,'N'							 			as tem_struct_manu		-- v1.11
	FROM
	(	
		select distinct   --- structure mère université dans Siham
		uo.nudoss         					as nudoss
		,substr(trim(l_uo.lboulg),1,60)     as ll_uo                 
		,substr(trim(l_uo.lboush),1,25)     as lc_uo
		,trim(uo.idou00)					as code_uo_niveau_voulu --v1.9
		from 
			hr.ze00@SIHAM.WORLD uo               -- uo 
			,hr.ze01@SIHAM.WORLD l_uo            -- libelles_uo    
			,hr.ze0a@SIHAM.WORLD h_situ          -- histo_situations
			,hr.zev2@SIHAM.WORLD rattach         -- rattachement u mixte
		where trim(uo.idou00) = trim(v_structure_mere)
			and uo.idos00 = 'HIE'
            and trunc(uo.dtef00) <= p_date_systeme
			and uo.nudoss = l_uo.nudoss
			and uo.nudoss = h_situ.nudoss
			and uo.nudoss = rattach.nudoss(+)
			and l_uo.cdlang = 'F'
            and p_date_systeme between trunc(h_situ.dtef00) and trunc(h_situ.datxxx-1)
			and h_situ.stou01 = 'ACT'
            and p_date_systeme between trunc(rattach.datdeb(+)) and trunc(rattach.datfin(+))
			and (rattach.nudoss is null or rattach.idesta = v_org_rattach)
	) uo_ose
;

cursor cur_structure is
	SELECT distinct
	-- ##A_PERSONNALISER_CHOIX_SIHAM## si vous avez besoin de libellé en dur sinon la 2eme ligne suffit avec ll_uo
	decode(uo_ose.code_uo_niveau_voulu,'HDR0000003','Direction de la Recherche et des Etudes Doctorales',
			'HEA0000003','Institut d''Administration des Entreprises',
			'HEB0000003','Institut Universitaire de Technologie de Béziers',
			'HED0000003','U.F.R de Droit et Science Politique',
			'HEE0000003','U.F.R d''Economie',
			'HEH0000003','U.F.R des Sciences de Montpellier',
			'HEI0000003','U.F.R d''Education',
			'HEJ0000003','Institut de Préparation à l''Administration Générale',
			'HEL0000003','Institut Montpellier Management',
			'HEM0000003','U.F.R de Médecine',
			'HEO0000003','U.F.R d''Odontologie',
			'HEP0000003','U.F.R des Sciences Pharmaceutiques',
			'HES0000003','Sciences et Techniques des Activités Physiques et Sportives',
			'HET0000003','Institut Universitaire de Technologie de Montpellier-Sète',
			'HEU0000003','Institut Universitaire de Technologie de Nîmes',
			'HEX0000003','Ecole Polytechnique Universitaire de Montpellier',
			'HS10000003','Service Commun de la Formation Continue',
			'HS20000003','SCUIO-IP',
			'HS50000003','Service Universitaire des Activités Physiques et Sportives',
			'HDE0000003','Formation et Vie Universitaire',							-- v1.7 - 08/04/2019
			'HXC0BB0006','Association sportive de l''Université de Montpellier',	-- v1.8b- 16/04/2019 - MYP - ajout structure ASUM HXC0BB0006
			substr(uo_ose.ll_uo,1, case when instr(uo_ose.ll_uo,'-')-2 <0 then 60 else instr(uo_ose.ll_uo,'-')-2 end) --v1.9
	) as ll_uo 					-- LIBELLE_LONG EN DUR
	--uo_ose.ll_uo			 	-- LIBELLE_LONG COMPLET COMME SIHAM
	,decode(uo_ose.code_uo_niveau_voulu, 'HDE0000003','FVU','HXC0BB0006','ASUM', uo_ose.lc_uo ) as lc_uo  -- ##A_PERSONNALISER_CHOIX_SIHAM##
	-- uo_ose.lc_uo 			-- LIBELLE_COURT COMPLET COMME SIHAM
	,0 											as type_id 				-- TYPE_ID
	/*
	,case when UM_UO_TYPE_ENS_OSE(uo_ose.code_uo_niveau_voulu) is null then 'DIR'
		when UM_UO_TYPE_ENS_OSE(uo_ose.code_uo_niveau_voulu) = 1 then 'ENS'
		else 'DIR'
	end as type_uo		-- TYPE_ID	
	*/
	,0											as etablissement_id		-- ETABLISSEMENT_ID
	,uo_ose.niveau								as niveau				-- NIVEAU
	,p_source_id								as source_id 			-- SOURCE_ID id correspondant à SIHAM
	,uo_ose.code_uo_niveau_voulu				as c_uo					-- SOURCE_CODE
	,''											as contact_pj			-- CONTACT_PJ
	,0											as aff_adr_contrat 		-- AFF_ADRESSE_CONTRAT
	,case when UM_UO_TYPE_ENS(uo_ose.code_uo_niveau_voulu) is null then 0
		when UM_UO_TYPE_ENS(uo_ose.code_uo_niveau_voulu) = 1 then 1
		else 0
	end as enseignement	-- ENSEIGNEMENT
	,'N'							 			as tem_struct_manu		-- v1.11
	FROM
	(	
		-- ##A_PERSONNALISER_CHOIX_SIHAM## -- structures composantes niveau 2 pour ose = niveau 3 siham
		select distinct
		OSE.UM_UO_NUDOSS(trim(uo_niv.idou00)) 	as nudoss --v1.9
		,substr(trim(l_uo_niv.lboulg),1,60) 	as ll_uo 				
		,substr(trim(l_uo_niv.lboush),1,25) 	as lc_uo				 			
		,trim(uo_niv.idou00)					as code_uo_niveau_voulu  --v1.9

		,decode(trim(uo.idou00), 'HXC0BB0006',trim(UM_CODE_UO_NIVEAU_DESSUS(trim(uo_niv.idou00),4)),
				trim(UM_CODE_UO_NIVEAU_DESSUS(trim(uo_niv.idou00),2))
				) as uo_mere  					--v1.9  -- UO mere 2 niveaux au dessus sauf pour 'HXC0BB0006' 4 niveaux au dessus
		-- trim(UM_CODE_UO_NIVEAU_DESSUS(trim(uo_niv.idou00),2)) as uo_mere
		,2										as niveau
		from 
			hr.ze00@SIHAM.WORLD uo  			-- uo 
			,hr.ze01@SIHAM.WORLD l_uo  		-- libelles_uo	
			,hr.ze0a@SIHAM.WORLD h_situ 	 	-- histo_situations
			,hr.zev2@SIHAM.WORLD rattach 	 	-- rattachement u mixte
			,hr.ze00@SIHAM.WORLD uo_niv 		-- uo_niveau_voulu
			,hr.ze01@SIHAM.WORLD l_uo_niv		-- libelles_uo_niveau_voulu
		where uo.idos00 = 'HIE'
			and trim(uo.idou00) not in (v_uo_a_exclure)
			and OSE.UM_NIVEAU_UO(trim(uo_niv.idou00)) >= 3		-- composante/direction dans siahm au niveau 3
			and trunc(uo.dtef00) <= p_date_systeme
			and uo.nudoss = l_uo.nudoss
			and uo.nudoss = h_situ.nudoss
			and uo.nudoss = rattach.nudoss(+)
			and l_uo.cdlang = 'F'
			and p_date_systeme between trunc(h_situ.dtef00) and trunc(h_situ.datxxx-1)
			and h_situ.stou01 = 'ACT'
			and p_date_systeme between trunc(rattach.datdeb(+)) and trunc(rattach.datfin(+)) 
			and (rattach.nudoss is null or rattach.idesta = v_org_rattach)
			-- ##A_PERSONNALISER_CHOIX_OSE## structures retenues pour OSE = choix du niveau d'UO /branches : choix_niveaux_UO_pour_OSE.xlsx
			-- cf fonction UM_AFFICH_UO_SUP
			and trim(uo_niv.idou00) = OSE.UM_AFFICH_UO_SUP(trim(uo.idou00))  --v1.9
			and uo_niv.nudoss = l_uo_niv.nudoss
	) uo_ose
	UNION		-- v1.11- 12/09/2019 - MYP - ajout structure EDTTSD - spéciale OREC -- ##A_PERSONNALISER_CHOIX_SIHAM##
		select 'Ecole doctorale Territoires, Temps, Société et Développement' as ll_uo
			,'ED TTSD' 		as lc_uo
			,0				as type_id
			,0				as etablissement_id
			,2				as niveau				-- NIVEAU
			,p_source_id	as source_id 			-- SOURCE_ID id correspondant à SIHAM
			,'EDTTSD'		as c_uo					-- SOURCE_CODE
			,''				as contact_pj			-- CONTACT_PJ
			,0				as aff_adr_contrat 		-- AFF_ADRESSE_CONTRAT
			,0 				as enseignement			-- ENSEIGNEMENT
			,'O'			as tem_struct_manu
		from dual
	ORDER BY 5, 4  --v1.11
;

/*================== curseur cur_adr_structure ===============================*/
cursor cur_adr_structure is
	SELECT   -- v2.2 03/12/2020 : 1 select au dessus pour recup id
	V_ADR_STR.c_uo
	,V_ADR_STR.date_deb
	,V_ADR_STR.structure_id
	,V_ADR_STR.principale
	,V_ADR_STR.telephone
	,V_ADR_STR.no_voie
	--,UM_EXISTE_ADR_NUM_COMPL(V_ADR_STR.NUMERO_COMPL) 	as NUMERO_COMPL_ID  -- v2.2 03/12/2020
	,V_ADR_STR.NUMERO_COMPL 							as NUMERO_COMPL_CODE	-- v2.2 26/01/2021
	,UM_EXISTE_VOIRIE_LIB(V_ADR_STR.VOIRIE) 			as VOIRIE_CODE		-- v2.2 26/01/2021
	,V_ADR_STR.nom_voie
	,V_ADR_STR.localite
	,V_ADR_STR.code_postal
	,V_ADR_STR.ville
	--,UM_EXISTE_PAYS(V_ADR_STR.pays_code_insee)			as PAYS_ID			-- v2.2 03/12/2020
	,V_ADR_STR.pays_code_insee							as PAYS_CODE_INSEE
	,V_ADR_STR.pays_libelle
	FROM 
	(select uo.source_code					as c_uo
		, str_adr.dtbg00					as date_deb
		,uo.id              				as structure_id
		,1		            				as principale
		,trim(telephone)    				as telephone
		,trim(substr(str_adr.zonadb,1,4))	as no_voie
		--,trim(substr(str_adr.zonadb,5,2))   as NUMERO_COMPL					-- v2.2 03/12/2020
		,compl.code							as NUMERO_COMPL					-- v2.4 11/06/2020
        ,trim(substr(replace(trim(substr(zonadb,7,32)),'.','') , 1, instr(trim(substr(zonadb,7,32)),' ')-1)) as VOIRIE  -- v2.2 03/12/2020
		,trim(substr(trim(substr(zonadb,7,32)), instr(trim(substr(zonadb,7,32)),' ')+1,length(trim(substr(zonadb,7,32)) ))) as nom_voie  -- v2.2 03/12/2020

		,trim(str_adr.zonada)				as localite 					-- batiment  -- v2.3 - 28/05/2021
		,trim(str_adr.cdpost)				as code_postal
		,trim(substr(str_adr.zonadd,7,32))	as ville
		,trim(str_adr.cdpays)				as pays_code_insee				-- v2.3 - 28/05/2021
		,substr(trim(pays.libelle_court),1,30)	as pays_libelle					-- v2.3 - 28/05/2021
		,row_number() over (partition by uo.source_code order by  str_adr.dtbg00 desc) as rnum
	from OSE.UM_STRUCTURE uo
		, hr.ze00@SIHAM.WORLD str
		, hr.ze0f@SIHAM.WORLD str_adr
		, ( select distinct str_tel.nudoss
				--, trim(str_tel.txadr0) as type_tel, trim(str_tel.nbad00) as num_tel
				,substr(listagg(trim(str_tel.nbad00),' - ') within group (order by trim(str_tel.txadr0)),1,20) as telephone
			from hr.zef9@SIHAM.WORLD str_tel
			where  str_tel.txadr0 in ('TPE','TPR','TPS',' PPE','PPR')
			group by str_tel.nudoss
		) str_tel
		,UM_PAYS pays
		,OSE.ADRESSE_NUMERO_COMPL compl		-- v2.4 11/06/2020
	where uo.tem_struct_manu <> 'O'
		and trim(uo.source_code) = trim(str.idou00)
		and str.nudoss = str_adr.nudoss
		and str_adr.txadr0 = 'POS'
		and cdpays = pays.source_code(+)
		and str_adr.nudoss = str_tel.nudoss(+)
		and upper(trim(substr(str_adr.zonadb,5,2))) = compl.code(+)		-- v2.4 11/06/2020
	UNION -- v1.11- 12/09/2019 - MYP - ajout structure EDTTSD - spéciale OREC -- ##A_PERSONNALISER_CHOIX_SIHAM##
		select trim(uo.source_code) as c_uo
            , to_date('01/01/2015','DD/MM/YYYY')
            ,(select ID from OSE.UM_STRUCTURE where source_code = trim(uo.source_code))
            , 1          as PRINCIPALE
            , ''         as TELEPHONE
            , '163'      as no_voie
			, '' 		 as NUMERO_COMPL			-- v2.2 03/12/2020
			, 'RUE' 	 as VOIRIE					
            , 'AUGUSTE BROUSSONNET' 		as nom_voie
            , ''         					as localite
            , '34090'     					as code_postal
            , 'MONTPELLIER'           		as ville
            , 'FRA'                		    as pays_code_insee
            , 'France'                		as pays_libelle
            , 1								as rnum
        from OSE.UM_STRUCTURE uo
		where uo.tem_struct_manu = 'O'		-- v1.11
	) V_ADR_STR
	order by 1, 2  --v1.11
; 

/*========= PROG PRINCIPAL PROCEDURE UM_SYNCHRO_STRUCTURE ===============================*/
BEGIN
	-- ##A_PERSONNALISER_CHOIX_SIHAM## : table UM_PARAM_ETABL
	select trim(valeur) INTO v_structure_mere 	from UM_PARAM_ETABL where code = 'C_STRUCTURE_MERE'; 
	select trim(valeur) INTO v_org_rattach 		from UM_PARAM_ETABL where code = 'C_ORG_RATTACH'; 
	select trim(valeur) INTO v_uo_a_exclure 	from UM_PARAM_ETABL where code = 'C_UO_A_EXCLURE'; 

	-- dbms_output.put_line('Lancement import structure mère');
	FOR c1 in cur_structure_mere LOOP

		v_id_structure := UM_EXISTE_STRUCTURE(c1.c_uo);
		IF v_id_structure = 0 then
			BEGIN
			-- la structure mere n'existe pas
			v_nb_insert := v_nb_insert+1 ;
			
			insert into UM_STRUCTURE(LIBELLE_LONG, LIBELLE_COURT, TYPE_ID, ETABLISSEMENT_ID, NIVEAU, SOURCE_ID, SOURCE_CODE
					, CONTACT_PJ, AFF_ADRESSE_CONTRAT, ENSEIGNEMENT, tem_struct_manu)		-- v1.11
			values (c1.ll_uo
				,c1.lc_uo
				,c1.type_id
				,c1.etablissement_id
				,c1.niveau
				,c1.source_id
				,c1.c_uo  -- SOURCE CODE
				,c1.contact_pj
				,c1.aff_adr_contrat
				,c1.enseignement
				,c1.tem_struct_manu		-- v1.11
				);
			EXCEPTION
				-- when no_data_found then null;
				when others then
						rollback;
						dbms_output.put_line(' Pb insert UM_STRUCTURE MERE : '||trim(c1.c_uo));
			END;
			COMMIT;	
		END IF;		
	END LOOP;
	
	-- dbms_output.put_line('Lancement import structures niveau composantes/directions');
	FOR c1 in cur_structure LOOP

		v_id_structure := UM_EXISTE_STRUCTURE(c1.c_uo);
		IF v_id_structure <> 0 then 
			-- la structure existe deja : update  -- v2.2 03/12/2020
			select substr(trim(LIBELLE_LONG),1,60), trim(LIBELLE_COURT), TYPE_ID, ETABLISSEMENT_ID, NIVEAU, CONTACT_PJ, AFF_ADRESSE_CONTRAT, ENSEIGNEMENT
					into v_ll, v_lc, v_type_id, v_etabl, v_niveau, v_contact_pj, v_aff_adr, v_enseignement
			from OSE.UM_STRUCTURE
			where id = v_id_structure;
			
			IF ( trim(v_ll) <> trim(c1.ll_uo) or trim(v_ll) is null --v1.9
					or v_lc <> c1.lc_uo
					or v_type_id <> c1.type_id
					or v_etabl <> c1.etablissement_id
					or v_niveau <> c1.niveau
					or v_contact_pj <> c1.contact_pj
					or v_aff_adr <> c1.aff_adr_contrat
					or v_enseignement <> c1.enseignement
				) THEN
				begin
					v_nb_update := v_nb_update+1 ;
					update OSE.UM_STRUCTURE SET 
						LIBELLE_LONG = c1.ll_uo
						,LIBELLE_COURT = c1.lc_uo
						,TYPE_ID = c1.type_id
						,ETABLISSEMENT_ID = c1.etablissement_id
						,NIVEAU = c1.niveau
						,SOURCE_ID = c1.source_id
						,CONTACT_PJ = c1.contact_pj
						,AFF_ADRESSE_CONTRAT = c1.aff_adr_contrat
						,ENSEIGNEMENT = c1.enseignement
					WHERE id = v_id_structure;
					EXCEPTION
					-- when no_data_found then null;
					when others then
							rollback;
							dbms_output.put_line(' Pb update UM_STRUCTURE : '||trim(c1.c_uo));
				end;
				commit;
			END IF;	

		ELSE
			BEGIN
			-- la structure n'existe pas
			v_nb_insert := v_nb_insert+1 ;
			
			insert into UM_STRUCTURE(LIBELLE_LONG, LIBELLE_COURT, TYPE_ID, ETABLISSEMENT_ID, NIVEAU, SOURCE_ID, SOURCE_CODE
					, CONTACT_PJ, AFF_ADRESSE_CONTRAT, ENSEIGNEMENT, tem_struct_manu)		-- v1.11
			values (c1.ll_uo
				,c1.lc_uo
				,c1.type_id
				,c1.etablissement_id
				,c1.niveau
				,c1.source_id
				,c1.c_uo  -- SOURCE CODE
				,c1.contact_pj
				,c1.aff_adr_contrat
				,c1.enseignement
				,c1.tem_struct_manu		-- v1.11
				);
			EXCEPTION
				-- when no_data_found then null;
				when others then
						rollback;
						dbms_output.put_line(' Pb insert UM_STRUCTURE : '||trim(c1.c_uo));
			END;
			COMMIT;	
			-- DANS OSE 1 SEUL NIVEAU DE STRUCTURE (niveau 2) RATTACHEE DIRECTEMENT A STRUCTURE MERE (niveau 1)
			update OSE.UM_STRUCTURE str1 set str1.PARENTE_ID = 
				(select id
				 from OSE.UM_STRUCTURE str2
				 where trim(str2.source_code) = v_structure_mere
				)
			;
			commit;
			-- maj id niveau 2
			update OSE.UM_STRUCTURE set STRUCTURE_NIV2_ID = ID
			where niveau = 2;
			commit;
		END IF;		
	END LOOP;

	select count(*) INTO v_nb_total from OSE.UM_STRUCTURE;
	dbms_output.put_line(rpad(' synchro UM_STRUCTURE',35,' ')||'-  nb_insert :'||v_nb_insert||' - nb update :'||v_nb_update||' - nb enreg total :'||v_nb_total);

	/*================================================================================*/
	dbms_output.put_line('Lancement maj des adresses de structures pour OSE : ');
	v_nb_update := 0;
	v_nb_insert := 0;
	
	FOR c2 in cur_adr_structure LOOP

		v_id_structure := UM_EXISTE_STRUCTURE(c2.c_uo);
		v_id_adr_structure := UM_EXISTE_ADR_STRUCTURE(v_id_structure);
		
		IF v_id_adr_structure <> 0 then 
			-- v2.2 - 03/12/2020
			select PRINCIPALE, TELEPHONE, NO_VOIE, NOM_VOIE, LOCALITE, CODE_POSTAL, VILLE, PAYS_CODE_INSEE, NUMERO_COMPL_CODE, VOIRIE_CODE
					INTO v_principale, v_telephone, v_no_voie, v_nom_voie, v_localite, v_code_postal, v_ville, v_pays_code_insee, v_numero_compl_code, v_voirie_code
			from OSE.UM_ADRESSE_STRUCTURE
			where structure_id = v_id_structure;
			
			IF ( v_principale <> c2.principale 
					or v_telephone <> c2.telephone
					or v_no_voie <> c2.no_voie
					or v_nom_voie <> c2.nom_voie
					or v_localite <> c2.localite
					or v_code_postal <> c2.code_postal
					or v_ville <> c2.ville
					or v_pays_code_insee <> c2.pays_code_insee
					or (v_numero_compl_code <> c2.numero_compl_code or (v_numero_compl_code is null and c2.numero_compl_code is not null) ) 	-- v2.2 - 19/01/2021
					or (v_voirie_code <> c2.voirie_code or (v_voirie_code is null and c2.voirie_code is not null))								-- v2.2 - 19/01/2021
				) THEN
				begin
					v_nb_update := v_nb_update+1 ;
					update OSE.UM_ADRESSE_STRUCTURE SET 
						PRINCIPALE = c2.principale
						,TELEPHONE = c2.telephone
						,NO_VOIE = c2.no_voie
						,NOM_VOIE = c2.nom_voie
						,LOCALITE = c2.localite
						,CODE_POSTAL = c2.code_postal
						,VILLE = c2.ville
						,PAYS_CODE_INSEE = c2.pays_code_insee
						,PAYS_LIBELLE = c2.pays_libelle
						,NUMERO_COMPL_CODE = c2.numero_compl_code		-- v2.2 - 03/12/2020
						,VOIRIE_CODE = c2.voirie_code
					WHERE structure_id = v_id_structure;
					EXCEPTION
					when no_data_found then 
							rollback;
							dbms_output.put_line(' Pb update UM_ADRESSE_STRUCTURE - no data found : '||trim(c2.c_uo));
					when others then
							rollback;
							dbms_output.put_line(' Pb update UM_ADRESSE_STRUCTURE - others : '||trim(c2.c_uo));
				end;
				commit;
			END IF;	

		ELSE
			BEGIN
			-- l'adresse de la structure n'existe pas
			v_nb_insert := v_nb_insert+1 ;
			
			insert into OSE.UM_ADRESSE_STRUCTURE(STRUCTURE_ID, PRINCIPALE, TELEPHONE, NO_VOIE, NOM_VOIE, LOCALITE, CODE_POSTAL, VILLE, PAYS_CODE_INSEE, PAYS_LIBELLE, SOURCE_ID, SOURCE_CODE
												, NUMERO_COMPL_CODE, VOIRIE_CODE) -- v2.1 -- v2.2 - 03/12/2020
			values (v_id_structure
					,c2.principale
					,c2.telephone
					,c2.no_voie
					,c2.nom_voie
					,c2.localite
					,c2.code_postal
					,c2.ville
					,c2.pays_code_insee
					,c2.pays_libelle
					,p_source_id
					,c2.c_uo
					,c2.numero_compl_code  			-- v2.2 - 03/12/2020
					,c2.voirie_code
				);
			EXCEPTION
				-- when no_data_found then null;
				when others then
						rollback;
						dbms_output.put_line(' Pb insert UM_ADRESSE_STRUCTURE : '||trim(c2.c_uo)||' structure_id : '||v_id_structure);
			END;
			COMMIT;	
			

		END IF;		
	END LOOP;
	-- v1.8b
	
	-- Au cas ou : PURGE DES ADRESSES ERRONEES pointant sur um_structure inexistante
	delete from OSE.um_adresse_structure where structure_id not in (select id from OSE.um_structure);
	
	select count(*) INTO v_nb_total from OSE.UM_ADRESSE_STRUCTURE;
	dbms_output.put_line(rpad(' synchro UM_ADRESSE_STRUCTURE',35,' ')||'-  nb_insert :'||v_nb_insert||' - nb update :'||v_nb_update||' - nb enreg total :'||v_nb_total);
END;
/


CREATE OR REPLACE PROCEDURE OSE.UM_SYNCHRO_GRADE(p_source_id number, p_date_systeme date) IS
/* ===================================================================================================
			PROCEDURE UM_SYNCHRO_GRADE
##A_PERSONNALISER_CHOIX_OSE## : table des grades OSE contient grade pour les TITU + statut siham pour les CTRL et HEBERGES
====================================================================================================*/

-- VARIABLES DE TRAITEMENT ----------------------------
v_nb_insert_gr			number(9) := 0;
v_nb_update_gr			number(9) := 0;
v_nb_total_gr			number(9) := 0;
v_nb_insert_cor			number(9) := 0;
v_nb_update_cor			number(9) := 0;
v_nb_total_cor			number(9) := 0;
v_nb_insert_pip			number(9) := 0;
v_nb_update_pip			number(9) := 0;
v_nb_total_pip			number(9) := 0;

v_id_corps				number(9);
v_id_grade				number(9);
v_id					number(9);
v_ll					varchar2(120);
v_lc					varchar2(60);
v_corps_grade			number(9);

/*================== CURSEURS ===============================*/
/*------------ curseur cur_corps ---------*/
cursor cur_corps is
	select trim(reg.cdcode) as source_code  -- code SIHAM corps
        ,substr(ltrim(lreg.liblon),1,39) as ll_corps
        ,trim(lreg.libabr) as lc_corps
        ,reg.dtdva
        ,reg.dtfva 
        ,reg.teregx
        from hr.zd00@SIHAM.WORLD reg,        -- reglementation
            hr.zd01@SIHAM.WORLD lreg        -- libelle reglementation
        where
        -- corps
        reg.cdstco = 'HJV'

		-- v0.5 test des dates et pas statut actif		
        and p_date_systeme >= trunc(reg.dtdva)
        and (p_date_systeme <= trunc(reg.dtfva) or reg.dtfva is null)
        and reg.nudoss = lreg.nudoss
		and trim(reg.cdcode) <> '000'
	union -- SPECIAL POUR LES TIT STAGI : corps par défaut si grade siham pointe sur corps pas connu dans ose (vieux codes) Siham pas repris   ##A_PERSONNALISER_CHOIX_SIHAM## 
		select 'NC', 'Non Corr. grade-corps Siham', 'Non Corr. Siham', trunc(sysdate), trunc(sysdate),'A'
		from dual
	union  -- v1.8 - 11/04/2019 -- SPECIAL POUR LES NON TITU : COMME GRADE = STATUT Siham on pointe sur un corps par défaut en dur  ##A_PERSONNALISER_CHOIX_SIHAM## 
		select 'STSP', 'STATUT Non Titulaire Permanent', 'Statut Non Titu Perm', trunc(sysdate), trunc(sysdate),'A'
		from dual
	union
		select 'STSV', 'STATUT Non Titulaire Vacataire', 'Statut Non Titu Vac', trunc(sysdate), trunc(sysdate),'A'
		from dual
	order by 1
;

/*------------ curseur cur_grade ---------*/
cursor cur_grade is
	select trim(reg.cdcode) as source_code  -- code SIHAM grade
        ,substr(ltrim(lreg.liblon),1,39) as ll_grade
        ,trim(lreg.libabr) as lc_grade
        ,trim(gr_car.corps) as corps
        ,reg.dtdva
        ,reg.dtfva
        ,case when UM_EXISTE_CORPS(trim(gr_car.corps)) = 0 then (select id from um_corps where source_code = 'NC')
            else UM_EXISTE_CORPS(trim(gr_car.corps))
        end as id_corps
        from hr.zd00@SIHAM.WORLD reg,        -- reglementation pour grades corps
            hr.zd01@SIHAM.WORLD lreg,        -- libelle reglementation
            hr.zd63@SIHAM.WORLD gr_car       -- caracteristiques du grade 
        where
        -- grades
        reg.cdstco = 'HJB'    
        and reg.nudoss = lreg.nudoss
		-- v0.5 test des dates et pas statut actif
        and p_date_systeme >= trunc(reg.dtdva)
        and (p_date_systeme <= trunc(reg.dtfva) or reg.dtfva is null)
        and reg.nudoss = gr_car.nudoss
        and trim(reg.cdcode) <> '0000'
	order by trim(reg.cdcode)
;

/*------------ curseur cur_statut_pip ---------*/
cursor cur_statut_pip is
		--- le code STATUTS SIHAM correspond soit a un PERMANENT soit a un VACATAIRE ------------------------------
		select trim(reg.cdcode) as source_code  -- code SIHAM statut_pip
		,substr(ltrim(lreg.liblon),1,39) as ll_pip
		,trim(lreg.libabr) as lc_pip
		,reg.dtdva
		,reg.dtfva 
		,reg.teregx
		-- v2.1 30/11/20 ##A_PERSONNALISER_CHOIX_SIHAM## : voir fonction UM_EST_CTR_PERMANENT
		,case when UM_EST_CTR_PERMANENT(trim(reg.cdcode)) = 1 then
			(select id from OSE.UM_CORPS where source_code = 'STSP') 	-- non Titu Perm dans OSE
		else 
			(select id from OSE.UM_CORPS where source_code = 'STSV')    -- non Titu Vac dans OSE
		end as id_corps
		from hr.zd00@SIHAM.WORLD reg,        -- reglementation
			hr.zd01@SIHAM.WORLD lreg        -- libelle reglementation
        where
        reg.cdstco = 'HJ8'    
        --and reg.teregx = 'A' -- statut actif 
		-- v0.5 test des dates et pas statut actif        
        and p_date_systeme >= trunc(reg.dtdva)
        and (p_date_systeme <= trunc(reg.dtfva) or reg.dtfva is null)
		and trim(reg.cdcode) like 'C%'						-- les contractuels
        and (UM_EST_CTR_PERM_OU_VAC(trim(reg.cdcode)) <> 1) 	-- sauf ceux qui peuvent être PERM ou VAC
        and reg.nudoss = lreg.nudoss
        and trim(reg.cdcode) <> '00000'
	union
		-- code SIHAM statut_pip + lettre 'P' de PERMANENT
		select trim(reg.cdcode)||'P' as source_code
        ,substr(ltrim(lreg.liblon),1,40) as ll_pip
        ,trim(lreg.libabr) as lc_pip
        ,reg.dtdva
        ,reg.dtfva 
        ,reg.teregx
		,v_corps.id as id_corps
        from hr.zd00@SIHAM.WORLD reg,        -- reglementation
            hr.zd01@SIHAM.WORLD lreg,        -- libelle reglementation
			(select id, source_code 
			from um_corps where source_code in ('STSP') ) v_corps
        where
        -- statut pip
        reg.cdstco = 'HJ8'    
        --and reg.teregx = 'A' -- statut actif 
		-- v0.5 test des dates et pas statut actif        
        and p_date_systeme >= trunc(reg.dtdva)
        and (p_date_systeme <= trunc(reg.dtfva) or reg.dtfva is null)
		-- v2.1 30/11/20 ##A_PERSONNALISER_CHOIX_SIHAM## : voir fonction UM_EST_CTR_PERM_OU_VAC
		and (trim(reg.cdcode) like 'HB%'						-- heberges
			or (UM_EST_CTR_PERM_OU_VAC(trim(reg.cdcode)) = 1)	-- ou contractuels pouvants être Perm ou Vac dans ose			
			)
        and reg.nudoss = lreg.nudoss
        and trim(reg.cdcode) <> '00000'
	union
		-- code SIHAM statut_pip + lettre 'V' de VACATAIRE
		select trim(reg.cdcode)||'V' as source_code  -- code SIHAM statut_pip
        ,substr(ltrim(lreg.liblon),1,40) as ll_pip
        ,trim(lreg.libabr) as lc_pip
        ,reg.dtdva
        ,reg.dtfva 
        ,reg.teregx
		,v_corps.id as id_corps
        from hr.zd00@SIHAM.WORLD reg,        -- reglementation
            hr.zd01@SIHAM.WORLD lreg,        -- libelle reglementation
			(select id, source_code 
			from um_corps where source_code in ('STSV') ) v_corps
        where
        -- statut pip
        reg.cdstco = 'HJ8'    
        --and reg.teregx = 'A' -- statut actif 
		-- v0.5 test des dates et pas statut actif        
        and p_date_systeme >= trunc(reg.dtdva)
        and (p_date_systeme <= trunc(reg.dtfva) or reg.dtfva is null)
		-- v2.1 30/11/20 ##A_PERSONNALISER_CHOIX_SIHAM## : voir fonction UM_EST_CTR_PERM_OU_VAC
		and (trim(reg.cdcode) like 'HB%'						-- heberges
			or (UM_EST_CTR_PERM_OU_VAC(trim(reg.cdcode)) =1)	-- ou contractuels pouvants être Perm ou Vac dans ose			
			)
        and reg.nudoss = lreg.nudoss
        and trim(reg.cdcode) <> '00000'
  order by source_code
;
	
/*========= PROG PRINCIPAL PROCEDURE UM_SYNCHRO_GRADE ===============================*/
BEGIN
	/*------------ CORPS ---------*/
	--dbms_output.put_line('Lancement import corps pour OSE : ');
	FOR c1 in cur_corps LOOP
	
		v_id_corps := UM_EXISTE_CORPS(c1.source_code);
		IF v_id_corps <> 0 THEN
			-- Le corps existe deja : update ----------
			select LIBELLE_LONG, LIBELLE_COURT	INTO v_ll, v_lc
			from OSE.UM_CORPS
			where id = v_id_corps;
					
			IF ( v_ll <> c1.ll_corps
					or v_lc <> c1.lc_corps) THEN
				-- un libelle modifié => update	
				begin
					v_nb_update_cor := v_nb_update_cor+1 ;
					update OSE.UM_CORPS SET 
						LIBELLE_LONG = c1.ll_corps
						,LIBELLE_COURT = c1.lc_corps
					WHERE id = v_id_corps;
				exception
					-- when no_data_found then null;
					when others then
						rollback;
						dbms_output.put_line(' Pb update OSE.UM_CORPS : '||trim(c1.source_code));
				end;
			END IF;	
		ELSE
			BEGIN
				-- Le corps n'existe pas : insert ----------
				v_nb_insert_cor := v_nb_insert_cor+1 ;
				
				insert into UM_CORPS(LIBELLE_LONG, LIBELLE_COURT, SOURCE_ID, SOURCE_CODE)
				values (c1.ll_corps
					,c1.lc_corps
					,p_source_id
					,c1.source_code
					);
			EXCEPTION
				when others then
						rollback;
						dbms_output.put_line(' Pb insert corps : '||trim(c1.source_code));
			END;
		END IF;				
	END LOOP;   --- fin loop CORPS
	COMMIT;
	select count(*) INTO v_nb_total_cor from OSE.UM_CORPS;
	dbms_output.put_line(rpad(' synchro UM_CORPS',35,' ')||'-  nb_insert :'||v_nb_insert_cor||' - nb update :'||v_nb_update_cor||' - nb enreg total :'||v_nb_total_cor);
	
	/*------------ GRADES ---------*/
	--  Pour les TITU1 et STAGI : charger les grades
	FOR c2 in cur_grade LOOP
	
		v_id := UM_EXISTE_GRADE(c2.source_code);

		IF v_id <> 0 THEN
			-- Le grade existe deja : update ----------
			select LIBELLE_LONG, LIBELLE_COURT, CORPS_ID INTO v_ll, v_lc, v_corps_grade
			from OSE.UM_GRADE
			where id = v_id;
					
			IF ( v_ll <> c2.ll_grade
					or v_lc <> c2.lc_grade
					or v_corps_grade <> c2.id_corps) THEN
				begin
					v_nb_update_gr := v_nb_update_gr+1 ;
					update OSE.UM_GRADE SET 
						LIBELLE_LONG = c2.ll_grade
						,LIBELLE_COURT = c2.lc_grade
						,CORPS_ID = c2.id_corps
					WHERE id = v_id;
				EXCEPTION
					-- when no_data_found then null;
					when others then
						rollback;
						dbms_output.put_line(' Pb update grade : '||trim(c2.source_code));
				end;
			END IF;	
		ELSE
			BEGIN
				-- Le grade n'existe pas : insert ----------
				v_nb_insert_gr := v_nb_insert_gr+1 ;
				
				insert into UM_GRADE(LIBELLE_LONG, LIBELLE_COURT, CORPS_ID, SOURCE_ID, SOURCE_CODE)
				values (c2.ll_grade
					,c2.lc_grade
					,c2.id_corps
					,p_source_id
					,c2.source_code
					);
			EXCEPTION
				when others then
						rollback;
						dbms_output.put_line(' Pb insert UM_GRADE : '||trim(c2.source_code));
			END;
		END IF;		
						
	END LOOP; --- fin loop GRADE 
	COMMIT;
	select count(*) INTO v_nb_total_gr from OSE.UM_GRADE;
	dbms_output.put_line(rpad(' synchro UM_GRADE',35,' ')||'-  nb_insert :'||v_nb_insert_gr||' - nb update :'||v_nb_update_gr||' - nb enreg total :'||v_nb_total_gr);

	/*------------ STATUT_PIP ---------*/
	--  Pour les ANT et HEB : charger les statut_pip dans la table des grades
	FOR c3 in cur_statut_pip LOOP
	
		v_id := UM_EXISTE_GRADE(c3.source_code);

		IF v_id <> 0 THEN
			-- Le grade existe deja : update ----------
			select LIBELLE_LONG, LIBELLE_COURT, CORPS_ID INTO v_ll, v_lc, v_corps_grade
			from OSE.UM_GRADE
			where id = v_id;
					
			IF ( v_ll <> c3.ll_pip
					or v_lc <> c3.lc_pip
					or v_corps_grade <> c3.id_corps
					or v_corps_grade is null) THEN
				begin
					v_nb_update_pip := v_nb_update_pip+1 ;
					update OSE.UM_GRADE SET 
						LIBELLE_LONG = c3.ll_pip
						,LIBELLE_COURT = c3.lc_pip
						,CORPS_ID = c3.id_corps
					WHERE id = v_id;
				EXCEPTION
					-- when no_data_found then null;
					when others then
						rollback;
						dbms_output.put_line(' Pb update OSE.UM_GRADE (STATUT_PIP) : '||trim(c3.source_code));
				end;
			END IF;	
		ELSE
			BEGIN
				-- Le grade n'existe pas : insert ----------
				v_nb_insert_pip := v_nb_insert_pip+1 ;
				
				insert into UM_GRADE(LIBELLE_LONG, LIBELLE_COURT, SOURCE_ID, SOURCE_CODE, CORPS_ID)
				values (c3.ll_pip
					,c3.lc_pip
					,p_source_id
					,c3.source_code
					,c3.id_corps
					);
			EXCEPTION
				when others then
						rollback;
						dbms_output.put_line(' Pb insert UM_GRADE (STATUT_PIP) : '||trim(c3.source_code));
			END;
		END IF;

						
	END LOOP; --- fin loop GRADES PIP
	COMMIT;
	select count(*) INTO v_nb_total_pip from OSE.UM_GRADE;
	dbms_output.put_line(rpad(' synchro UM_GRADE (STATUT_PIP)',35,' ')||'-  nb_insert :'||v_nb_insert_pip||' - nb update :'||v_nb_update_pip||' - nb enreg total :'||v_nb_total_pip);

END;
/
