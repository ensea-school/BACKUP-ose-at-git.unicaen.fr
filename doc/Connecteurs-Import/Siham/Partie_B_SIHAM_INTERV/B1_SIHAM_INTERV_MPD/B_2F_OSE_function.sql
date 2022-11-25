/* ====================================================================================================
	# Detail du connecteur PARTIE B/ SIHAM_INTERV : synchro des intervenants - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	FONCTIONS UTILISES POUR LA SYNCHRO DES INTERVENANTS
			
	--- fonctions -------------- 
	OSE.UM_AFFECTE_STATUT
	OSE.UM_EXISTE_INTERVENANT
	OSE.UM_STAT_TRANSFERT_INDIVIDU
	OSE.UM_EXISTE_ADR_INTERVENANT
	OSE.UM_RECUP_INTERV_STATUT
	OSE.UM_CHGT_STATUT_VALIDE
	OSE.UM_EST_DOC_MCE
	OSE.UM_EST_VACATAIRE
	OSE.UM_MAJ_OSE_DONE
	OSE.UM_EXISTE_IBAN
	OSE.UM_INSERT_STATUT_VALIDE
	OSE.UM_MAJ_UM_TRANSFERT_INDIVIDU
	OSE.UM_AJOUT_UM_SYNCHRO_A_VALIDER
	OSE.UM_RECUP_NEW_MULTI_STATUT_AUTO

	-- OSE.UM_CALCULE_DATE_STATUT : fc V14 supprimée et regroupée dans UM_AFFECTE_STATUT
	-- OSE.UM_AFFICH_INTERV_STATUT : fc V14 supprimée car info mce dans T_UM_ENREG_STATUT
	-- OSE.UM_RECUP_INTERV_NB_HEURE_MCE : fc V14 supprimée car info mce dans T_UM_ENREG_STATUT
	----------------------------
	
	-- v2.0b 24/01/20 MYP : var v_stat_transfert : augmentation taille variable
	-- v2.1  07/07/20 MYP : modif regle  affectation statut Mapping_STATUT_SIHAM-OSE_v12.xlsx
	-- v2.2  19/11/20 MYP : differentes modif de mise en forme
	-- v3.0  04/12/20 MYP : adaptations pour Ose V15 
	-- v3.1  01-03/21 MYP : nouvelles fonctions pour gérer T_UM_ENREG_STATUT
	-- v3.2  15/06/21 MYP : report modifs V14 depuis ex v2.2
			-- v2.3 - 14/12/20 MYP : nouveau statut CONV_MAIEU
			-- v2.4 - 28/05/21 MYP : suppression espaces décalage
	-- v3.3  25/01/22 MYP : UM_AJOUT_UM_SYNCHRO_A_VALIDER : qd de IE à IE forcer date_deb au 01/09
	-- v3.4  14/06/22 MYP : remplacer UM_STATUT_INTERVENANT par UM_STATUT
	-- v3.5  26/07/22 MYP : modifier mapping tests statut siham pour les nouveaux codes HU
=====================================================================================================*/

/* --------------- VERSION V15.1 - ATIVE ------------------------------------------*/
create or replace FUNCTION OSE.UM_AFFECTE_STATUT(p_statut_pip VARCHAR2, p_gp_hie VARCHAR2, p_code_fonction VARCHAR2, p_temoin_fonc VARCHAR2, 
												p_code_emploi VARCHAR2, p_recrutement VARCHAR2, p_modserv VARCHAR2, p_position VARCHAR2, p_corps VARCHAR2, p_orec_type_vac VARCHAR2,
												p_d_deb_annee_univ DATE, p_dat_aff DATE, p_dat_pos DATE, p_dat_grade DATE, p_dat_fonct DATE, p_dat_modserv DATE,
												p_dat_statut DATE, p_date_systeme DATE, p_d_fin_annee_univ DATE) 
								RETURN T_UM_ENREG_STATUT IS
/* =============================================================
	UM_AFFECTE_STATUT
===============================================================*/
-- retourne un objet  tableau de type T_UM_ENREG_STATUT(ID, CODE_STATUT, CODE_TYPE_INTERVENANT, DATE_DEB_STATUT, DATE_FIN_STATUT)
-- v1.0 - 10/09/2018 - MYP - statuts detailles pour OSE suivant Mapping_STATUT_SIHAM-OSE_v3.xlsx
-- !!! SI le TEST des codes statut_pip siham changent alors penser à adapter la procedure UM_SELECT_INTERVENANT (tests en dur)

v_id_statut	 		NUMBER(9) 	 := 0;				-- v3.0 08/03/2021 ID statut qui sera affecté
v_code_statut		VARCHAR2(20) := 'HOSE'; 		-- v1.14 Code statut intervenant qui sera affecté : par défaut HORS HOSE = FLAG pour indiquer que critères non requis pour gestion dans OSE (pas d'enseignement)
v_type_interv		VARCHAR2(1)	 := '';				-- v3.0 08/03/2021 type statut intervenant qui sera affecté P = PERM / E = IE
v_date_deb_statut	DATE		 :=  p_d_deb_annee_univ;
v_date_fin_statut	DATE 		 :=  p_d_fin_annee_univ;
v_nb_h_mce			NUMBER(8,2)	 := 0;
v_annee_id			NUMBER(9) 	 := to_number(to_char(p_d_deb_annee_univ,'YYYY'));

-- v3.0b -- une seule fonction pour id statut, code et dates 
v_new_statut		T_UM_ENREG_STATUT := T_UM_ENREG_STATUT(v_id_statut,v_code_statut,v_type_interv, trunc(p_d_deb_annee_univ),trunc(p_d_fin_annee_univ), v_nb_h_mce);
   
BEGIN
    -- ##A_PERSONNALISER_CHOIX_SIHAM## et ##A_PERSONNALISER_CHOIX_OSE## - statuts detailles pour OSE suivant Mapping_STATUT_SIHAM-OSE_v3.xlsx
	
		-- TITU1 et STAGI ---------------------------------------------------------------------------------------------------------
	case when p_statut_pip in ('TITU1','STAGI') then
			case 
				when p_position = ('ACE04') and p_code_emploi = 'UEXTEC__01'			then v_new_statut.CODE_STATUT := 'ENS_MADE'; 	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_pos); 			-- Enseignants MAD Entrant (pas payés UM)
				when (p_gp_hie in ('SA') or (p_gp_hie = 'EA' and p_corps <> '364'))	then v_new_statut.CODE_STATUT := 'ENS_CH_UM'; 	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_grade);				-- Enseignants-chercheurs
				when p_gp_hie in ('DC') 												then v_new_statut.CODE_STATUT := 'ENS_1D'; 		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_grade);			-- Enseignant premier degré
				when (p_gp_hie in ('DA') or (p_gp_hie = 'EA' and p_corps = '364')) 
						and p_modserv not like 'TI%' 									then v_new_statut.CODE_STATUT := 'ENS_2D'; 		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_grade);			-- Enseignant second degré + EA corps 364 prof ensam
				when (p_gp_hie in ('DA') or (p_gp_hie = 'EA' and p_corps = '364'))
						and p_modserv like 'TI%' 										then v_new_statut.CODE_STATUT := 'SPART';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_grade, p_dat_modserv);	-- Service partagé FDE
				when p_gp_hie in ('OA') 												then v_new_statut.CODE_STATUT := 'INF_ORIEN_EDU';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_grade);		-- Personnel d'éducation et d'orientation
				when p_gp_hie in ('SB', 'SD', 'SP', 'MG','HU')							then v_new_statut.CODE_STATUT := 'ENS_HU';	 	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_grade);			-- Enseignant-chercheur HU TITU  -- v3.5  26/07/22
			else 
				case when p_recrutement = 'R' 											then v_new_statut.CODE_STATUT := 'CEV_TIT_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut,p_d_deb_annee_univ);	-- CEV TITU Rémunéré
					 when p_recrutement = 'G' 											then v_new_statut.CODE_STATUT := 'CEV_TIT_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut,p_d_deb_annee_univ);	-- CEV TITU Titre gracieux
				else
					v_new_statut.CODE_STATUT:= 'HOSE'; v_new_statut.DATE_DEB_STATUT := p_date_systeme;  -- Hors périmètre OSE : pas gérés dans OSE -- v1.14  
				end case;
			end case;
				
		-- CONTRACTUELS PERM ---------------------------------------------------------------------------------------------------------
		when p_statut_pip in ('C0102', 'C0322') and (p_code_fonction = 'UPD2' 
				or (p_code_fonction >= 'UD32' and p_code_fonction <= 'UD64') ) 			then v_new_statut.CODE_STATUT := 'DOC_MCE';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_fonct);			-- Doctorant contractuel avec MCE				
		when p_statut_pip = 'C0301' 													then v_new_statut.CODE_STATUT := 'ATER_UM';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- ATER
		when p_statut_pip = 'C2001' 													then v_new_statut.CODE_STATUT := 'ATER_50';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- ATER mi-temps	
		when p_statut_pip in ('C2006','C2008')					 						then v_new_statut.CODE_STATUT := 'ENS_ASS';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Enseignant associé
		when p_statut_pip in ('C2007','C2009')					 						then v_new_statut.CODE_STATUT := 'ENS_ASS_50';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Enseignant associé mi-temps
		when ((p_statut_pip >= 'C2010' and  p_statut_pip <= 'C2029')
				or (p_statut_pip >= 'C0602' and  p_statut_pip <= 'C0604')  -- v3.5  26/07/22
			 ) 																			then v_new_statut.CODE_STATUT := 'ENS_HU_CTR';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Enseignant-chercheur HU CTR	
		when p_statut_pip = 'C2042' 													then v_new_statut.CODE_STATUT := 'LECT';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Lecteurs
		when p_statut_pip in ('C2043','C2047') 											then v_new_statut.CODE_STATUT := 'MLV';			v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Maîtres de langues  -- v1.5c
		when p_statut_pip = 'C2049' and p_modserv = 'MS100'								then v_new_statut.CODE_STATUT := 'ENS_CH_CTR';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut,p_dat_modserv);		-- Enseignant-chercheur contractuel
		when p_statut_pip = 'C2049' and p_modserv <> 'MS100'							then v_new_statut.CODE_STATUT := 'ENS_CH_CTR_50';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut,p_dat_modserv);		-- Enseignant-chercheur contractuel mi-temps
		when p_statut_pip = 'C2051' and p_modserv = 'MS100'								then v_new_statut.CODE_STATUT := 'ENS_CTR';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut,p_dat_modserv);		-- Enseignant CDD
		when p_statut_pip = 'C2051' and p_modserv <> 'MS100'							then v_new_statut.CODE_STATUT := 'ENS_CTR_50';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut,p_dat_modserv);		-- Enseignant CDD mi-temps
		
		-- CONTRACTUELS VACATAIRES OU HEBERGES VACATAIRES -----------------------------------------------------------------------------
		when p_statut_pip = 'C2041' and p_recrutement = 'R'								then v_new_statut.CODE_STATUT := 'ATV_R';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ);	-- ATV Rémunéré
		when p_statut_pip = 'C2041' and p_recrutement = 'G'								then v_new_statut.CODE_STATUT := 'ATV_G';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ);	-- ATV Titre gracieux
		when p_statut_pip = 'HB111' and p_code_emploi = 'UEXTMAD_01' 					then v_new_statut.CODE_STATUT := 'MAD_1D';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- MAD premier degré
		when p_statut_pip = 'HB111' and p_code_emploi = 'UEXTMAD_02' 					then v_new_statut.CODE_STATUT := 'MAD_2D';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- MAD second degré
		when p_statut_pip = 'HB111' and p_code_emploi like 'UEXTPFA%' 					then v_new_statut.CODE_STATUT := 'PFA';			v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- PFA
		when p_statut_pip = 'HB112'	and p_code_emploi = 'UEXTMAD_02' 					then v_new_statut.CODE_STATUT := 'CONV_MAIEU';  v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Enseignant Maïeutique				-- v2.3 14/12/2020
		when p_statut_pip = 'HB112'									 					then v_new_statut.CODE_STATUT := 'CONV';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut);						-- Enseignant avec convention entrante
		when p_statut_pip like 'HB%' and p_code_fonction = 'UE01'						then v_new_statut.CODE_STATUT := 'CONV';		v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_fonct);			-- Hébergé avec convention entrante  	-- v1.11 -- v2.1 - 07/07/2020 HB%
		when p_statut_pip like 'HB%' and (p_code_fonction = 'UPD2' 
			or (p_code_fonction >= 'UD32' and p_code_fonction <= 'UD64') )				then v_new_statut.CODE_STATUT := 'CONV_MCE';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_dat_fonct);			-- Doctorant contractuel hors UM avec MCE à l'UM (convention) - v1.6
		when (p_statut_pip = 'C2038') and p_temoin_fonc ='OUI' and p_recrutement = 'R'	then v_new_statut.CODE_STATUT := 'CEV_TIT_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV TITU Rémunéré
		when (p_statut_pip = 'C2038') and p_temoin_fonc ='OUI' and p_recrutement = 'G' 	then v_new_statut.CODE_STATUT := 'CEV_TIT_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV TITU Titre gracieux
		when (p_statut_pip = 'C2038') and p_temoin_fonc ='NON' and p_recrutement = 'R'	then v_new_statut.CODE_STATUT := 'CEV_NTIT_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV NTITU Rémunéré
		when (p_statut_pip = 'C2038') and p_temoin_fonc ='NON' and p_recrutement = 'G' 	then v_new_statut.CODE_STATUT := 'CEV_NTIT_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV NTITU Titre gracieux
		
		-- HEBERGES VACATAIRES -----------------------------------------------------------------------------
		when p_statut_pip like 'HB%' and p_orec_type_vac = 'ATV' and p_recrutement = 'R' 	then v_new_statut.CODE_STATUT := 'ATV_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ);	-- ATV Rémunéré
		when p_statut_pip like 'HB%' and p_orec_type_vac = 'ATV' and p_recrutement = 'G'	then v_new_statut.CODE_STATUT := 'ATV_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ);	-- ATV Titre gracieux
		when p_statut_pip like 'HB%' and p_orec_type_vac = 'CEV' 
				and p_temoin_fonc ='OUI' and p_recrutement = 'R'						then v_new_statut.CODE_STATUT := 'CEV_TIT_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV TITU Rémunéré
		when p_statut_pip like 'HB%' and p_orec_type_vac = 'CEV' 
				and p_temoin_fonc ='OUI' and p_recrutement = 'G' 						then v_new_statut.CODE_STATUT := 'CEV_TIT_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV TITU Titre gracieux
		when p_statut_pip like 'HB%' and p_orec_type_vac = 'CEV'  
				and p_temoin_fonc ='NON' and p_recrutement = 'R'						then v_new_statut.CODE_STATUT := 'CEV_NTIT_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV NTITU Rémunéré
		when p_statut_pip like 'HB%' and p_orec_type_vac = 'CEV'  
				and p_temoin_fonc ='NON' and p_recrutement = 'G' 						then v_new_statut.CODE_STATUT := 'CEV_NTIT_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ); 	-- CEV NTITU Titre gracieux
		
		-- TOUS LES AUTRES C% ou HB% ---------------------------------------------------------------------------------------------------
		else 
			case when p_recrutement = 'R' 												then v_new_statut.CODE_STATUT := 'CEV_NTIT_R';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ);	-- CEV NTITU Rémunéré
				 when p_recrutement = 'G' 												then v_new_statut.CODE_STATUT := 'CEV_NTIT_G';	v_new_statut.DATE_DEB_STATUT := greatest(p_dat_aff, p_dat_statut, p_d_deb_annee_univ);	-- CEV NTITU Titre gracieux
				 else v_new_statut.CODE_STATUT := 'HOSE'; v_new_statut.DATE_DEB_STATUT := p_date_systeme;  -- Hors périmètre OSE : pas gérés dans OSE -- v1.14
			end case;
	end case;
	
    -- recup de l id statut + 
	IF v_new_statut.code_statut <> 'HOSE' then   -- v1.14
		select st.ID, typ.code INTO v_id_statut, v_type_interv
		from OSE.UM_STATUT st 				-- v3.4 14/06/22 
			, OSE.TYPE_INTERVENANT typ
		where st.code_statut = v_new_statut.code_statut
			and st.annee_id = v_annee_id	-- v3.4 14/06/22
			and st.type_intervenant_id = typ.id ;
		
		v_new_statut.id 					:= v_id_statut;
		v_new_statut.code_type_intervenant 	:= v_type_interv;
	END IF;
	-- controles date de debut ---
	v_date_deb_statut := nvl(v_new_statut.date_deb_statut,p_d_deb_annee_univ);
	if v_new_statut.date_deb_statut < p_d_deb_annee_univ then v_date_deb_statut := p_d_deb_annee_univ;
		else 
		if v_new_statut.date_deb_statut between p_d_deb_annee_univ and p_d_fin_annee_univ then v_date_deb_statut := v_new_statut.date_deb_statut;
			else v_date_deb_statut := p_d_fin_annee_univ;
		end if;
	end if;
	v_new_statut.date_deb_statut 		:= trunc(v_date_deb_statut);
	
	-- controles date de fin ---
	v_date_fin_statut := nvl(v_new_statut.date_fin_statut,p_d_fin_annee_univ);
	if v_new_statut.date_fin_statut < v_new_statut.date_deb_statut then v_date_fin_statut := v_new_statut.date_deb_statut; 
		else if v_new_statut.date_fin_statut > p_d_fin_annee_univ then v_date_fin_statut := p_d_fin_annee_univ;
			 end if;
	end if;
	v_new_statut.date_fin_statut 		:= trunc(v_date_fin_statut);

    RETURN v_new_statut;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_INTERVENANT(p_annee_id IN number, p_siham_matricule IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_INTERVENANT	-- v1.14
===============================================================*/
v_id_intervenant 	number(9) := 0;  

CURSOR cur_intervenant IS
	 select nvl(max(id),0)			-- v3.0 peut y en avoir plusieurs ou aucun : forcer 0
	 from OSE.UM_INTERVENANT
	 where annee_id = p_annee_id -- v1.14
		and source_code = p_siham_matricule
	;

BEGIN
	OPEN cur_intervenant;
    FETCH cur_intervenant INTO v_id_intervenant;

    return v_id_intervenant;
END;
/


create or replace FUNCTION OSE.UM_STAT_TRANSFERT_INDIVIDU(p_annee_id IN number) RETURN VARCHAR2 IS
/* =============================================================
	UM_STAT_TRANSFERT_INDIVIDU  -- v1.13 -- v2.0b -- v2.4
===============================================================*/
-- retourne le nombre de dossiers insérés ou modifiés de la table intermédiaire UM_TRANSFERT_INDIVIDU
v_statistiques 		VARCHAR2(1000) := '';  -- v2.0b
   
BEGIN
	
	select listagg(v.valeur, chr(10)) within group (order by V.valeur) INTO v_statistiques	-- v2.4 - 28/05/21
	FROM(
		select listagg(v.valeur,'||') within group (order by v.nom) as valeur
		from
		(
			select 'stat' as nom, listagg('- TEM_OSE_INSERT='||TEM_OSE_INSERT||' : '||count(*),'		|') within group (order by TEM_OSE_INSERT) as valeur
			from OSE.UM_TRANSFERT_INDIVIDU
			where annee_id = p_annee_id  -- v1.13
				and TEM_OSE_INSERT <> 'N' -- v2.2 pas concerne par insert
			group by TEM_OSE_INSERT
		 ) v
		union
		select listagg(v.valeur,'||') within group (order by v.nom) as valeur
		from
		(        
			select 'stat' as nom, listagg('- TEM_OSE_UPDATE='||TEM_OSE_UPDATE||' : '||count(*),'		| ') within group (order by TEM_OSE_UPDATE) as valeur
			from OSE.UM_TRANSFERT_INDIVIDU  
			where annee_id = p_annee_id  -- v1.13
				and TEM_OSE_UPDATE <> 'N' -- v2.2 pas concerne par update
			group by TEM_OSE_UPDATE
		) v
	) V;
    return (v_statistiques);
END;
/

-- grant execute on OSE.UM_STAT_TRANSFERT_INDIVIDU to Ose_Consult;  -- pour consultation Openreports


CREATE OR REPLACE FUNCTION OSE.UM_EXISTE_ADR_INTERVENANT(p_id_intervenant IN NUMBER) RETURN NUMBER IS
/* =============================================================
	UM_EXISTE_ADR_INTERVENANT
===============================================================*/
v_existe 	number(9) := 0;  

CURSOR cur_adr_intervenant IS
	 select 1
	 from OSE.UM_ADRESSE_INTERVENANT
	 where intervenant_id = p_id_intervenant
	;

BEGIN

   OPEN cur_adr_intervenant;
    FETCH cur_adr_intervenant INTO v_existe;

    return v_existe;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_RECUP_INTERV_STATUT(p_siham_matricule IN VARCHAR2, p_annee_id IN NUMBER, p_date_systeme IN DATE) RETURN T_UM_ENREG_STATUT IS
/* =============================================================
	UM_RECUP_INTERV_STATUT : retourne un enreg complet des infos du statut actuel
===============================================================*/
v_id_statut	 		NUMBER(9) 	 := 0;				-- v3.0 08/03/2021 ID statut
v_code_statut		VARCHAR2(20) := ''; 			
v_type_interv		VARCHAR2(1)	 := '';				-- v3.0 08/03/2021 type statut intervenant qui sera affecté P = PERM / E = IE
v_date_deb_statut	DATE		 :=  p_date_systeme;
v_date_fin_statut	DATE 		 :=  p_date_systeme;
v_nb_h_mce			NUMBER(8,2)	 := 0;	

-- v3.0b -- une seule fonction pour id statut, code et dates 
v_statut_actuel		T_UM_ENREG_STATUT := T_UM_ENREG_STATUT(0,v_code_statut,v_type_interv, trunc(v_date_deb_statut),trunc(v_date_fin_statut),v_nb_h_mce);

CURSOR cur_statut IS
	select i.statut_id, st.code_statut, typ.code, i.date_deb_statut, i.date_fin_statut, i.w_nb_heure_mce
			
	from OSE.UM_INTERVENANT i,
		OSE.UM_STATUT st,
		OSE.TYPE_INTERVENANT typ
	where trim(i.source_code) = trim(p_siham_matricule)
	  and i.annee_id = p_annee_id 
	  and i.date_deb_statut <= p_date_systeme and i.date_fin_statut >= p_date_systeme  -- v3.0 statut de la periode de synchro
	  and i.statut_id = st.id
	  and i.annee_id = st.annee_id	-- v3.4 14/06/22
	  and st.type_intervenant_id = typ.id
	;

BEGIN
   OPEN cur_statut;
    FETCH cur_statut  INTO v_id_statut, v_code_statut, v_type_interv, v_date_deb_statut, v_date_fin_statut, v_nb_h_mce;
		
	v_statut_actuel.id 						:= v_id_statut;
	v_statut_actuel.code_statut				:= v_code_statut;
	v_statut_actuel.code_type_intervenant 	:= v_type_interv;
	v_statut_actuel.date_deb_statut			:= v_date_deb_statut;
	v_statut_actuel.date_fin_statut			:= v_date_fin_statut;
	v_statut_actuel.nb_h_mce				:= v_nb_h_mce;
    return v_statut_actuel;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_CHGT_STATUT_VALIDE(p_siham_matricule IN VARCHAR2, p_annee_id IN number, p_statut_actuel IN T_UM_ENREG_STATUT, p_statut_nouveau IN T_UM_ENREG_STATUT) RETURN VARCHAR2 IS
/* =============================================================
	UM_CHGT_STATUT_VALIDE
===============================================================*/
-- Pour suivi des changements de statut dans OSE.UM_SYNCHRO_A_VALIDER : Détecte si un changment de statut a été validé dans la table
-- v1.13 ajout p_annee_id
v_chgt_statut_ok 	VARCHAR2(3)	:= 'NON';

CURSOR cur_statut IS
	select 'OUI'
	from OSE.UM_SYNCHRO_A_VALIDER s
	where s.matcle = p_siham_matricule and s.annee_id = p_annee_id
		and s.actu_statut_id = p_statut_actuel.id
		and s.new_statut_id = p_statut_nouveau.id
		and s.new_date_deb_statut = p_statut_nouveau.date_deb_statut
		-- -- si changement validé par DRH-BGME 
		and tem_validation = 'O' and d_validation is not null
		-- et pas encore transfere dans ose
		and d_transfert_force is null
	;
BEGIN
    -- recup code uo niveau defini pour extraction badge principal vers ARD
   OPEN cur_statut;
    FETCH cur_statut INTO v_chgt_statut_ok;

    return v_chgt_statut_ok;

END;
/


CREATE OR REPLACE FUNCTION OSE.UM_EST_DOC_MCE(p_statut_id IN NUMBER) RETURN BOOLEAN IS
/* =============================================================
	UM_EST_DOC_MCE
===============================================================*/
-- Retourne true si le statut est doctorant avec mission d'enseignement
v_est_bien_egal 	boolean 	:= false;  
v_trouve 			number(9) 	:= 0;
v_statut_doc_mce	varchar2(20) := '';	-- v3.0 04/12/20

CURSOR cur_statut IS
	 select id
	 from OSE.UM_STATUT  -- v3.4 14/06/22
	 where id = p_statut_id
		and trim(code_statut) = v_statut_doc_mce; 

BEGIN
	-- ##A_PERSONNALISER_CHOIX_OSE## : table UM_PARAM_ETABL -- v3.0 04/12/20
	select trim(valeur) INTO v_statut_doc_mce from UM_PARAM_ETABL where code = 'C_STRUCTURE_MERE'; 

   OPEN cur_statut;
    FETCH cur_statut INTO v_trouve;
	if v_trouve = 0 then
		v_est_bien_egal := false;
	else	
		v_est_bien_egal := true;
	end if;
    return v_est_bien_egal;

END;
/

CREATE OR REPLACE FUNCTION OSE.UM_EST_VACATAIRE(p_statut_id IN NUMBER) RETURN BOOLEAN IS
/* =============================================================
	UM_EST_VACATAIRE
===============================================================*/
-- Retourne true si le statut est de type vacataire, false si permanent (suivant table OSE.TYPE_INTERVENANT)
v_est_bien_egal 	boolean 	:= false;  
v_trouve 			number(9) 	:= 0;

CURSOR cur_statut IS
	 select st.id
	 from OSE.UM_STATUT st -- v3.4 14/06/22
		,OSE.TYPE_INTERVENANT ti
	 where st.id = p_statut_id
		and st.type_intervenant_id = ti.id
		and ti.code = 'E';		-- ##A_PERSONNALISER_CHOIX_OSE## type intervenant extérieur fourni avec Ose

BEGIN
   OPEN cur_statut;
    FETCH cur_statut INTO v_trouve;
	if v_trouve = 0 then
		v_est_bien_egal := false;
	else	
		v_est_bien_egal := true;
	end if;
    return v_est_bien_egal;

END;
/


CREATE OR REPLACE FUNCTION OSE.UM_MAJ_OSE_DONE(p_matricule IN VARCHAR2, p_annee_id IN number) RETURN BOOLEAN IS
/* =============================================================
	UM_MAJ_OSE_DONE
	v1.5b - création : retourne true si la maj/creation a été faite dans UM_INTERVENANT
	v1.13 - annee_id dans UM_TRANSFERT_INDIVIDU
===============================================================*/
-- retourne true si témoins OSE.UM_TRANSFERT_INDIVIDU insert ou update OK (DONE ou N pas a traiter)
v_est_bien_maj 	boolean 	:= false;  
v_creation_ose	varchar2(5) := '';
v_maj_ose		varchar2(5) := '';

CURSOR cur_creation_ose IS
	 select TEM_OSE_INSERT
	 from OSE.UM_TRANSFERT_INDIVIDU tr
	 where tr.matcle = p_matricule and tr.annee_id = p_annee_id; --v1.13
	 
CURSOR cur_maj_ose IS
	 select TEM_OSE_UPDATE
	 from OSE.UM_TRANSFERT_INDIVIDU tr
	 where tr.matcle = p_matricule and tr.annee_id = p_annee_id; --v1.13

BEGIN
	OPEN cur_creation_ose;
    FETCH cur_creation_ose INTO v_creation_ose;
	
	OPEN cur_maj_ose;
    FETCH cur_maj_ose INTO v_maj_ose;
	
	if v_creation_ose in ('DONE', 'N') or v_maj_ose in ('DONE', 'N') then
		v_est_bien_maj := true;
	else	
		v_est_bien_maj := false;
	end if;
    return v_est_bien_maj;
END;
/

CREATE OR REPLACE function OSE.UM_EXISTE_IBAN(p_siham_matricule IN VARCHAR2, p_iban IN VARCHAR2) RETURN NUMBER IS
/* =============================================================
    UM_EXISTE_INTERVENANT    -- v3.0  04/12/2020
===============================================================*/
v_id_intervenant     number(9) := 0;  

	CURSOR cur_intervenant IS
		 select id
		 from OSE.UM_INTERVENANT
		 where source_code = p_siham_matricule
			and trim(iban) = p_iban
		;
BEGIN

	   OPEN cur_intervenant;
		FETCH cur_intervenant INTO v_id_intervenant;

		return v_id_intervenant;
END;
/
CREATE OR REPLACE FUNCTION OSE.UM_INSERT_STATUT_VALIDE(p_siham_matricule IN VARCHAR2, p_annee_id IN number) RETURN VARCHAR2 IS
/* =============================================================
	UM_INSERT_STATUT_VALIDE
===============================================================*/
-- Pour suivi des changements de statut dans OSE.UM_SYNCHRO_A_VALIDER : Détecte si un changment de statut a été validé dans la table
-- v1.13 ajout p_annee_id
v_insert_new_statut 	VARCHAR2(10)	:= 'NON';
v_id 					NUMBER(9) 		:= 0;

CURSOR cur_statut IS
	select ID, 'INSERT'
	from OSE.UM_SYNCHRO_A_VALIDER s
	where s.matcle = p_siham_matricule
		and s.annee_id = p_annee_id  -- v1.13
		-- -- si changement validé par DRH-BGME  pour etre inséré en plus du précedent
		and tem_validation = 'I'
		and d_validation is not null
		-- et pas encore transfere dans ose
		and d_transfert_force is null
	;
BEGIN
    -- recup code uo niveau defini pour extraction badge principal vers ARD
   OPEN cur_statut;
    FETCH cur_statut INTO v_id, v_insert_new_statut;

    return v_insert_new_statut;

END;
/

CREATE OR REPLACE FUNCTION OSE.UM_MAJ_UM_TRANSFERT_INDIVIDU(p_matricule IN VARCHAR2, p_annee_id IN number, p_changement_statut IN VARCHAR2) RETURN BOOLEAN IS
/* =============================================================
	UM_MAJ_UM_TRANSFERT_INDIVIDU
	v3.1 09/03/21 creation procedure pour maj de la table 
===============================================================*/
-- retourne true si OSE.UM_TRANSFERT_INDIVIDU.CHANGEMENT_STATUT a pu etre maj
v_est_bien_maj 	boolean 	:= false;  

BEGIN
	BEGIN
	update OSE.UM_TRANSFERT_INDIVIDU tr
	set tr.CHANGEMENT_STATUT = substr(p_changement_statut,1,100)
	where tr.matcle = p_matricule and tr.annee_id = p_annee_id;
	v_est_bien_maj := true;
	
	EXCEPTION
		when others then v_est_bien_maj := false;
		commit;
	END;

    return v_est_bien_maj;
END;
/

CREATE OR REPLACE FUNCTION OSE.UM_AJOUT_UM_SYNCHRO_A_VALIDER(p_matricule IN VARCHAR2, p_annee_id IN number, p_temoin_validation IN VARCHAR2, p_date_validation IN DATE, p_date_transfert_force IN DATE,
																p_statut_actuel IN T_UM_ENREG_STATUT, p_statut_nouveau IN T_UM_ENREG_STATUT, p_param_gestion_statut IN VARCHAR2) RETURN BOOLEAN IS
/* =============================================================
	UM_MAJ_UM_TRANSFERT_INDIVIDU
	v3.1 09/03/21 creation procedure pour maj de la table 
===============================================================*/
-- retourne true si OSE.UM_TRANSFERT_INDIVIDU.CHANGEMENT_STATUT a pu etre maj
v_est_bien_insere 		boolean 	:= false;  
v_new_date_deb_statut	DATE		:= p_statut_nouveau.date_deb_statut;		-- v3.3  25/01/22


BEGIN
	BEGIN

	--- si IE à IE alors 1 seule période sur l annee et on ecrase -- v3.3  25/01/22
	if p_statut_actuel.code_type_intervenant = 'E' and p_statut_nouveau.code_type_intervenant = 'E' THEN
			v_new_date_deb_statut := p_statut_actuel.date_deb_statut;
	end if;
	
	insert into OSE.UM_SYNCHRO_A_VALIDER (D_HORODATAGE, NUDOSS, MATCLE, QUALIT , NOMUSE, PRENOM ,NOMPAT ,CHANGEMENT_STATUT, TEM_VALIDATION, D_VALIDATION, D_TRANSFERT_FORCE, ANNEE_ID,
											ACTU_STATUT_ID, ACTU_CODE_STATUT, ACTU_CODE_TYPE_INT, ACTU_DATE_DEB_STATUT, ACTU_DATE_FIN_STATUT, ACTU_NB_H_MCE,
											NEW_STATUT_ID, NEW_CODE_STATUT, NEW_CODE_TYPE_INT, NEW_DATE_DEB_STATUT, NEW_DATE_FIN_STATUT, NEW_NB_H_MCE, 
											PARAM_GESTION_STATUT)
			
	select tr.d_horodatage, tr.nudoss, tr.matcle, tr.qualit, tr.nomuse, tr.prenom, tr.nompat, tr.changement_statut, p_temoin_validation, p_date_validation, p_date_transfert_force, p_annee_id,
			p_statut_actuel.id, p_statut_actuel.code_statut, p_statut_actuel.code_type_intervenant, p_statut_actuel.date_deb_statut, p_statut_actuel.date_fin_statut, p_statut_actuel.nb_h_mce,
			p_statut_nouveau.id, p_statut_nouveau.code_statut, p_statut_nouveau.code_type_intervenant, v_new_date_deb_statut, p_statut_nouveau.date_fin_statut, p_statut_nouveau.nb_h_mce,			-- v3.3  25/01/22
			p_param_gestion_statut
	from OSE.UM_TRANSFERT_INDIVIDU tr
	where tr.matcle = p_matricule and tr.annee_id = p_annee_id and tr.changement_statut is not null 
	and not exists ( select 1 from OSE.UM_SYNCHRO_A_VALIDER s
					 where s.matcle = tr.matcle and s.changement_statut = tr.changement_statut and s.annee_id = p_annee_id  
							and s.ACTU_STATUT_ID = p_statut_actuel.id and s.NEW_STATUT_ID = p_statut_nouveau.id and s.NEW_DATE_DEB_STATUT = p_statut_nouveau.date_deb_statut
					);	

	v_est_bien_insere := true;
	
	EXCEPTION
		when others then v_est_bien_insere := false;
		commit;
	END;

    return v_est_bien_insere;
END;
/

create or replace FUNCTION OSE.UM_RECUP_NEW_MULTI_STATUT_AUTO(p_annee_id IN NUMBER, p_matricule IN VARCHAR2) RETURN T_UM_ENREG_STATUT IS
/* =============================================================
	UM_RECUP_NEW_MULTI_STATUT_AUTO
===============================================================*/
-- retourne un objet  tableau de type T_UM_ENREG_STATUT à partir de UM_SYNCHRO_A_VALIDER et tem_validation AI

v_id_statut	 		NUMBER(9) 	 := 0;				-- v3.0 08/03/2021 ID statut qui sera affecté
v_code_statut		VARCHAR2(20) := 'HOSE'; 		-- v1.14 Code statut intervenant qui sera affecté : par défaut HORS HOSE = FLAG pour indiquer que critères non requis pour gestion dans OSE (pas d'enseignement)
v_type_interv		VARCHAR2(1)	 := '';				-- v3.0 08/03/2021 type statut intervenant qui sera affecté P = PERM / E = IE
v_date_deb_statut	DATE		 := sysdate;
v_date_fin_statut	DATE 		 := sysdate;
v_nb_h_mce			NUMBER(8,2)	 := 0;	

-- v3.0b -- une seule fonction pour id statut, code et dates 
v_new_statut		T_UM_ENREG_STATUT := T_UM_ENREG_STATUT(v_id_statut,v_code_statut,v_type_interv, v_date_deb_statut,v_date_fin_statut, v_nb_h_mce);

BEGIN
	select NEW_STATUT_ID, NEW_CODE_STATUT, NEW_CODE_TYPE_INT, NEW_DATE_DEB_STATUT, NEW_DATE_FIN_STATUT, NEW_NB_H_MCE
		INTO v_new_statut.ID, v_new_statut.CODE_STATUT, v_new_statut.CODE_TYPE_INTERVENANT, v_new_statut.DATE_DEB_STATUT, v_new_statut.DATE_FIN_STATUT, v_new_statut.NB_H_MCE
	from OSE.UM_SYNCHRO_A_VALIDER s
	where s.annee_id = p_annee_id and s.matcle = p_matricule
		-- -- si changement validé par DRH-BGME  pour etre inséré en plus du précedent
		and tem_validation = 'AI'
		and d_validation is not null
		-- et pas encore transfere dans ose ou en cours de transfert dans ose
		and d_transfert_force is null
	;
	
	RETURN v_new_statut;
END;
/	

/*===============================================================*/
